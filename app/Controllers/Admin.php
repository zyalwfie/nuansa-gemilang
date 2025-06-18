<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\ProductModel;
use Myth\Auth\Models\UserModel;

class Admin extends BaseController
{
    protected $productModel, $userModel, $orderModel, $db, $builderUsers, $builderAuthGroupsUsers, $builderOrders;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->builderUsers = $this->db->table('users');
        $this->builderAuthGroupsUsers = $this->db->table('auth_groups_users');
        $this->builderOrders = $this->db->table('orders');

        $this->productModel = new ProductModel();
        $this->userModel = new UserModel();
        $this->orderModel = new OrderModel();
    }

    public function index()
    {
        $this->builderOrders->select('SUM(total_price) AS total_spent');
        $query = $this->builderOrders->get();
        $totalEarning = $query->getRow();

        $completedOrdersCount = $this->orderModel->where('status', 'berhasil')->countAllResults();
        $pendingOrdersCount = $this->orderModel->where('status', 'tertunda')->countAllResults();
        $cancelledOrdersCount = $this->orderModel->where('status', 'gagal')->countAllResults();
        $totalEarningAmount = $totalEarning ? $totalEarning->total_spent : 0;

        $data = [
            'page_title' => 'Dashboard | Nuansa',
            'total_earning' => $totalEarningAmount,
            'completed_orders_count' => $completedOrdersCount,
            'pending_orders_count' => $pendingOrdersCount,
            'cancel_orders_count' => $cancelledOrdersCount,
            'orders' => $this->orderModel->findAll(4)
        ];
        
        return view('dashboard/admin/index', $data);
    }

    // Product Controller
    public function products()
    {
        $data = [
            'index' => 1,
            'page_title' => 'Dasbor | Admin | Produk',
            'products' => $this->productModel->findAll()
        ];

        return view('dashboard/admin/products/index', $data);
    }

    public function createProduct()
    {
        $data = [
            'page_title_create' => 'Dasbor | Tambah Produk'
        ];

        return view('dashboard/admin/products/form', $data);
    }

    public function storeProduct()
    {
        $postData = $this->request->getPost();
        $postData['slug'] = url_title($postData['name'], '-', true);

        $imageFile = $this->request->getFile('image');
        $additionalFiles = $this->request->getFileMultiple('additional_images');

        if ($imageFile->isValid()) {
            $postData['image'] = 'image';
        }

        if (!empty($additionalFiles)) {
            $postData['additional_images'] = 'additional_images';
        }

        if ($imageFile->getError() !== 0) {
            return redirect()->back()->withInput()->with('error_image', 'Gambar harus diunggah!');
        }

        $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($imageFile->getExtension(), $allowedExt)) {
            return redirect()->back()->withInput()->with('error_image', 'Format gambar tidak valid!');
        }

        if ($imageFile->getSize() > 2097152) {
            return redirect()->back()->withInput()->with('error_image', 'Ukuran gambar terlalu besar! Maksimal 2MB.');
        }

        if (!$this->validateData($postData, $this->productModel->getValidationRules(), $this->productModel->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if (!$imageFile->hasMoved()) {
            $tempName = $imageFile->getRandomName();
            $tempPath = FCPATH . 'img/uploads/temp/' . $tempName;
            $imageFile->move(FCPATH . 'img/uploads/temp', $tempName);

            $noBgPath = removeBackground($tempPath);
            @unlink($tempPath);

            if (!$noBgPath) {
                return redirect()->route('admin.products.create')->withInput()->with('error_image', 'Kesalahan teknis, coba unggah kembali!');
            }

            $postData['image'] = $noBgPath;
        }

        $additionalImageNames = [];
        if (!empty($additionalFiles)) {
            foreach ($additionalFiles as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $fileName = $file->getRandomName();
                    $file->move(FCPATH . 'img/uploads/adds', $fileName);
                    $additionalImageNames[] = $fileName;
                }
            }
            $postData['additional_images'] = json_encode($additionalImageNames);
        } else {
            $postData['additional_images'] = null;
        }

        $result = $this->productModel->save($postData);

        if ($result) {
            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
        } else {
            return redirect()->route('admin.products.index')->with('failed', 'Produk gagal ditambahkan!');
        }
    }

    public function editProduct($slug)
    {
        $data = [
            'page_title_edit' => 'Dasbor | Ubah Produk',
            'product' => $this->productModel->where('slug', $slug)->first()
        ];

        return view('dashboard/admin/products/form', $data);
    }

    public function updateProduct($id)
    {
        $product = $this->productModel->find($id);

        $postData = $this->request->getPost();
        $postData['slug'] = $product['name'] !== $postData['name'] ? url_title($postData['name'], '-', true) : $product['slug'];

        $imageFile = $this->request->getFile('image');
        $additionalFiles = $this->request->getFileMultiple('additional_images');

        if ($imageFile && $imageFile->isValid() && $imageFile->getError() === 0) {
            $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($imageFile->getExtension(), $allowedExt)) {
                return redirect()->back()->withInput()->with('error_image', 'Format gambar tidak valid!');
            }
            if ($imageFile->getSize() > 2097152) {
                return redirect()->back()->withInput()->with('error_image', 'Ukuran gambar terlalu besar! Maksimal 2MB.');
            }
            if (!$imageFile->hasMoved()) {
                $tempName = $imageFile->getRandomName();
                $tempPath = FCPATH . 'img/uploads/temp/' . $tempName;
                $imageFile->move(FCPATH . 'img/uploads/temp', $tempName);

                $noBgPath = removeBackground($tempPath);
                @unlink($tempPath);

                if (!$noBgPath) {
                    return redirect()->back()->withInput()->with('error_image', 'Kesalahan teknis, coba unggah kembali!');
                }

                if (!empty($product['image']) && file_exists(FCPATH . $product['image']) && $product['image'] !== 'img/uploads/main/default-img-product.svg') {
                    @unlink(FCPATH . $product['image']);
                }

                $postData['image'] = $noBgPath;
            }
        } else {
            $postData['image'] = $product['image'];
        }

        $additionalImageNames = [];
        if (!empty($additionalFiles)) {
            foreach ($additionalFiles as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $fileName = $file->getRandomName();
                    $file->move(FCPATH . 'img/uploads/adds', $fileName);
                    $additionalImageNames[] = $fileName;
                }
            }
            if (!empty($additionalImageNames)) {
                $postData['additional_images'] = json_encode($additionalImageNames);
            } else {
                $postData['additional_images'] = $product['additional_images'];
            }
        } else {
            $postData['additional_images'] = $product['additional_images'];
        }

        if (!$this->validateData($postData, $this->productModel->getValidationRules(), $this->productModel->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $postData['id'] = $id;
        $result = $this->productModel->save($postData);

        if ($result) {
            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
        } else {
            return redirect()->route('admin.products.index')->with('failed', 'Produk gagal diperbarui!');
        }
    }

    public function destroyProduct($slug)
    {
        $product = $this->productModel->where('slug', $slug)->first();
        if (!$product) {
            return redirect()->route('admin.products.index')->with('failed', 'Produk tidak ditemukan!');
        }

        if (!empty($product['image']) && file_exists(FCPATH . 'img/uploads/main/' . $product['image']) && $product['image'] !== 'img/uploads/main/default-img-product.svg') {
            @unlink(FCPATH . 'img/uploads/main/' . $product['image']);
        }

        if (!empty($product['additional_images'])) {
            $additionalImages = json_decode($product['additional_images'], true);
            if (is_array($additionalImages)) {
                foreach ($additionalImages as $img) {
                    $imgPath = FCPATH . 'img/uploads/adds/' . $img;
                    if (file_exists($imgPath)) {
                        @unlink($imgPath);
                    }
                }
            }
        }

        $this->productModel->delete($product['id']);
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }

    // User Controller
    public function users()
    {
        $this->builderUsers->select('users.id as userId, email, full_name, username, avatar, active');
        $this->builderUsers->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $this->builderUsers->where('auth_groups_users.group_id', 2);
        $query = $this->builderUsers->get();
        $users = $query->getResult();

        $data = [
            'index' => 1,
            'page_title' => 'Dasbor | Admin | Pengguna',
            'users' => $users
        ];

        return view('dashboard/admin/users/index', $data);
    }

    public function destroyUser($username)
    {
        $queryAuthGroupsUsers = $this->builderAuthGroupsUsers->get();
        $authGroupsUsers = $queryAuthGroupsUsers->getResult();

        $authGroupsUserId = [];

        foreach ($authGroupsUsers as $row) {
            $authGroupsUserId[] = $row->user_id;
        }

        $user = $this->builderUsers->where('username', $username)->get()->getRow();
        $userId = $user->id;

        if (!$user && !in_array($userId, $authGroupsUserId)) {
            return redirect()->route('admin.users')->with('failed', 'Pengguna tidak ditemukan!');
        }

        if (!empty($user->avatar) && $user->avatar !== 'default-img-avatar.svg') {
            $avatarPath = FCPATH . 'img/uploads/avatar/' . $user->avatar;
            if (file_exists($avatarPath)) {
                @unlink($avatarPath);
            }
        }

        $this->builderUsers->where('username', $username)->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus!');
    }

    // Order Controller
    public function orders()
    {
        $orders = $this->orderModel->findAll();

        $data = [
            'page_title' => 'Nuansa | Admin | Pesanan',
            'orders' => $orders
        ];

        return view('dashboard/admin/orders/index', $data);
    }

    public function showOrder($orderId)
    {
        $this->builderOrders->select('order_items.id as orderItemId, name, price, image, quantity');
        $this->builderOrders->join('order_items', 'orders.id = order_items.order_id');
        $this->builderOrders->join('products', 'order_items.product_id = products.id');
        $this->builderOrders->where('order_items.order_id', $orderId);
        $query = $this->builderOrders->get();
        $orderItems = $query->getResult();

        $order = $this->orderModel->where('id', $orderId)->first();

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

        return view('dashboard/admin/orders/show', $data);
    }

    public function updateOrder($orderId)
    {
        $this->orderModel->update($orderId, [
            'status' => 'berhasil',
        ]);

        return redirect()->back()->with('proofed', 'Pesanan berhasil disetujui!');
    }

    // Profile Controller
    public function profile()
    {
        $data = [
            'page_title' => "Dasbor | Admin | Profil",
        ];

        return view('dashboard/admin/profile/index', $data);
    }

    public function editProfile()
    {
        $data = [
            'page_title' => 'Dasbord | Admin | Edit Profil'
        ];

        return view('dashboard/admin/profile/edit', $data);
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
            return redirect()->route('admin.profile.index')->with('success', 'Profil berhasil diperbarui!');
        } else {
            return redirect()->route('admin.profile.index')->with('failed', 'Profil gagal diperbarui!');
        }
    }
}
