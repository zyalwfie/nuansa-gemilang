<?= $this->extend('dashboard/layouts/app'); ?>

<?= $this->section('page_title'); ?>
<?= $page_title ?>
<?= $this->endSection(); ?>

<?= $this->section('head_css'); ?>
<style>
    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
    }

    .rating input {
        display: none;
    }

    .rating label {
        font-size: 2rem;
        cursor: pointer;
        transition: transform .2s;
    }

    .rating input:checked~label {
        color: gold;
    }

    .rating input:checked~label svg {
        fill: gold;
    }

    .rating label:hover,
    .rating label:hover~label {
        transform: scale(1.2);
        color: gold;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<!-- Page Heading -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url(route_to('user.history.index')) ?>">Riwayat pesanan</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url(route_to('user.history.show', $order['id'])) ?>" class="text-secondary">Detail</a></li>
    </ol>
</nav>
<h1 class="h3 mb-2 text-gray-800">Detail Pesanan</h1>
<p class="mb-4">Kamu bisa cek kembali detail lengkap pesanan atas nama <span class="fw-semibold text-capitalize"><?= $order['full_name'] ?></span></p>

<div class="row">
    <div class="col mb-3">
        <h2 class="h3 mb-3 text-black">Rincian Pengiriman</h2>
        <div class="p-3 p-lg-5 border bg-white">

            <div class="form-group mb-3 row">
                <div class="col">
                    <label for="full_name" class="text-black">Nama Penerima</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?= $order['full_name'] ?? $order['username'] ?>" disabled>
                </div>
                <div class="col">
                    <label for="email" class="text-black">Email</label>
                    <input type="text" class="form-control" id="email" name="email" value="<?= $order['email'] ?>" disabled>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="street_address" class="text-black">Alamat Penerima</label>
                <input type="text" class="form-control" id="street_address" name="street_address" value="<?= $order['street_address'] ?>" disabled>
            </div>

            <div class="form-group mb-3">
                <label for="phone_number" class="text-black">Nomor Telepon</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" aria-describedby="phoneHelp" value="<?= $order['phone_number'] ?>" disabled>
            </div>

            <div class="form-group">
                <label for="notes" class="text-black">Catatan</label>
                <textarea name="notes" id="notes" cols="30" rows="5" name="notes" class="form-control" disabled><?= $order['notes'] ?></textarea>
            </div>

        </div>
    </div>

    <div class="col">
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col">
                        <h2 class="h3 mb-3 text-black">Pesananmu</h2>
                    </div>
                    <div class="col text-end">
                        <span class="badge <?php if ($order['status'] === 'tertunda') : ?>text-bg-warning <?php elseif ($order['status'] === 'berhasil') : ?>text-bg-success <?php else: ?>text-bg-danger<?php endif; ?> text-capitalize">
                            <?= $order['status'] ?>
                        </span>
                    </div>
                </div>
                <div class="p-3 p-lg-5 border bg-white">
                    <p class="lead fs-6">
                        <?php
                        $createdAt = new DateTime($order['created_at']);

                        $timezone = new DateTimeZone('Asia/Jakarta');
                        $createdAt->setTimezone($timezone);

                        $offset = $timezone->getOffset($createdAt);

                        if ($offset == 7 * 3600) {
                            $timezoneLabel = 'WIB';
                        } elseif ($offset == 9 * 3600) {
                            $timezoneLabel = 'WIT';
                        } else {
                            $timezoneLabel = 'WITA';
                        }

                        $formattedDate = $createdAt->format('d F Y, H:i') . ' ' . $timezoneLabel;
                        ?>
                        <?= $formattedDate ?>
                    </p>
                    <table class="table site-block-order-table mb-5">
                        <thead>
                            <th>Produk</th>
                            <th>Total</th>
                        </thead>
                        <tbody>
                            <?php foreach ($order_items as $orderItem) : ?>
                                <tr>
                                    <td class="d-flex align-items-center">
                                        <?= $orderItem->name ?> <strong class="mx-2">x</strong> <?= $orderItem->quantity ?>
                                        <?php if ($order['status'] === 'berhasil' && !$orderItem->is_rated) : ?>
                                            <button
                                                class="rate-button btn p-0 ms-2"
                                                data-bs-toggle="modal"
                                                data-bs-target="#rateModal"
                                                data-product_name="<?= $orderItem->name ?>"
                                                data-order_item_id="<?= $orderItem->orderItemId ?>"
                                                data-product_id="<?= $orderItem->productId ?>">
                                                <span class="badge text-bg-success">Nilai</span>
                                            </button>
                                        <?php elseif ($order['status'] === 'berhasil' && $orderItem->is_rated) : ?>
                                            <button class="btn p-0 border-0" disabled>
                                                <span class="badge text-bg-secondary ms-2">Ternilai</span>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                    <td>Rp<?= number_format($orderItem->price, '0', '.', ',') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="text-black font-weight-bold"><strong>Total Pesanan</strong></td>
                                <td class="text-black font-weight-bold"><strong>Rp<?= number_format($order['total_price'], '0', '.', ',') ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row align-items-center justify-content-between">
                    <div class="col">
                        <h2 class="h3 mb-3 text-black">Bukti Pembayaran</h2>
                    </div>
                    <div class="col">
                        <?php if (session()->has('proofed')) : ?>
                            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                                <?= session('proofed') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="p-3 p-lg-5 border bg-white">
                    <?php if ($proof_of_payment->proof_of_payment && $order['status'] === 'berhasil') : ?>
                        <div class="d-flex align-items-stretch">
                            <div class="mb-3 w-50 me-4" style="height: 250px; width: 200px; border-radius: .25rem; background-color: #e3e6f0; overflow: hidden;">
                                <img id="paymentProofImg" src="<?= base_url('img/uploads/proof/') . $proof_of_payment->proof_of_payment ?>" alt="Bukti Pembayaran" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                            </div>
                            <div class="mb-3">
                                <div class="mb-4">
                                    <p class="mb-2">Gambar di samping adalah bukti pembayaran yang telah diunggah</p>
                                    <button class="btn btn-secondary" type="button" disabled>Pesanan telah disetujui</button>
                                </div>
                            </div>
                        </div>
                    <?php elseif ($proof_of_payment->proof_of_payment) : ?>
                        <div class="d-flex align-items-stretch">
                            <div class="mb-3 w-50 me-4" style="height: 250px; width: 200px; border-radius: .25rem; background-color: #e3e6f0; overflow: hidden;">
                                <img id="paymentProofImg" src="<?= base_url('img/uploads/proof/') . $proof_of_payment->proof_of_payment ?>" alt="Bukti Pembayaran" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                            </div>
                            <div class="mb-3">
                                <div class="mb-4">
                                    <p class="mb-2">Gambar di samping adalah bukti pembayaran yang telah diunggah</p>
                                    <button class="btn btn-info" type="button" id="detailBtn">Lihat detail</button>
                                </div>
                                <?= form_open_multipart(route_to('landing.cart.payment.update'), ['class' => 'd-flex flex-column align-items-stretch']) ?>
                                <?= csrf_field() ?>
                                <div class="mb-2">
                                    <label for="proof_of_payment" class="form-label">File Bukti Pembayaran <span class="text-danger">*</span></label>
                                    <input class="form-control <?= session('errors.proof_of_payment') ? 'is-invalid' : '' ?>" type="file" id="proof_of_payment" name="proof_of_payment" accept="image/*,application/pdf" onchange="previewProof(event)">
                                    <?php if (session('errors.proof_of_payment')) : ?>
                                        <div class="invalid-feedback">
                                            <?= session('errors.proof_of_payment') ?>
                                        </div>
                                    <?php endif; ?>
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                </div>
                                <button type="submit" class="btn btn-primary">Perbarui Bukti</button>
                                <?= form_close() ?>
                            </div>
                        </div>
                    <?php else : ?>
                        <?= (!$proof_of_payment->proof_of_payment) ? form_open_multipart(base_url(route_to('landing.cart.payment.upload')), ['class' => 'd-flex align-items-stretch']) : form_open_multipart(base_url(route_to('landing.cart.payment.update')), ['class' => 'd-flex align-items-stretch']) ?>
                        <?= csrf_field() ?>
                        <div class="me-3 mb-3 mb-lg-0 position-relative" style="height: 250px; width: 200px; border-radius: .25rem; background-color: #e3e6f0; overflow: hidden;">
                            <img id="paymentProofImg" src="" style="width: 100%; height: 100%; object-fit: cover; position: absolute; transform: translate(-50%, -50%); top: 50%; left: 50%;" alt="Bukti Pembayaran">
                        </div>
                        <div class="flex flex-column">
                            <div class="alert alert-warning d-flex align-items-center flex-grow-1 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    fill="currentColor" viewBox="0 0 24 24" class="me-2">
                                    <!--Boxicons v3.0 https://boxicons.com | License  https://docs.boxicons.com/free-->
                                    <path d="M12 22c5.51 0 10-4.49 10-10S17.51 2 12 2 2 6.49 2 12s4.49 10 10 10M11 7h2v6h-2zm0 8h2v2h-2z"></path>
                                </svg>
                                <span>Belum ada bukti pembayaran!</span>
                            </div>
                            <div class="d-flex flex-column">
                                <?php if (!$proof_of_payment->proof_of_payment) : ?>
                                    <input type="hidden" name="uri_string" value="<?= uri_string() ?>">
                                <?php endif; ?>
                                <label for="proof_of_payment" class="form-label">File Bukti Pembayaran <span class="text-danger">*</span></label>
                                <input class="form-control <?= session('errors.proof_of_payment') ? 'is-invalid' : '' ?>" type="file" id="proof_of_payment" name="proof_of_payment" accept="image/*,application/pdf" onchange="previewProof(event)">
                                <?php if (session('errors.proof_of_payment')) : ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.proof_of_payment') ?>
                                    </div>
                                <?php endif; ?>
                                <button type="submit" class="btn btn-primary mt-2">Unggah Bukti</button>
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            </div>
                        </div>
                        <?= form_close() ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rate Modal -->
<div class="modal fade" id="rateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="rateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="rateModalLabel">Beri Rating</h1>
            </div>
            <form id="rateForm">
                <input type="hidden" name="order_item_id" id="rate_order_item_id">
                <input type="hidden" name="product_id" id="product_id">
                <div class="modal-body text-center">
                    <!-- Rating stars -->
                    <div class="rating">
                        <input type="radio" id="star5" name="rating" value="5">
                        <label for="star5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="m6.516 14.323-1.49 6.452a.998.998 0 0 0 1.529 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082a1 1 0 0 0-.59-1.74l-5.701-.454-2.467-5.461a.998.998 0 0 0-1.822 0L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.214 4.107zm2.853-4.326a.998.998 0 0 0 .832-.586L12 5.43l1.799 3.981a.998.998 0 0 0 .832.586l3.972.315-3.271 2.944c-.284.256-.397.65-.293 1.018l1.253 4.385-3.736-2.491a.995.995 0 0 0-1.109 0l-3.904 2.603 1.05-4.546a1 1 0 0 0-.276-.94l-3.038-2.962 4.09-.326z"></path>
                            </svg>
                        </label>
                        <input type="radio" id="star4" name="rating" value="4">
                        <label for="star4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="m6.516 14.323-1.49 6.452a.998.998 0 0 0 1.529 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082a1 1 0 0 0-.59-1.74l-5.701-.454-2.467-5.461a.998.998 0 0 0-1.822 0L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.214 4.107zm2.853-4.326a.998.998 0 0 0 .832-.586L12 5.43l1.799 3.981a.998.998 0 0 0 .832.586l3.972.315-3.271 2.944c-.284.256-.397.65-.293 1.018l1.253 4.385-3.736-2.491a.995.995 0 0 0-1.109 0l-3.904 2.603 1.05-4.546a1 1 0 0 0-.276-.94l-3.038-2.962 4.09-.326z"></path>
                            </svg>
                        </label>
                        <input type="radio" id="star3" name="rating" value="3">
                        <label for="star3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="m6.516 14.323-1.49 6.452a.998.998 0 0 0 1.529 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082a1 1 0 0 0-.59-1.74l-5.701-.454-2.467-5.461a.998.998 0 0 0-1.822 0L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.214 4.107zm2.853-4.326a.998.998 0 0 0 .832-.586L12 5.43l1.799 3.981a.998.998 0 0 0 .832.586l3.972.315-3.271 2.944c-.284.256-.397.65-.293 1.018l1.253 4.385-3.736-2.491a.995.995 0 0 0-1.109 0l-3.904 2.603 1.05-4.546a1 1 0 0 0-.276-.94l-3.038-2.962 4.09-.326z"></path>
                            </svg>
                        </label>
                        <input type="radio" id="star2" name="rating" value="2">
                        <label for="star2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="m6.516 14.323-1.49 6.452a.998.998 0 0 0 1.529 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082a1 1 0 0 0-.59-1.74l-5.701-.454-2.467-5.461a.998.998 0 0 0-1.822 0L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.214 4.107zm2.853-4.326a.998.998 0 0 0 .832-.586L12 5.43l1.799 3.981a.998.998 0 0 0 .832.586l3.972.315-3.271 2.944c-.284.256-.397.65-.293 1.018l1.253 4.385-3.736-2.491a.995.995 0 0 0-1.109 0l-3.904 2.603 1.05-4.546a1 1 0 0 0-.276-.94l-3.038-2.962 4.09-.326z"></path>
                            </svg>
                        </label>
                        <input type="radio" id="star1" name="rating" value="1">
                        <label for="star1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="m6.516 14.323-1.49 6.452a.998.998 0 0 0 1.529 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082a1 1 0 0 0-.59-1.74l-5.701-.454-2.467-5.461a.998.998 0 0 0-1.822 0L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.214 4.107zm2.853-4.326a.998.998 0 0 0 .832-.586L12 5.43l1.799 3.981a.998.998 0 0 0 .832.586l3.972.315-3.271 2.944c-.284.256-.397.65-.293 1.018l1.253 4.385-3.736-2.491a.995.995 0 0 0-1.109 0l-3.904 2.603 1.05-4.546a1 1 0 0 0-.276-.94l-3.038-2.962 4.09-.326z"></path>
                            </svg>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2" id="rateSubmitBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text">Beri nilai</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('footer_js'); ?>
<script>
    const img = document.getElementById('paymentProofImg');
    const detailBtn = document.getElementById('detailBtn');
    const rateForm = document.getElementById('rateForm');
    const rateSubmitBtn = document.getElementById('rateSubmitBtn');
    const spinner = rateSubmitBtn.querySelector('.spinner-border');
    const btnText = rateSubmitBtn.querySelector('.btn-text');

    document.querySelectorAll('.rate-button').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('rateModalLabel').textContent = "Beri nilai untuk " + this.dataset.product_name;
            document.getElementById('rate_order_item_id').value = this.dataset.order_item_id;
            document.getElementById('product_id').value = this.dataset.product_id;
        });
    });

    rateForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        spinner.classList.remove('d-none');
        btnText.textContent = 'Loading...';
        rateSubmitBtn.disabled = true;

        const formData = new FormData(rateForm);

        try {
            const response = await fetch('<?= base_url('dashboard/user/rate/update') ?>', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.success) {
                alert(result.message);
                location.reload();
            } else if (result.errors) {
                alert("Error: " + JSON.stringify(result.errors));
            }
        } catch (err) {
            console.error(err);
        } finally {
            spinner.classList.add('d-none');
            btnText.textContent = 'Beri nilai';
            rateSubmitBtn.disabled = false;
        }
    });

    function previewProof(event) {
        const file = event.target.files[0];
        const fileInput = document.getElementById('proof_of_payment');
        if (!file) return;
        if (file.type.startsWith('image/')) {
            img.src = URL.createObjectURL(file);
            if (fileInput.classList.contains('invalid')) {
                fileInput.classList.remove('invalid')
            }
        } else {
            fileInput.classList.add('invalid');
        }
    }

    let viewer;
    if (img && detailBtn) {
        viewer = new Viewer(img, {
            toolbar: true,
            navbar: false,
            title: false,
            movable: true,
            zoomable: true,
            scalable: true,
            transition: true,
            fullscreen: true,
        });
        detailBtn.addEventListener('click', function() {
            viewer.show();
        });
        img.addEventListener('click', function() {
            viewer.show();
        })
    }
</script>
<?= $this->endSection(); ?>