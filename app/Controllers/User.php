<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\RatingModel;
use Myth\Auth\Models\UserModel;

class User extends BaseController
{
    protected $orders, $orderItems, $ratings, $db, $ordersBuilder, $userModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->ordersBuilder = $this->db->table('orders');
        $this->orders = new OrderModel();
        $this->orderItems = new OrderItemModel();
        $this->ratings = new RatingModel();
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
            ->where('orders.status !=', 'berhasil')
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
        $this->ordersBuilder->select('order_items.id as orderItemId, name, price, image, quantity, is_rated');
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

    public function history()
    {
        $orders = $this->ordersBuilder
            ->select('full_name, username, email, label, phone_number, street_address, orders.id, orders.status, total_price, notes, orders.created_at, orders.updated_at')
            ->join('users', 'users.id = orders.user_id')
            ->join('addresses', 'addresses.id = orders.address_id')
            ->where('orders.user_id', user()->id)
            ->where('orders.status =', 'berhasil')
            ->orderBy('created_at', 'desc')
            ->get()
            ->getResultArray();

        $data = [
            'page_title' => 'Dasbor | Riwayat',
            'orders' => $orders,
        ];

        return view('dashboard/user/history/index', $data);
    }

    public function showHistory($orderId)
    {
        $this->ordersBuilder->select('order_items.id as orderItemId, products.id as productId, name, price, image, quantity, is_rated');
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
            'page_title' => 'Nuansa | Riwayat | Detail Pesanan',
            'order_items' => $orderItems,
            'order' => $order,
            'proof_of_payment' => $proofOfPayment
        ];

        return view('dashboard/user/history/show', $data);
    }

    public function rateProduct()
    {
        $rules = [
            'order_item_id' => 'required|integer',
            'rating' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'errors' => $this->validator->getErrors()
            ])->setStatusCode(422);
        }

        $orderItemId = $this->request->getPost('order_item_id');
        $productId = $this->request->getPost('product_id');
        $rating = $this->request->getPost('rating');

        $this->orderItems->update($orderItemId, [
            'is_rated' => 1
        ]);
        $this->ratings->insert([
            'product_id' => $productId,
            'star' => $rating
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Terima kasih, rating berhasil disimpan!',
            'data' => [
                'order_item_id' => $orderItemId,
                'rating' => $rating
            ]
        ]);
    }
}
