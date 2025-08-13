<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use Myth\Auth\Models\UserModel;

class User extends BaseController
{
    protected $orders, $db, $builderOrders, $userModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->builderOrders = $this->db->table('orders');
        $this->orders = new OrderModel();
        $this->userModel = new UserModel();
    }

    public function index(): string
    {
        $userId = user()->id;

        $this->builderOrders->select('user_id, SUM(total_price) AS total_spent');
        $this->builderOrders->where('user_id', $userId);
        $this->builderOrders->groupBy('user_id');

        $query = $this->builderOrders->get();
        $totalSpent = $query->getRow();

        $completedOrdersCount = $this->orders->where('user_id', $userId)->where('status', 'berhasil')->countAllResults();
        $pendingOrdersCount = $this->orders->where('user_id', $userId)->where('status', 'tertunda')->countAllResults();
        $totalSpentAmount = $totalSpent ? $totalSpent->total_spent : 0;

        $data = [
            'page_title' => 'Dashboard | Nuansa',
            'total_spent' => $totalSpentAmount,
            'completed_orders_count' => $completedOrdersCount,
            'pending_orders_count' => $pendingOrdersCount,
            'orders' => $this->orders->where('user_id', $userId)->findAll()
        ];

        return view('dashboard/user/index', $data);
    }

    public function orders()
    {
        $data = [
            'page_title' => 'Nuansa | Data Pesanan',
            'orders' => $this->orders->where('user_id', user()->id)->findAll(),
        ];

        return view('dashboard/user/orders/index', $data);
    }

    public function showOrder($orderId)
    {
        $this->builderOrders->select('order_items.id as orderItemId, name, price, image, quantity');
        $this->builderOrders->join('order_items', 'orders.id = order_items.order_id');
        $this->builderOrders->join('products', 'order_items.product_id = products.id');
        $this->builderOrders->where('orders.user_id', user()->id);
        $this->builderOrders->where('order_items.order_id', $orderId);
        $query = $this->builderOrders->get();
        $orderItems = $query->getResult();

        $order = $this->orders->where('id', $orderId)->first();

        $proofOfPayment = $this->builderOrders->select('proof_of_payment')
            ->join('payments', 'orders.id = payments.order_id')
            ->where('payments.order_id', $orderId)
            ->get()
            ->getRow();

        $data = [
            'page_title' => 'Nuansa | Detail Pesanan',
            'order_items' => $orderItems,
            'order' => $order,
            'proof_of_payment' => $proofOfPayment
        ];

        return view('dashboard/user/orders/show', $data);
    }
}
