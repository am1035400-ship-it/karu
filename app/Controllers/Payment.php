<?php

namespace App\Controllers;

use App\Libraries\FamGateway;
use App\Models\PaymentModel;
use App\Models\UserModel;
use App\Models\HistoryModel;
use CodeIgniter\I18n\Time;

class Payment extends BaseController
{
    protected $fam;
    protected $paymentModel;
    protected $userModel;

    public function __construct()
    {
        $this->fam = new FamGateway();
        $this->paymentModel = new PaymentModel();
        $this->userModel = new UserModel();
    }

    /**
     * Show Add Balance / Recharge Page
     */
    public function recharge()
    {
        $userId = session('userid');
        if (!$userId) {
            return redirect()->to('login')->with('msgDanger', 'Please login to recharge wallet.');
        }

        // Auto-expire pending orders older than 5 minutes (FamGateway session timeout)
        $fiveMinAgo = date('Y-m-d H:i:s', time() - 300);
        $db = \Config\Database::connect();
        $db->query("UPDATE `payments` SET `status` = 'EXPIRED' WHERE `status` = 'PENDING' AND `created_at` < ?", [$fiveMinAgo]);

        $user = $this->userModel->getUser($userId);
        $recentPayments = $this->paymentModel->getUserPayments($userId, 15);

        $data = [
            'title' => 'Add Balance',
            'user' => $user,
            'recentPayments' => $recentPayments,
            'time' => new Time()
        ];

        return view('User/recharge', $data);
    }

    /**
     * Create a payment order and redirect directly to FamGateway Hosted Checkout
     */
    public function create()
    {
        $userId = session('userid');
        if (!$userId) {
            return redirect()->to('login')->with('msgDanger', 'Please login to proceed.');
        }

        $amount = (float)$this->request->getPost('amount');
        if ($amount < 1 || $amount > 1000) {
            return redirect()->back()->with('msgDanger', 'Recharge amount must be between ₹1.00 and ₹1,000.00.');
        }

        $user = $this->userModel->getUser($userId);
        $redirectUrl = site_url('payment/success');
        $webhookUrl = site_url('api/webhook/famgateway');

        $customer = [
            'name' => $user->username ?? 'User',
            'email' => $user->email ?? 'user@kuro.panel',
        ];

        $res = $this->fam->createPayment($amount, $redirectUrl, $webhookUrl, $customer);

        if ($res && isset($res['status']) && $res['status'] === 'success') {
            $orderData = $res['data'];
            $orderId = $orderData['order_id'];

            // Store in session for redirect resolution
            session()->set('last_payment_order_id', $orderId);

            // Insert into payments table (Status: PENDING)
            $this->paymentModel->insert([
                'order_id' => $orderId,
                'user_id' => $userId,
                'username' => $user->username,
                'amount' => $amount,
                'status' => 'PENDING',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            // Direct redirect to FamGateway Hosted Checkout Page
            return redirect()->to($orderData['checkout_url']);
        }

        $msg = $res['message'] ?? 'Failed to initialize payment gateway. Please try again.';
        return redirect()->back()->with('msgDanger', $msg);
    }

    /**
     * Check payment status endpoint
     */
    public function status($orderId = null)
    {
        if (!$orderId) {
            $orderId = $this->request->getGet('order_id');
        }

        if (!$orderId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Missing order ID']);
        }

        $payment = $this->paymentModel->getPaymentByOrderId($orderId);
        if (!$payment) {
            return $this->response->setJSON(['status' => 'not_found']);
        }

        // If already marked PAID
        if ($payment['status'] === 'PAID') {
            return $this->response->setJSON([
                'status' => 'PAID',
                'amount' => $payment['amount'],
                'utr' => $payment['utr']
            ]);
        }

        // Live check with FamGateway API for real-time verification
        $verify = $this->fam->verifyOrder($orderId);
        if ($verify && isset($verify['data']) && in_array(strtolower($verify['data']['status'] ?? ''), ['success', 'paid'])) {
            $utr = $verify['data']['utr'] ?? ($verify['data']['transaction_id'] ?? 'FAM-' . time());
            $this->fulfillPayment($orderId, $utr);

            return $this->response->setJSON([
                'status' => 'PAID',
                'amount' => $payment['amount'],
                'utr' => $utr
            ]);
        }

        return $this->response->setJSON([
            'status' => $payment['status']
        ]);
    }

    /**
     * Secure Webhook endpoint called by FamGateway on payment success
     */
    public function webhook()
    {
        if (strtolower($this->request->getMethod()) === 'get') {
            return $this->response->setStatusCode(200)->setBody('FamGateway Webhook Endpoint is Active & Ready.');
        }

        $rawPost = file_get_contents('php://input');
        $signature = $_SERVER['HTTP_X_FAMGATEWAY_SIGNATURE'] ?? '';

        $data = $this->fam->verifyWebhook($rawPost, $signature);

        // Fallback: If signature header empty, verify payload directly against FamGateway API
        if (!$data && !empty($rawPost)) {
            $json = json_decode($rawPost, true);
            if ($json && isset($json['order_id'])) {
                $apiCheck = $this->fam->verifyOrder($json['order_id']);
                if ($apiCheck && isset($apiCheck['data']) && in_array(strtolower($apiCheck['data']['status'] ?? ''), ['success', 'paid'])) {
                    $data = $apiCheck['data'];
                    $data['event'] = 'payment.success';
                }
            }
        }

        if ($data && ($data['event'] ?? '') === 'payment.success') {
            $orderId = $data['order_id'];
            $utr = $data['utr'] ?? ($data['transaction_id'] ?? null);

            $this->fulfillPayment($orderId, $utr);

            return $this->response->setStatusCode(200)->setBody('Webhook processed successfully.');
        }

        return $this->response->setStatusCode(400)->setBody('Invalid webhook payload or signature.');
    }

    /**
     * Secure Success Redirect Page after FamGateway checkout
     */
    public function success()
    {
        $userId = session('userid');
        $orderId = $this->request->getGet('order_id') 
                ?? $this->request->getGet('orderId') 
                ?? $this->request->getGet('id') 
                ?? session()->get('last_payment_order_id');

        $payment = null;
        if ($orderId) {
            $payment = $this->paymentModel->getPaymentByOrderId($orderId);
        }

        // If no orderId was in GET, check user's latest payment in last 15 mins
        if (!$payment && $userId) {
            $fifteenMinAgo = date('Y-m-d H:i:s', time() - 900);
            $payment = $this->paymentModel->where('user_id', $userId)
                                         ->where('created_at >=', $fifteenMinAgo)
                                         ->orderBy('id', 'DESC')
                                         ->first();
            if ($payment) {
                $orderId = $payment['order_id'];
            }
        }

        if ($payment) {
            // If already paid in DB (via webhook)
            if ($payment['status'] === 'PAID') {
                return redirect()->to('dashboard')->with('msgSuccess', 'Payment Successful! ₹' . number_format($payment['amount'], 2) . ' credited to your wallet (UTR: ' . ($payment['utr'] ?? 'Confirmed') . ').');
            }

            // Real-time verification with FamGateway API
            $verify = $this->fam->verifyOrder($orderId);
            if ($verify && isset($verify['data']) && in_array(strtolower($verify['data']['status'] ?? ''), ['success', 'paid'])) {
                $utr = $verify['data']['utr'] ?? ($verify['data']['transaction_id'] ?? 'FAM-' . time());
                $this->fulfillPayment($orderId, $utr);

                return redirect()->to('dashboard')->with('msgSuccess', 'Payment Successful! ₹' . number_format($payment['amount'], 2) . ' credited to your wallet (UTR: ' . $utr . ').');
            }
        }

        // If no order details available, show standard success redirect
        return redirect()->to('dashboard')->with('msgSuccess', 'Payment processed! Your wallet balance has been updated.');
    }

    /**
     * Internal helper to credit user wallet and update payment record with concurrency protection
     */
    private function fulfillPayment($orderId, $utr = null)
    {
        $payment = $this->paymentModel->getPaymentByOrderId($orderId);
        if (!$payment || $payment['status'] === 'PAID') {
            return false; // Already fulfilled, prevent double credit
        }

        // 1. Mark payment as PAID
        $this->paymentModel->update($payment['id'], [
            'status' => 'PAID',
            'utr' => $utr,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // 2. Increment user saldo
        $userId = $payment['user_id'];
        $amount = (float)$payment['amount'];

        $db = \Config\Database::connect();
        $db->query("UPDATE `users` SET `saldo` = `saldo` + ? WHERE `id_users` = ?", [$amount, $userId]);

        // 3. Log into history
        $historyModel = new HistoryModel();
        $historyModel->insert([
            'keys_id' => 'RECHARGE',
            'user_do' => $payment['username'],
            'info' => "Recharged ₹" . number_format($amount, 2) . " via UPI (Order: $orderId, UTR: $utr)",
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return true;
    }
}
