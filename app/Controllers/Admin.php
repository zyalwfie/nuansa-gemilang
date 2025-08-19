<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\OrderModel;
use App\Models\ProductModel;
use Myth\Auth\Models\UserModel;
use App\Controllers\BaseController;

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
        $this->builderOrders
            ->select('SUM(total_price) AS total_spent')
            ->where('status =', 'berhasil');
        $query = $this->builderOrders->get();
        $totalEarning = $query->getRow();

        $completedOrdersCount = $this->orderModel->where('status', 'berhasil')->countAllResults();
        $pendingOrdersCount = $this->orderModel->where('status', 'tertunda')->countAllResults();
        $cancelledOrdersCount = $this->orderModel->where('status', 'gagal')->countAllResults();
        $totalEarningAmount = $totalEarning ? $totalEarning->total_spent : 0;

        $orders = $this->builderOrders
            ->select('full_name, username, email, label, phone_number, street_address, orders.status, total_price, notes, orders.created_at, orders.updated_at')
            ->join('users', 'users.id = orders.user_id')
            ->join('addresses', 'addresses.id = orders.address_id')
            ->limit(5)
            ->orderBy('created_at', 'desc')
            ->get()
            ->getResultArray();

        $data = [
            'page_title' => 'Dashboard | Nuansa',
            'total_earning' => $totalEarningAmount,
            'completed_orders_count' => $completedOrdersCount,
            'pending_orders_count' => $pendingOrdersCount,
            'cancel_orders_count' => $cancelledOrdersCount,
            'orders' => $orders
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
        $orders = $this->builderOrders
            ->select('full_name, username, email, label, phone_number, street_address, orders.status, total_price, notes, orders.created_at, orders.updated_at, orders.id')
            ->join('users', 'users.id = orders.user_id')
            ->join('addresses', 'addresses.id = orders.address_id')
            ->get()
            ->getResultArray();

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

        $order = $this->builderOrders
            ->select('full_name, username, email, label, phone_number, street_address, orders.status, total_price, notes, orders.created_at, orders.updated_at, orders.id')
            ->join('users', 'users.id = orders.user_id')
            ->join('addresses', 'addresses.id = orders.address_id')
            ->where('orders.id', $orderId)
            ->get()
            ->getRowArray();

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

    // Report Controller
    public function reports()
    {
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $ordersBuilder = $this->db->table('orders');
        $ordersBuilder->select('full_name, username, email, label, phone_number, street_address, orders.status, total_price, notes, orders.created_at, orders.updated_at, orders.id')
            ->join('users', 'users.id = orders.user_id')
            ->join('addresses', 'addresses.id = orders.address_id')
            ->where('orders.status', 'berhasil');

        if ($startDate) {
            $ordersBuilder->where('orders.created_at >=', $startDate);
        }
        if ($endDate) {
            $ordersBuilder->where('orders.created_at <=', $endDate . ' 23:59:59');
        }

        $query = $ordersBuilder->get();
        $filteredOrders = $query->getResultArray();

        $totalSales = array_reduce($filteredOrders, function ($carry, $order) {
            return $carry + $order['total_price'];
        }, 0);

        $data = [
            'pageTitle' => 'Dasbor | Admin | Laporan Transaksi',
            'filteredOrders' => $filteredOrders,
            'totalSales' => $totalSales,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        return view('dashboard/admin/report/index', $data);
    }

    public function previewReportPdf()
    {
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $ordersBuilder = $this->db->table('orders');
        $ordersBuilder->select('full_name, username, email, label, phone_number, street_address, orders.status, total_price, notes, orders.created_at, orders.updated_at, orders.id')
            ->join('users', 'users.id = orders.user_id')
            ->join('addresses', 'addresses.id = orders.address_id')
            ->where('orders.status', 'berhasil');

        if ($startDate) {
            $ordersBuilder->where('orders.created_at >=', $startDate);
        }
        if ($endDate) {
            $ordersBuilder->where('orders.created_at <=', $endDate . ' 23:59:59');
        }

        $query = $ordersBuilder->get();
        $filteredOrders = $query->getResultArray();

        $totalSales = array_reduce($filteredOrders, function ($carry, $order) {
            return $carry + $order['total_price'];
        }, 0);

        $data = [
            'pageTitle' => 'Preview Laporan Penjualan',
            'orders' => $filteredOrders,
            'totalSales' => $totalSales,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        return view('dashboard/admin/report/preview', $data);
    }

    public function exportReportPdf()
    {
        if (!class_exists('Dompdf\Dompdf')) {
            throw new \RuntimeException('Dompdf library is not installed. Please run: composer require dompdf/dompdf');
        }

        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $ordersBuilder = $this->db->table('orders');
        $ordersBuilder->select('full_name, username, email, label, phone_number, street_address, orders.status, total_price, notes, orders.created_at, orders.updated_at, orders.id')
            ->join('users', 'users.id = orders.user_id')
            ->join('addresses', 'addresses.id = orders.address_id')
            ->where('orders.status', 'berhasil');

        if ($startDate) {
            $ordersBuilder->where('orders.created_at >=', $startDate);
        }
        if ($endDate) {
            $ordersBuilder->where('orders.created_at <=', $endDate . ' 23:59:59');
        }

        $query = $ordersBuilder->get();
        $filteredOrders = $query->getResultArray();

        $totalSales = array_reduce($filteredOrders, function ($carry, $order) {
            return $carry + $order['total_price'];
        }, 0);

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        $html = $this->generatePdfContent($filteredOrders, $totalSales, $startDate, $endDate);

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $filename = 'laporan_penjualan_';
        if ($startDate && $endDate) {
            $filename .= date('d_m_Y', strtotime($startDate)) . '_to_' . date('d_m_Y', strtotime($endDate));
        } elseif ($startDate) {
            $filename .= 'dari_' . date('d_m_Y', strtotime($startDate));
        } elseif ($endDate) {
            $filename .= 'sampai_' . date('d_m_Y', strtotime($endDate));
        } else {
            $filename .= 'semua_periode';
        }
        $filename .= '.pdf';

        // Output the generated PDF to Browser
        // Parameters:
        // 1. filename
        // 2. options: 'D' = Download, 'I' = Inline (display in browser), 'F' = Save to file, 'S' = Return as string
        $dompdf->stream($filename, ["Attachment" => true]);
    }

    private function generatePdfContent($orders, $totalSales, $startDate = null, $endDate = null)
    {
        $html = '
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <title>Nuansa Gemilang | Laporan Penjualan</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    color: #333;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    width: 90%;
                    margin: 0 auto;
                }
                .header {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .header h1 {
                    margin: 0;
                    font-size: 22px;
                    color: #2c3e50;
                }
                .header p {
                    margin: 2px 0;
                    font-size: 12px;
                    color: #555;
                }
                .title {
                    text-align: center;
                    margin: 10px 0 20px 0;
                }
                .title h2 {
                    margin: 0;
                    font-size: 18px;
                    color: #2c3e50;
                }
                .title p {
                    margin: 4px 0;
                    font-size: 13px;
                    color: #777;
                }
                .summary {
                    background: #f8f9fa;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                    padding: 12px;
                    margin-bottom: 20px;
                    text-align: center;
                }
                .summary h3 {
                    margin: 0 0 6px 0;
                    font-size: 14px;
                    color: #444;
                }
                .summary .amount {
                    font-size: 20px;
                    font-weight: bold;
                    color: #27ae60;
                }
                .report-info {
                    background: #f8f9fa;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                    padding: 12px;
                    margin-bottom: 20px;
                }
                .report-info p {
                    margin: 3px 0;
                    font-size: 13px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                table thead {
                    background: #2c3e50;
                    color: white;
                }
                table th, table td {
                    padding: 8px 10px;
                    border: 1px solid #ddd;
                    font-size: 12px;
                    text-align: left;
                }
                table tbody tr:nth-child(even) {
                    background: #f2f2f2;
                }
                .footer {
                    text-align: center;
                    font-size: 11px;
                    color: #666;
                    margin-top: 30px;
                }
            </style>
        </head>
        <body>
            <div class="container">

                <!-- Header -->
                <div class="header">
                    <h1>Nuansa Gemilang</h1>
                    <p>Jln. Swakarsa 8, Lombok, Nusa Tenggara Barat, Indonesia</p>
                    <p>+62 878-6625-5327 | baiqlenilestari3@gmail.com</p>
                </div>

                <!-- Title -->
                <div class="title">
                    <h2>Laporan Penjualan</h2>';

            if ($startDate && $endDate) {
                $html .= "<p>Periode: " . date('d F Y', strtotime($startDate)) . " - " . date('d F Y', strtotime($endDate)) . "</p>";
            } elseif ($startDate) {
                $html .= "<p>Mulai dari: " . date('d F Y', strtotime($startDate)) . "</p>";
            } elseif ($endDate) {
                $html .= "<p>Sampai dengan: " . date('d F Y', strtotime($endDate)) . "</p>";
            } else {
                $html .= "<p>Semua Periode</p>";
            }

            $html .= '
                </div>

                <!-- Summary -->
                <div class="summary">
                    <h3>Total Penjualan</h3>
                    <div class="amount">Rp' . number_format($totalSales, 0, ',', '.') . '</div>
                </div>

                <!-- Report Info -->
                <div class="report-info">
                    <p>Total Transaksi: ' . count($orders) . ' pesanan</p>
                    <p>Status: Semua transaksi berhasil</p>
                </div>

                <!-- Table -->
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Penerima</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>';

            if (empty($orders)) {
                $html .= '
                        <tr>
                            <td colspan="6" style="text-align:center;">Tidak ada data penjualan yang ditemukan.</td>
                        </tr>';
            } else {
                foreach ($orders as $index => $order) {
                    $html .= "
                        <tr>
                            <td>" . ($index + 1) . "</td>
                            <td>" . date('d/m/Y', strtotime($order['created_at'])) . "</td>
                            <td>" . htmlspecialchars($order['full_name'] ?? $order['username']) . "</td>
                            <td>" . htmlspecialchars($order['email']) . "</td>
                            <td>" . htmlspecialchars($order['phone_number'] ?? '-') . "</td>
                            <td>Rp " . number_format($order['total_price'], 0, ',', '.') . "</td>
                        </tr>";
                }
            }

            $html .= '
                    </tbody>
                </table>

                <!-- Footer -->
                <div class="footer">
                    <p>Laporan ini dicetak pada: ' . date('d F Y H:i:s') . '</p>
                    <p>© ' . date('Y') . ' Nuansa Gemilang - Laporan Penjualan</p>
                </div>

            </div>
        </body>
        </html>';

        return $html;
    }
}
