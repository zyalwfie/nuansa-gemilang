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
                    <h1><?= $product['name'] ?></h1>
                    <p class="mb-4">
                        Di bawah ini deskripsi singkat dari produk yang kamu cari. semoga dapat membantumu memahami produk kami.
                    </p>
                    <p>
                        <a href="<?= base_url(route_to('landing.shop')) ?>" class="btn btn-white-outline">Kembali</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->

<!-- Product section-->
<section class="py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="row gx-4 gx-lg-5 align-items-center">
            <div class="col-md-6">
                <img class="card-img-top mb-5 mb-md-0" src="<?= base_url('img/uploads/main/' . $product['image']) ?>" alt="<?= $product['name'] ?>" />
            </div>
            <div class="col-md-6">
                <div class="small mb-1">Detail Produk</div>
                <h1 class="display-5 fw-bolder"><?= $product['name'] ?></h1>
                <span class="d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="gold" class="me-1">
                        <path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path>
                    </svg>
                    <?= $product['avg_rating'] ?>
                </span>
                <div class="fs-5 mb-3 d-flex align-items-center gap-1">
                    <span>Rp<?= number_format($product['price'], '0', ',', '.') ?></span>
                    <span>/</span>
                    <small class="text-muted">Sisa stok <?= $product['stock'] ?></small>
                </div>
                <div class="mb-4">
                    <?= $product['description'] ?>
                </div>
                <?php if (in_groups('admin')) : ?>

                <?php else : ?>
                    <?php if (session('not_in_stock')) : ?>
                        <div class="alert alert-danger alert-dismissible fade show d-flex gap-2 align-items-center" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-diamond" viewBox="0 0 16 16">
                                <path d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.48 1.48 0 0 1 0-2.098zm1.4.7a.495.495 0 0 0-.7 0L1.134 7.65a.495.495 0 0 0 0 .7l6.516 6.516a.495.495 0 0 0 .7 0l6.516-6.516a.495.495 0 0 0 0-.7L8.35 1.134z" />
                                <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                            </svg>
                            <?= session('not_in_stock') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php else : ?>

                    <?php endif; ?>
                    <?= form_open(route_to('landing.cart.add'), ['class' => 'd-flex align-items-center']) ?>
                    <?= csrf_field() ?>
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <div class="form-group">
                        <input class="form-control text-center me-3 <?= session('not_in_stock') ? 'is-invalid' : '' ?>" type="number" value="<?= old('quantity', 1) ?>" name="quantity" style="max-width: 5rem" />
                    </div>
                    <button type="submit" class="btn btn-outline-dark flex-shrink-0 d-flex gap-2 align-items-center">
                        <i class="fas fa-fw fa-cart-plus"></i>
                        Tambah
                    </button>
                    <?= form_close() ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<div class="my-4"></div>

<?= $this->endSection(); ?>