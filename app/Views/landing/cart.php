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
                            <th class="product-quantity">
                                Kuantitas
                            </th>
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

                <div class="form-group mb-3 row">
                    <div class="col">
                        <label for="recipient_name" class="text-black">Nama Penerima <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= session('errors.recipient_name') ? 'is-invalid' : '' ?>" id="recipient_name" name="recipient_name" placeholder="Tulis namamu di sini" value="<?= old('recipient_name') ?>">
                        <?php if (session('errors.recipient_name')) : ?>
                            <div class="invalid-feedback">
                                <?= session('errors.recipient_name') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col">
                        <label for="recipient_email" class="text-black">Email</label>
                        <input type="text" class="form-control <?= session('errors.recipient_email') ? 'is-invalid' : '' ?>" id="recipient_email" name="recipient_email" placeholder="Tulis emailmu di sini" value="<?= old('recipient_email') ?>">
                        <?php if (session('errors.recipient_email')) : ?>
                            <div class="invalid-feedback">
                                <?= session('errors.recipient_email') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="street_address" class="text-black">Alamat Penerima <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?= session('errors.street_address') ? 'is-invalid' : '' ?>" id="street_address" name="street_address" placeholder="Tulis alamatmu di sini" value="<?= old('street_address') ?>">
                    <?php if (session('errors.street_address')) : ?>
                        <div class="invalid-feedback">
                            <?= session('errors.street_address') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group mb-3">
                    <label for="recipient_phone" class="text-black">No. Telepon <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?= session('errors.recipient_phone') ? 'is-invalid' : '' ?>" id="recipient_phone" name="recipient_phone" placeholder="Tulis nomor teleponmu di sini" aria-describedby="phoneHelp" value="<?= old('recipient_phone') ?>">
                    <?php if (session('errors.recipient_phone')) : ?>
                        <div class="invalid-feedback">
                            <?= session('errors.recipient_phone') ?>
                        </div>
                    <?php endif; ?>
                    <div id="phoneHelp" class="form-text">Nomor telepon harus format Indonesia yang valid (contoh: 08123456789, +628123456789, 628123456789).</div>
                </div>

                <div class="form-group">
                    <label for="notes" class="text-black">Catatan</label>
                    <textarea name="notes" id="notes" cols="30" rows="5" name="notes" class="form-control" placeholder="Tulis catatanmu di sini..."><?= old('notes') ?></textarea>
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
    </div>
    <?= form_close() ?>
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