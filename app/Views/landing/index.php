<?= $this->extend('landing/layouts/app'); ?>

<?= $this->section('page_title'); ?>
<?= $page_title ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<!-- Start Hero Section -->
<div class="hero">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="intro-excerpt">
                    <h1>Nuansa Baru untuk <span clsas="d-block">Rumahmu</span></h1>
                    <p class="mb-4">Hadirkan sentuhan elegan dan kenyamanan maksimal dengan bean bag bergaya modern yang menyatu sempurna dengan interior vibe kamu.</p>
                    <p><a href="<?= base_url(route_to('landing.shop')) ?>" class="btn btn-secondary me-2">Belanja</a><a href="<?= in_groups('admin') ? base_url(route_to('admin.index')) : base_url(route_to('user.index')) ?>" class="btn btn-white-outline">Jelajahi</a></p>
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

<!-- Start Product Section -->
<div class="product-section">
    <div class="container">
        <div class="row">

            <!-- Start Column 1 -->
            <div class="col-md-12 col-lg-3 mb-5 mb-lg-0">
                <h2 class="mb-4 section-title">Rekomendasi Bean Bag dari Kami dan Pastinya Best Seller.</h2>
                <p class="mb-4">Temukan bean bag terbaik kami yang nyaman, tahan lama, dan ramah lingkungan. Pilihan terbaik untuk kenyamanan dan gaya hidup Anda.</p>
                <p><a href="<?= base_url(route_to('landing.shop')) ?>" class="btn">Periksa lebih banyak</a></p>
            </div>
            <!-- End Column 1 -->

            <!-- Start Column 2 -->
            <?php foreach ($featured_products as $product) : ?>
                <?= form_open(base_url(route_to('landing.cart.add')), ['class' => 'col-12 col-md-4 col-lg-3 mb-5 mb-md-0']) ?>
                <?= csrf_field() ?>
                <div class="product-item">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <img src="<?= base_url() ?>img/uploads/main/<?= $product['image'] ?>" class="img-fluid product-thumbnail">
                    <h3 class="product-title"><?= $product['name'] ?></h3>
                    <strong class="product-price">Rp<?= number_format($product['price'], 0, '.', ',') ?></strong>

                    <?php if (logged_in()) : ?>
                        <?php if (in_groups('admin')) : ?>

                        <?php else : ?>
                            <button type="submit" class="icon-cross border-0">
                                <img src="<?= base_url() ?>img/cross.svg" class="img-fluid">
                            </button>
                        <?php endif; ?>

                    <?php endif; ?>
                </div>
                <?= form_close() ?>
            <?php endforeach; ?>
            <!-- End Column 2 -->

        </div>
    </div>
</div>
<!-- End Product Section -->

<!-- Start Why Choose Us Section -->
<div class="why-choose-section">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-6">
                <h2 class="section-title">Kenapa Pilih Kami?</h2>
                <p>Produk kami dirancang dengan detail dan dibuat dari material berkualitas tinggi yang nyaman dan stylish. Sehingga memberimu pengalaman bersantai yang maksimal.</p>

                <div class="row my-5">
                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="<?= base_url() ?>/img/truck.svg" alt="Image" class="imf-fluid">
                            </div>
                            <h3>Pengiriman Cepat</h3>
                            <p>Kami kirim langsung ke depan pintu rumahmu dengan cepat dan aman.</p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="<?= base_url() ?>/img/bag.svg" alt="Image" class="imf-fluid">
                            </div>
                            <h3>Belanja dengan Gampang</h3>
                            <p>Tinggal klik, bayar, dan duduk manis. Kami bikin pengalaman belanja simpel dan mudah.</p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="<?= base_url() ?>/img/support.svg" alt="Image" class="imf-fluid">
                            </div>
                            <h3>Layanan 24/7</h3>
                            <p>Ada pertanyaan atau request khusus? Tim kami siap bantu kapan pun kamu butuh/</p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="<?= base_url() ?>/img/return.svg" alt="Image" class="imf-fluid">
                            </div>
                            <h3>Garansi Tukar Mudah</h3>
                            <p>Bean bag nggak cocok? Tenang, proses retur super gampang tanpa drama.</p>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-5">
                <div class="img-wrap">
                    <img src="<?= base_url() ?>img/why-choose-us-img.jpg" alt="Image" class="img-fluid">
                </div>
            </div>

        </div>
    </div>
</div>
<!-- End Why Choose Us Section -->

<!-- Start Popular Product -->
<div class="popular-product">
    <div class="container">
        <div class="row">
            <?php foreach ($featured_products as $product) : ?>
                <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
                    <div class="product-item-sm d-flex">
                        <div class="thumbnail">
                            <img src="<?= base_url() ?>/img/uploads/main/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="img-fluid">
                        </div>
                        <div class="pt-3">
                            <h3><?= $product['name'] ?></h3>
                            <p style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;"><?= $product['description'] ?></p>
                            <p><a href="<?= base_url(route_to('landing.shop')) ?>">Lebih Banyak</a></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<!-- End Popular Product -->
<?= $this->endSection(); ?>