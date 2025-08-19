<?= $this->extend('dashboard/layouts/app'); ?>

<?= $this->section('page_title'); ?>
<?= $page_title ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>


<!-- Page Heading -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url(route_to('admin.orders.index')) ?>">Pesanan</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url(route_to('admin.orders.show', $order['id'])) ?>" class="text-secondary">Detail</a></li>
    </ol>
</nav>
<h1 class="h3 mb-2 text-gray-800">Detail Pesanan</h1>
<p class="mb-4">Dapatkan informasi lengkap mengenai pesanan dan kelola pesanan atas nama <span class="fw-semibold text-capitalize"><?= $order['full_name'] ?></span></p>

<div class="row">
    <div class="col">
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
                                    <td><?= $orderItem->name ?> <strong class="mx-2">x</strong> <?= $orderItem->quantity ?></td>
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
                            <div class="px-1 py-2 alert alert-primary alert-dismissible fade show d-flex align-items-center justify-content-between" role="alert">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="me-2" fill="currentColor">
                                        <path d="m10 15.586-3.293-3.293-1.414 1.414L10 18.414l9.707-9.707-1.414-1.414z"></path>
                                    </svg>
                                    <span><?= session('proofed') ?></span>
                                </div>
                                <button type="button" class="btn p-0" data-bs-dismiss="alert" aria-label="Close">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path>
                                    </svg>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="p-3 p-lg-5 border bg-white">
                    <div class="row">
                        <?php if ($proof_of_payment->proof_of_payment) : ?>
                            <div class="col">
                                <img id="paymentProofImg" src="<?= base_url('img/uploads/proof/') . $proof_of_payment->proof_of_payment ?>" alt="Bukti Pembayaran" style="width: 100%; height: auto; object-fit: cover; cursor: pointer;">
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <p>Gambar di samping adalah bukti pembayaran yang telah diunggah</p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-primary" type="button" id="detailBtn">Lihat</button>
                                        <?php if ($order['status'] === 'tertunda') : ?>
                                            <button class="btn btn-outline-info" type="button" data-toggle="modal" data-target="#confirmModal">Konfirmasi Pesanan</button>
                                        <?php else : ?>
                                            <button class="btn btn-secondary" type="button" disabled>Telah Disetujui</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else : ?>
                                <div class="alert alert-warning d-flex align-items-center m-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="me-2">
                                        <path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path>
                                    </svg>
                                    <span>Belum ada bukti pembayaran!</span>
                                </div>
                            <?php endif; ?>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Modal-->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="modalConfirmOrder"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalConfirmOrder">Apakah pesanan layak disetujui?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Pilih "Setujui" di bawah ini jika pesanan sudah dibayar.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batalkan</button>
                    <a class="btn btn-primary" href="<?= base_url(route_to('admin.orders.update', $order['id'])) ?>">Setujui</a>
                </div>
            </div>
        </div>
    </div>
    <?= $this->endSection(); ?>

    <?= $this->section('footer_js'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const img = document.getElementById('paymentProofImg');
            const detailBtn = document.getElementById('detailBtn');
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
            }
        });

        function previewProof(event) {
            const file = event.target.files[0];
            const previewContainer = document.getElementById('previewContainer');
            previewContainer.innerHTML = '';
            if (!file) return;
            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.style.maxWidth = '100%';
                img.style.maxHeight = '300px';
                img.className = 'img-fluid border rounded';
                previewContainer.appendChild(img);
            } else if (file.type === 'application/pdf') {
                const pdfIcon = document.createElement('div');
                pdfIcon.innerHTML = '<span class="text-secondary">Preview tidak tersedia untuk PDF. File siap diunggah.</span>';
                previewContainer.appendChild(pdfIcon);
            } else {
                previewContainer.innerHTML = '<span class="text-danger">File tidak didukung.</span>';
            }
        }
    </script>
    <?= $this->endSection(); ?>