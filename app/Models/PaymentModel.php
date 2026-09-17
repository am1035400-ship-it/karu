<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'order_id',
        'user_id',
        'username',
        'amount',
        'status',
        'utr',
        'created_at',
        'updated_at'
    ];

    public function getPaymentByOrderId($orderId)
    {
        return $this->where('order_id', $orderId)->first();
    }

    public function getUserPayments($userId, $limit = 20)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('id', 'DESC')
                    ->findAll($limit);
    }
}
