<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use Myth\Auth\Models\UserModel;

class User extends BaseController
{
    protected $orders, $db, $ordersBuilder, $userModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->ordersBuilder = $this->db->table('orders');
        $this->orders = new OrderModel();
        $this->userModel = new UserModel();
    }

    public function index(): string
    {
        $userId = user()->id;

        $this->ordersBuilder->select('user_id, SUM(total_price) AS total_spent');
        $this->ordersBuilder->where('user_id', $userId);
        $this->ordersBuilder->groupBy('user_id');

        $query = $this->ordersBuilder->get();
        $totalSpent = $query->getRow();

        $completedOrdersCount = $this->orders->where('user_id', $userId)->where('status', 'berhasil')->countAllResults();
        $pendingOrdersCount = $this->orders->where('user_id', $userId)->where('status', 'tertunda')->countAllResults();
        $totalSpentAmount = $totalSpent ? $totalSpent->total_spent : 0;

        $orders = $this->ordersBuilder
            ->select('full_name, username, email, label, phone_number, street_address, orders.status, total_price, notes, orders.created_at, orders.updated_at')
            ->join('users', 'users.id = orders.user_id')
            ->join('addresses', 'addresses.id = orders.address_id')
            ->where('orders.user_id', user()->id)
            ->get()
            ->getResultArray();

        $data = [
            'page_title' => 'Dashboard | Nuansa',
            'total_spent' => $totalSpentAmount,
            'completed_orders_count' => $completedOrdersCount,
            'pending_orders_count' => $pendingOrdersCount,
            'orders' => $orders
        ];

        return view('dashboard/user/index', $data);
    }

    public function orders()
    {
        $orders = $this->ordersBuilder
            ->select('full_name, username, email, label, phone_number, street_address, orders.id, orders.status, total_price, notes, orders.created_at, orders.updated_at')
            ->join('users', 'users.id = orders.user_id')
            ->join('addresses', 'addresses.id = orders.address_id')
            ->where('orders.user_id', user()->id)
            ->get()
            ->getResultArray();

        $data = [
            'page_title' => 'Nuansa | Data Pesanan',
            'orders' => $orders,
        ];

        return view('dashboard/user/orders/index', $data);
    }

    public function showOrder($orderId)
    {
        $this->ordersBuilder->select('order_items.id as orderItemId, name, price, image, quantity');
        $this->ordersBuilder->join('order_items', 'orders.id = order_items.order_id');
        $this->ordersBuilder->join('products', 'order_items.product_id = products.id');
        $this->ordersBuilder->where('orders.user_id', user()->id);
        $this->ordersBuilder->where('order_items.order_id', $orderId);
        $query = $this->ordersBuilder->get();
        $orderItems = $query->getResult();

        $order = $this->ordersBuilder
            ->select('full_name, username, email, label, phone_number, street_address, orders.id, orders.status, total_price, notes, orders.created_at, orders.updated_at')
            ->join('users', 'users.id = orders.user_id')
            ->join('addresses', 'addresses.id = orders.address_id')
            ->where('orders.id', $orderId)
            ->get()
            ->getRowArray();

        $proofOfPayment = $this->ordersBuilder->select('proof_of_payment')
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
