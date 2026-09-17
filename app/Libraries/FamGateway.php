<?php

namespace App\Libraries;

/**
 * FamGateway PHP SDK Library for CodeIgniter 4
 * Official UPI Payment Gateway Integration
 * 
 * Get your API Key from: https://famgateway.in/
 */
class FamGateway
{
    /**
     * =========================================================================
     * 🔑 FAMGATEWAY API KEY
     * Replace with your API key from https://famgateway.in/api-keys.php
     * Or set FAMGATEWAY_API_KEY in your .env file
     * =========================================================================
     */
    private $apiKey;
    private $baseUrl = "https://famgateway.in";

    public function __construct($apiKey = null)
    {
        $this->apiKey = $apiKey ?? (getenv('FAMGATEWAY_API_KEY') ?: 'YOUR_FAMGATEWAY_API_KEY_HERE');
    }

    /**
     * Create a new payment session
     *
     * @param float $amount
     * @param string $redirectUrl
     * @param string $webhookUrl
     * @param array $customer
     * @return array|false
     */
    public function createPayment($amount, $redirectUrl = '', $webhookUrl = '', $customer = [])
    {
        $params = [
            'api_key' => $this->apiKey,
            'amount' => number_format((float)$amount, 2, '.', ''),
        ];
        
        if (!empty($redirectUrl)) {
            $params['redirect_url'] = $redirectUrl;
        }
        if (!empty($webhookUrl)) {
            $params['webhook_url'] = $webhookUrl;
        }
        if (!empty($customer['name'])) {
            $params['customer_name'] = $customer['name'];
        }
        if (!empty($customer['email'])) {
            $params['customer_email'] = $customer['email'];
        }
        if (!empty($customer['phone'])) {
            $params['customer_phone'] = $customer['phone'];
        }

        $url = $this->baseUrl . "/api/qr.php?" . http_build_query($params);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 25);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'KuroPanel-FamGateway/2.0');
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            $data = json_decode($response, true);
            return $data;
        }

        return false;
    }

    /**
     * Verify payment status for an order
     *
     * @param string $orderId
     * @return array|false
     */
    public function verifyOrder($orderId)
    {
        $url = $this->baseUrl . "/api/verify-order.php?" . http_build_query([
            'api_key' => $this->apiKey,
            'order_id' => $orderId
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'KuroPanel-FamGateway/2.0');
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            return json_decode($response, true);
        }

        return false;
    }

    /**
     * Verify authenticity of incoming webhook payload
     *
     * @param string $rawPostData
     * @param string $signature
     * @return array|false
     */
    public function verifyWebhook($rawPostData, $signature)
    {
        $expectedSignature = hash_hmac('sha256', $rawPostData, $this->apiKey);
        if (!empty($signature) && hash_equals($expectedSignature, (string)$signature)) {
            return json_decode($rawPostData, true);
        }
        
        return false;
    }
}
