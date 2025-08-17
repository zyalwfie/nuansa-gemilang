<?= $this->extend('landing/layouts/app'); ?>

<?= $this->section('page_title'); ?>
<?= $page_title ?>
<?= $this->endSection(); ?>

<?= $this->section('head_css'); ?>
<style>
    .address .my-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: .5rem;
    }

    .address .my-grid.empty {
        display: grid;
        grid-template-columns: 1fr;
    }

    .address .address-container {
        transition: .1s linear;
        position: relative;
        border: 1px solid #ced4da;
        border-radius: 10px;
    }

    .address .address-container:has(input[type=radio]:checked) {
        box-shadow: 1px 1px 2px #ced4da;
    }

    .address .address-container:hover {
        box-shadow: 1px 1px 2px #ced4da;
    }

    .address input[type=radio] {
        display: none;
    }

    .address input[type=radio]:checked+.label {
        background-color: #3b5d50;
        color: #ced4da;
        animation: bounceColor .4s ease;
        box-shadow: 0 0 8px rgba(59, 93, 80, 0.6);
    }

    .address input[type=radio]:checked~.address-text {
        text-shadow: 0 0 8px rgba(59, 93, 80, 0.6);
    }

    @keyframes bounceColor {
        0% {
            transform: scale(0.9) translateY(2px);
            background-color: #2a423a;
        }

        50% {
            transform: scale(1.1) translateY(-4px);
            background-color: #3b5d50;
        }

        70% {
            transform: scale(0.95) translateY(1px);
        }

        100% {
            transform: scale(1) translateY(0);
        }
    }

    .address .label {
        padding-inline: 1rem;
        border-radius: 9999rem;
        border: 1px solid #3b5d50;
        transition: .1s linear;
        cursor: pointer;
        font-weight: 500;
        display: inline-block;
        user-select: none;
    }

    .address .address-text {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .address .label:hover {
        background-color: #3b5d50;
        color: #ced4da;
    }

    .address .label.active {
        color: #ced4da;
        background-color: #3b5d50;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<!-- Start Hero Section -->
<div class="hero">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="intro-excerpt">
                    <h1>Keranjang Anda</h1>
                    <p>Kelola produk yang ingin kamu beli sebelum checkout di sini.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->

<div class="untree_co-section before-footer-section">
    <div class="container">

        <div class="row mb-5">
            <div class="site-blocks-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="product-thumbnail">Gambar</th>
                            <th class="product-name">Nama</th>
                            <th class="product-price">Harga</th>
                            <th class="product-quantity">Kuantitas</th>
                            <th class="product-total">Total</th>
                            <th class="product-remove">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($carts as $cart) : ?>
                            <tr>
                                <td class="product-thumbnail">
                                    <img
                                        src="<?= base_url('img/uploads/main/' . $cart->image) ?>"
                                        alt="<?= $cart->name ?>"
                                        class="img-fluid" />
                                </td>
                                <td class="product-name">
                                    <h2 class="h5 text-black">
                                        <?= $cart->name ?>
                                    </h2>
                                </td>
                                <td>Rp<?= number_format($cart->price, '0', '.', ',') ?></td>
                                <td>
                                    <div class="input-group mb-3 d-flex align-items-center quantity-container" style="max-width: 120px">
                                        <div class="input-group-prepend">
                                            <?= form_open(base_url(route_to('landing.cart.decrease', $cart->cart_id))) ?>
                                            <?= csrf_field() ?>
                                            <button class="btn btn-outline-black decrease" type="submit" <?= $cart->quantity <= 1 ? 'disabled' : '' ?>>
                                                <i class="fas fa-fw fa-minus"></i>
                                            </button>
                                            <?= form_close(); ?>
                                        </div>
                                        <input type="text" class="form-control text-center quantity-amount" value="<?= $cart->quantity ?>" aria-label="Show quantity amount" aria-describedby="button" readonly />
                                        <div class="input-group-append">
                                            <?= form_open(base_url(route_to('landing.cart.increase', $cart->cart_id))) ?>
                                            <?= csrf_field() ?>
                                            <button
                                                class="btn btn-outline-black increase"
                                                type="submit"
                                                <?= $cart->stock < 1 ? 'disabled' : '' ?>>
                                                <i class="fas fa-fw fa-plus"></i>
                                            </button>
                                            <?= form_close() ?>
                                        </div>
                                    </div>
                                </td>
                                <td>Rp<?= number_format($cart->price_at_add, '0', '.', ',') ?></td>
                                <td>
                                    <?= form_open(base_url(route_to('landing.cart.destroy', $cart->cart_id))) ?>
                                    <?= csrf_field() ?>
                                    <button
                                        type="submit"
                                        class="btn btn-black btn-sm">
                                        <i class="fas fa-fw fa-times"></i>
                                    </button>
                                    <?= form_close(); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?= form_open(base_url(route_to('landing.cart.payment.create')), ['class' => 'row']) ?>
        <div class="col-12 col-lg-6 mb-5 mb-md-0">
            <h2 class="h3 mb-3 text-black">Rincian Pengiriman</h2>
            <div class="p-3 p-lg-5 border bg-white">

                <div class="mb-3 address">
                    <div class="text-black">Pilih alamat <sup class="text-danger">*</sup></div>
                    <div class="my-grid <?= empty($addresses) ? 'empty' : '' ?>">
                        <?php if (!empty($addresses)) : ?>
                            <?php foreach ($addresses as $address) : ?>
                                <div class="address-container p-3 d-flex flex-column align-items-start gap-1">
                                    <input type="radio" name="address_id" id="<?= $address['id'] ?>" value="<?= $address['id'] ?>" class="<?= session('errors.address_id') ? 'is-invalid' : '' ?>">
                                    <label for="<?= $address['id'] ?>" class="label"><?= $address['label'] ?></label>
                                    <p class="address-text m-0"><?= $address['street_address'] ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="alert alert-warning d-flex align-items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    fill="currentColor" viewBox="0 0 24 24">
                                    <!--Boxicons v3.0 https://boxicons.com | License  https://docs.boxicons.com/free-->
                                    <path d="M11 7h2v6h-2zM11 15h2v2h-2z"></path>
                                    <path d="M16.71 2.29A1 1 0 0 0 16 2H8c-.27 0-.52.11-.71.29l-5 5A1 1 0 0 0 2 8v8c0 .27.11.52.29.71l5 5c.19.19.44.29.71.29h8c.27 0 .52-.11.71-.29l5-5A1 1 0 0 0 22 16V8c0-.27-.11-.52-.29-.71zM20 15.58l-4.41 4.41H8.42l-4.41-4.41V8.41L8.42 4h7.17L20 8.41z"></path>
                                </svg>
                                <span>
                                    Belum ada alamat yang kamu buat! Buat alamat <a href="<?= route_to('user.address.index') ?>">di sini.</a>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if (session('errors.address_id')) : ?>
                        <div class="text-danger">
                            <?= session('errors.address_id') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="notes" class="text-black">Catatan <small>(Opsional)</small></label>
                    <textarea name="notes" id="notes" cols="30" rows="5" name="notes" class="form-control" placeholder="Tulis catatanmu di sini jika ada..."><?= old('notes') ?></textarea>
                </div>

            </div>
        </div>

        <input type="hidden" name="total_price" value="<?= $cartsTotal ?>">

        <div class="col-12 col-lg-6">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="h3 mb-3 text-black">Pesananmu</h2>
                    <div class="p-3 p-lg-5 border bg-white mb-3">
                        <table class="table site-block-order-table mb-5">
                            <thead>
                                <th>Produk</th>
                                <th>Total</th>
                            </thead>
                            <tbody>
                                <?php foreach ($carts as $cart) : ?>
                                    <tr>
                                        <input type="hidden" name="product_id[]" value="<?= $cart->product_id ?>">
                                        <input type="hidden" name="quantity[]" value="<?= $cart->quantity ?>">
                                        <td><?= $cart->name ?> <strong class="mx-2">x</strong> <?= $cart->quantity ?></td>
                                        <td>Rp<?= number_format($cart->price, '0', '.', ',') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td class="text-black font-weight-bold"><strong>Total Pesanan</strong></td>
                                    <td class="text-black font-weight-bold"><strong>Rp<?= number_format($cartsTotal, '0', '.', ',') ?></strong></td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="border p-3 mb-4">
                            <h3 class="h6 mb-0"><a class="d-block" data-bs-toggle="collapse" href="#collapsebank" role="button" aria-expanded="false" aria-controls="collapsebank">Transfer ke nomor rekening di bawah</a></h3>

                            <div class="collapse" id="collapsebank">
                                <div class="pt-4 pb-2">
                                    <div class="d-flex gap-4 align-items-center mb-3">
                                        <img src="<?= base_url('img/bca.svg') ?>" alt="Bank Central Asia" width="100">
                                        <div class="d-flex gap-3 align-items-center">
                                            <div class="d-flex flex-column">
                                                <p class="fs-5 fw-bold m-0" id="bcaBankAccount">2320725421</p>
                                                <p class="mb-0">atas nama Harianto</p>
                                            </div>
                                            <button type="button" id="bcaCopyBtn" onclick="copyToClipboard('bcaCopyIcon','bcaBankAccount')">
                                                <i class="far fa-fw fa-copy" id="bcaCopyIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-4 align-items-center">
                                        <img src="<?= base_url('img/mandiri.svg') ?>" alt="Bank Mandiri" width="100">
                                        <div class="d-flex gap-3 align-items-center">
                                            <div class="d-flex flex-column">
                                                <p class="fs-5 fw-bold m-0" id="mandiriBankAccount">1610003613267</p>
                                                <p class="mb-0">atas nama Harianto</p>
                                            </div>
                                            <button type="button" id="mandiriCopyBtn" onclick="copyToClipboard('mandiriCopyIcon', 'mandiriBankAccount')">
                                                <i class="far fa-fw fa-copy" id="mandiriCopyIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-black btn-lg py-3 btn-block w-100" type="submit">Lanjutkan Pembayaran</button>
                    </div>

                </div>
            </div>
        </div>
        <?= form_close() ?>
    </div>
</div>

</div>
<?= $this->endSection(); ?>

<?= $this->section('head_css'); ?>
<style>
    #bcaCopyBtn,
    #mandiriCopyBtn {
        border: 1px solid #000;
        background-color: transparent;
        color: #000;
        border-radius: 9999rem;
        transition: .1s linear;
    }

    #bcaCopyBtn:hover,
    #mandiriCopyBtn:hover {
        background-color: #000;
        color: #fff;
    }

    #bcaCopiedText,
    #mandiriCopiedText {
        position: absolute;
        width: max-content;
        right: -8px;
        transform: translateX(100%) translateY(1rem);
        opacity: 0;
        transition: .1s ease-in-out;
    }

    .translate-y-0 {
        transform: translateX(100%) translateY(0) !important;
    }

    .opacity-1 {
        opacity: 1 !important;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('footer_js'); ?>
<script>
    function copyToClipboard(icon, account) {
        const CopyIcon = document.getElementById(icon);
        const bankAccount = document.getElementById(account);

        const textArea = document.createElement("textarea");
        textArea.value = bankAccount.textContent;
        document.body.appendChild(textArea);
        textArea.select();

        try {
            document.execCommand('copy');
            CopyIcon.classList.remove('far');
            CopyIcon.classList.remove('fa-copy');
            CopyIcon.classList.add('fas');
            CopyIcon.classList.add('fa-check');

            setTimeout(() => {
                CopyIcon.classList.remove('fas');
                CopyIcon.classList.remove('fa-check');
                CopyIcon.classList.add('far');
                CopyIcon.classList.add('fa-copy');
            }, 1500);
        } catch (error) {
            console.error('Fallback failed:', error);
        } finally {
            document.body.removeChild(textArea);
        }
    }
</script>
<?= $this->endSection(); ?>