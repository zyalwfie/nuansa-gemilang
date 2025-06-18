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

    public function profile()
    {
        $data = [
            'page_title' => "Dasbor | Pengguna | Profil",
        ];

        return view('dashboard/user/profile/index', $data);
    }

    public function editProfile()
    {
        $data = [
            'page_title' => 'Dasbord | Pengguna | Edit Profil'
        ];

        return view('dashboard/user/profile/edit', $data);
    }

    public function updateProfile()
    {
        $user = user();
        $userId = $user->id;
        $postData = $this->request->getPost();
        $avatarFile = $this->request->getFile('avatar');

        $postData['id'] = $userId;

        $rules = $this->userModel->validationRules;
        $rules['id'] = 'permit_empty';

        $rules['email'] = str_replace('{id}', $userId, $rules['email']);
        $rules['username'] = str_replace('{id}', $userId, $rules['username']);

        if (!$this->userModel->validate($postData)) {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }

        $updateData = [
            'id' => $userId,
            'full_name' => $postData['full_name'],
            'email' => $postData['email'],
            'username' => $postData['username']
        ];

        if ($avatarFile && $avatarFile->isValid() && !$avatarFile->hasMoved()) {
            $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($avatarFile->getExtension(), $allowedExt)) {
                return redirect()->back()->withInput()->with('error_avatar', 'Format gambar tidak valid!');
            }
            if ($avatarFile->getSize() > 2097152) {
                return redirect()->back()->withInput()->with('error_avatar', 'Ukuran gambar terlalu besar! Maksimal 1MB.');
            }
            $avatarName = $avatarFile->getRandomName();
            $avatarFile->move(FCPATH . 'img/uploads/avatar', $avatarName);

            if (!empty($user->avatar) && $user->avatar !== 'default-img-avatar.svg') {
                $oldAvatarPath = FCPATH . 'img/uploads/avatar/' . $user->avatar;
                if (file_exists($oldAvatarPath)) {
                    @unlink($oldAvatarPath);
                }
            }
            $updateData['avatar'] = $avatarName;
        }

        $result = $this->userModel->save($updateData);

        if ($result) {
            return redirect()->route('user.profile.index')->with('success', 'Profil berhasil diperbarui!');
        } else {
            return redirect()->route('user.profile.index')->with('failed', 'Profil gagal diperbarui!');
        }
    }
}
