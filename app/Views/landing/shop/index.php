<?= $this->extend('landing/layouts/app'); ?>

<?= $this->section('page_title'); ?>
<?= $page_title ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<!-- Start Hero Section -->
<div class="hero">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">~
                <div class="intro-excerpt">
                    <h1>Belanja</h1>
                    <p class="mb-4">
                        Temukan berbagai produk menarik dengan harga terbaik di toko kami. Jelajahi pilihan kami dan nikmati pengalaman belanja yang mudah dan menyenangkan!
                    </p>
                    <p>
                        <a href="<?= base_url(route_to('landing.shop')) ?>" class="btn btn-secondary me-2">Belanja</a><a href="<?= in_groups('admin') ? base_url(route_to('admin.index')) : base_url(route_to('user.index')) ?>" class="btn btn-white-outline">Jelajahi</a>
                    </p>
                </div>
            </div>
            <div class="col-lg-7 d-flex justify-content-end">
                <div class="img-wrapper">
                    <img src="<?= base_url('img/hero-image.png') ?>" alt="Bean Bag" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->

<div class="my-1"></div>

<!-- Start Shop Section -->
<div class="untree_co-section product-section before-footer-section">
    <div class="container">
        <div class="row justify-content-between mb-4">
            <div class="col-12 col-md-4 mb-3">
                <h2 class="h-3">Produk</h2>
            </div>
            <div class="col-12 col-md-6">
                <form class="d-flex" role="search" method="get">
                    <input class="form-control me-2" type="search" name="q" placeholder="Cari produk" aria-label="Search" value="<?= esc($_GET['q'] ?? '') ?>" />
                    <button class="btn btn-primary" type="submit">Cari</button>
                </form>
            </div>
        </div>
        <div class="row">
            <?php
            $search = $_GET['q'] ?? '';
            $filteredProducts = $products;
            if ($search) {
                $filteredProducts = array_filter($products, function ($product) use ($search) {
                    return stripos($product['name'], $search) !== false;
                });
            }
            ?>
            <?php if (!$filteredProducts) : ?>
                <div class="row justify-content-center align-items-center">
                    <p class="lead text-center">Produk tidak ditemukan.</p>
                </div>
            <?php else : ?>
                <?php foreach ($filteredProducts as $product) : ?>
                    <div class="col-12 col-md-4 col-lg-3 mb-5">
                        <?= form_open(base_url(route_to('landing.cart.add'))) ?>
                        <?= csrf_field() ?>
                        <div class="product-item">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <a href="<?= base_url(route_to('landing.shop.show', $product['slug'])) ?>">
                                <img src="<?= base_url('img/uploads/main/') . $product['image'] ?>" class="img-fluid product-thumbnail">
                            </a>
                            <a href="<?= base_url(route_to('landing.shop.show', $product['slug'])) ?>">
                                <h3 class="product-title"><?= $product['name'] ?></h3>
                            </a>
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <strong class="product-price">Rp<?= number_format($product['price'], '0', ',', '.') ?></strong>
                                <strong>|</strong>
                                <div class="d-flex gap-1 align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="currentColor" viewBox="0 0 24 24">
                                        <!--Boxicons v3.0 https://boxicons.com | License  https://docs.boxicons.com/free-->
                                        <path d="m6.87 14.33-1.83 6.4c-.12.4.03.84.37 1.08.34.25.8.26 1.14.02L12 18.2l5.45 3.63a.988.988 0 0 0 1.14-.02c.34-.25.49-.68.37-1.08l-1.83-6.4 4.54-4.08c.3-.27.41-.69.28-1.06-.13-.38-.47-.64-.87-.68l-5.7-.45-2.47-5.46a.998.998 0 0 0-1.82 0L8.62 8.06l-5.7.45c-.4.03-.74.3-.87.68s-.02.8.28 1.06z"></path>
                                    </svg>
                                    <span>5</span>
                                </div>
                            </div>

                            <?php if (logged_in() && in_groups('user')) : ?>
                                <button type="submit" class="icon-cross border-0">
                                    <img src="<?= base_url() ?>img/cross.svg" class="img-fluid">
                                </button>
                            <?php endif; ?>
                        </div>
                        <?= form_close() ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- End Shop Section -->

<div class="my-4"></div>

<?= $this->endSection(); ?>