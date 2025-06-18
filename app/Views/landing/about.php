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
                    <h1>Tentang Kami</h1>
                    <p class="mb-4">
                        Di Nuansa, kami percaya bahwa kenyamanan dan desain tidak perlu dipisahkan. Kami menciptakan bean bag yang akan membawa nuansa baru di rumahmu.
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

<?= $this->endSection(); ?>