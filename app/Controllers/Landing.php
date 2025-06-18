<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CartModel;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\ProductModel;

class Landing extends BaseController
{
    protected $products, $carts, $orders, $orderItems, $payments, $db, $cartsTotal;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->carts = new CartModel();
        $this->products = new ProductModel();
        $this->orders = new OrderModel();
        $this->orderItems = new OrderItemModel();
        $this->payments = new PaymentModel();
    }

    public function index()
    {
        $data = [
            'page_title' => 'Nuansa | Bean Bag',
            'featured_products' => $this->products->where('is_featured', 1)->orderBy('name', 'ASC')->findAll(3),
            'carts_count' => (!logged_in()) ? 0 : $this->carts->where('user_id', user()->id)->countAllResults()
        ];

        return view('landing/index', $data);
    }

    public function shop()
    {
        $data = [
            'page_title' => 'Nuansa | Belanja',
            'products' => $this->products->findAll(),
            'featured_products' => $this->products->where('is_featured', 1)->orderBy('name', 'ASC')->findAll(3),
            'carts_count' => (!logged_in()) ? 0 : $this->carts->where('user_id', user()->id)->countAllResults()
        ];

        return view('landing/shop/index', $data);
    }

    public function showShop($slug)
    {
        $product = $this->products->where('slug', $slug)->first();

        $data = [
            'page_title' => "Nuansa | " . $product['name'],
            'featured_products' => $this->products->where('is_featured', 1)->orderBy('name', 'ASC')->findAll(3),
            'carts_count' => (!logged_in()) ? 0 : $this->carts->where('user_id', user()->id)->countAllResults(),
            'product' => $product
        ];

        return view('landing/shop/show', $data);
    }

    public function about()
    {
        $data = [
            'page_title' => 'Nuansa | Tentang Kami',
            'featured_products' => $this->products->where('is_featured', 1)->orderBy('name', 'ASC')->findAll(3),
            'carts_count' => (!logged_in()) ? 0 : $this->carts->where('user_id', user()->id)->countAllResults()
        ];

        return view('landing/about', $data);
    }

    public function service()
    {
        $data = [
            'page_title' => 'Nuansa | Layanan',
            'featured_products' => $this->products->where('is_featured', 1)->orderBy('name', 'ASC')->findAll(3),
            'carts_count' => (!logged_in()) ? 0 : $this->carts->where('user_id', user()->id)->countAllResults()
        ];

        return view('landing/service', $data);
    }

    public function contact()
    {
        $data = [
            'page_title' => 'Nuansa | Kontak',
            'featured_products' => $this->products->where('is_featured', 1)->orderBy('name', 'ASC')->findAll(3),
            'carts_count' => (!logged_in()) ? 0 : $this->carts->where('user_id', user()->id)->countAllResults()
        ];

        return view('landing/contact', $data);
    }

    public function cart()
    {
        $cartsBuilder = $this->db->table('carts');
        $query = $cartsBuilder
            ->select('carts.id as cart_id, user_id, product_id, quantity, price_at_add, name, price, stock, image')
            ->join('products', 'products.id = carts.product_id')
            ->get();
        $carts = $query->getResult();

        foreach ($carts as $cart) {
            $this->cartsTotal += $cart->price_at_add;
        }

        $data = [
            'page_title' => 'Nuansa | Halaman Keranjang',
            'featured_products' => $this->products->where('is_featured', 1)->orderBy('name', 'ASC')->findAll(3),
            'carts' => $carts,
            'cartsTotal' => $this->cartsTotal,
            'carts_count' => $this->carts->where('user_id', user()->id)->countAllResults()
        ];

        return view('landing/cart', $data);
    }

    public function addToCart()
    {
        $quantity = $this->request->getPost('quantity') ?? 1;
        $productId = $this->request->getPost('product_id');
        $product = $this->products->find($productId);
        $cart = $this->carts->where(['product_id' => $product['id'], 'user_id' => user()->id])->first();
        $currentCartQty = $cart ? (int)$cart['quantity'] : 0;
        $totalRequestedQty = $currentCartQty + $quantity;

        if ($product['stock'] < 1 || $totalRequestedQty > $product['stock']) {
            return redirect()->back()->withInput()->with('not_in_stock', 'Stok produk kurang dari yang kamu pesan');
        }

        if ($cart) {
            $newQuantity = $totalRequestedQty;
            $newPriceAtAdd = $product['price'] * $newQuantity;
            $this->carts->update($cart['id'], [
                'quantity' => $newQuantity,
                'price_at_add' => $newPriceAtAdd
            ]);
        } else {
            $this->carts->save([
                'user_id' => user()->id,
                'product_id' => $productId,
                'quantity' => $totalRequestedQty,
                'price_at_add' => $product['price'] * $totalRequestedQty
            ]);
        }

        $this->products->update($productId, ['stock' => $product['stock'] - $totalRequestedQty]);
        return redirect()->route('landing.cart.index');
    }

    public function increaseCartQuantity($cartId)
    {
        $cart = $this->carts->find($cartId);
        if (!$cart || $cart['user_id'] !== user()->id) {
            return redirect()->route('landing.cart.index');
        }

        $product = $this->products->find($cart['product_id']);
        if (!$product) {
            return redirect()->route('landing.cart.index');
        }

        if ($product['stock'] > 0) {
            $newQuantity = $cart['quantity'] + 1;
            $newPriceAtAdd = $product['price'] * $newQuantity;
            $newStock = $product['stock'] - 1;
            $this->carts->update($cartId, [
                'quantity' => $newQuantity,
                'price_at_add' => $newPriceAtAdd
            ]);
            $this->products->update($product['id'], [
                'stock' => $newStock
            ]);
        }

        return redirect()->route('landing.cart.index');
    }

    public function decreaseCartQuantity($cartId)
    {
        $cart = $this->carts->find($cartId);
        if (!$cart || $cart['user_id'] !== user()->id) {
            return redirect()->route('landing.cart.index');
        }

        if ($cart['quantity'] > 1) {
            $product = $this->products->find($cart['product_id']);
            $newQuantity = $cart['quantity'] - 1;
            $newPriceAtAdd = $product['price'] * $newQuantity;
            $newStock = $product['stock'] + 1;
            $this->carts->update($cartId, [
                'quantity' => $newQuantity,
                'price_at_add' => $newPriceAtAdd
            ]);
            $this->products->update($product['id'], [
                'stock' => $newStock
            ]);
        }

        return redirect()->route('landing.cart.index');
    }

    public function destroyCart($cartId)
    {
        $cart = $this->carts->find($cartId);
        if ($cart && $cart['user_id'] === user()->id) {
            $product = $this->products->find($cart['product_id']);
            if ($product) {
                $restoredStock = $product['stock'] + $cart['quantity'];
                $this->products->update($product['id'], ['stock' => $restoredStock]);
            }
            $this->carts->delete($cartId);
        }
        return redirect()->route('landing.cart.index');
    }

    public function paymentCreate()
    {
        $carts = $this->carts->where('user_id', user()->id)->findAll();
        $postData = $this->request->getPost();

        if (!$this->validateData($postData, $this->orders->getValidationRules(), $this->orders->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        } elseif (!$carts) {
            return redirect()->back()->withInput()->with('empty_carts', 'Tidak ada apa-apa di dalam keranjang!');
        }

        $postData['user_id'] = user()->id;
        $this->orders->save($postData);
        $order = $this->orders->orderBy('created_at', 'DESC')->first();

        if (isset($postData['product_id'], $postData['quantity'])) {
            foreach ($postData['product_id'] as $idx => $productId) {
                $quantity = isset($postData['quantity'][$idx]) ? $postData['quantity'][$idx] : 1;
                $this->orderItems->save([
                    'order_id' => $order['id'],
                    'product_id' => $productId,
                    'user_id' => user()->id,
                    'quantity' => $quantity
                ]);
            }
        }

        $this->carts->where('user_id', user()->id)->delete();
        $this->payments->save([
            'order_id' => $order['id'],
        ]);

        return redirect()->route('landing.cart.payment.index', [$order['id']]);
    }

    public function payment($orderId)
    {
        $orders = $this->orders->where('id', $orderId)->first();
        $payment = $this->payments->where('order_id', $orderId)->first();

        if (!$orders) {
            return redirect()->route('landing.shop');
        }

        if ($payment && $payment['proof_of_payment']) {
            return redirect()->route('landing.cart.payment.done');
        }

        $data = [
            'page_title' => 'Nuansa | Pembayaran',
            'featured_products' => $this->products->where('is_featured', 1)->orderBy('name', 'ASC')->findAll(3),
            'carts_count' => $this->carts->where('user_id', user()->id)->countAllResults(),
            'order_id' => $orderId
        ];

        return view('landing/payment', $data);
    }

    public function paymentUpload()
    {
        $file = $this->request->getFile('proof_of_payment');
        $orderId = $this->request->getPost('order_id');
        $uriString = $this->request->getPost('uri_string');
        $errors = [];

        if (!$file || !$file->isValid()) {
            $errors['proof_of_payment'] = 'Bukti pembayaran wajib diunggah.';
        } else {
            $mimeType = $file->getMimeType();
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
            if (!in_array($mimeType, $allowedTypes)) {
                $errors['proof_of_payment'] = 'File harus berupa gambar (jpg, jpeg, png) atau PDF.';
            } elseif ($file->getSize() > 2097152) {
                $errors['proof_of_payment'] = 'Ukuran file maksimal 2MB.';
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $newName = $file->getRandomName();
        $file->move(FCPATH . 'img/uploads/proof', $newName);

        $payment = $this->payments->where('order_id', $orderId)->first();
        if ($payment) {
            $this->payments->update($payment['id'], [
                'proof_of_payment' => $newName
            ]);
        }

        if ($uriString === 'dashboard/user/orders/show/' . $orderId) {
            return redirect()->back()->with('proofed', 'File bukti berhasil diunggah!');
        }

        return redirect()->route('landing.cart.payment.done');
    }

    public function paymentUpdate()
    {
        $file = $this->request->getFile('proof_of_payment');
        $orderId = $this->request->getPost('order_id');
        $errors = [];

        dd(uri_string() === 'dashboard/user/orders/show/' . $orderId);

        if (!$file || !$file->isValid()) {
            $errors['proof_of_payment'] = 'Bukti pembayaran wajib diunggah.';
        } else {
            $mimeType = $file->getMimeType();
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($mimeType, $allowedTypes)) {
                $errors['proof_of_payment'] = 'File harus berupa gambar (jpg, jpeg, png) atau PDF.';
            } elseif ($file->getSize() > 2097152) {
                $errors['proof_of_payment'] = 'Ukuran file maksimal 2MB.';
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $newName = $file->getRandomName();
        $file->move(FCPATH . 'img/uploads/proof', $newName);

        $payment = $this->payments->where('order_id', $orderId)->first();
        if ($payment) {
            if (!empty($payment['proof_of_payment'])) {
                $oldPath = FCPATH . 'img/uploads/proof/' . $payment['proof_of_payment'];
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $this->payments->update($payment['id'], [
                'proof_of_payment' => $newName
            ]);
        }

        return redirect()->back()->with('proofed', 'File bukti berhasil diperbarui!');
    }

    public function paymentDone()
    {
        $data = [
            'page_title' => 'Nuansa | Pembayaran',
            'featured_products' => $this->products->where('is_featured', 1)->orderBy('name', 'ASC')->findAll(3),
            'carts_count' => $this->carts->where('user_id', user()->id)->countAllResults(),
        ];

        return view('landing/thanks', $data);
    }
}
