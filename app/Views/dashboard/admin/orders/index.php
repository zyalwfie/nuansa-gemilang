<?= $this->extend('dashboard/layouts/app'); ?>

<?= $this->section('page_title'); ?>
<?= $page_title ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Data Pesanan</h1>
<p class="mb-4">Kelola dan pantau informasi pesanan secara efisien melalui tabel interaktif yang mendukung pencarian dan pengurutan data.</p>

<?php if (session()->has('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show px-3 py-1" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-fw fa-check me-2"></i>
            <div class="flex-grow-1">
                <?= session('success') ?>
            </div>
            <button type="button" class="btn text-secondary" data-bs-dismiss="alert" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: currentColor;">
                    <path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path>
                </svg>
            </button>
        </div>
    </div>
<?php elseif (session()->has('failed')) : ?>
    <div class="alert alert-success alert-dismissible fade show px-3 py-1" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-fw fa-times me-2"></i>
            <div class="flex-grow-1">
                <?= session('failed') ?>
            </div>
            <button type="button" class="btn text-secondary" data-bs-dismiss="alert" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: currentColor;">
                    <path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path>
                </svg>
            </button>
        </div>
    </div>
<?php endif; ?>

<!-- Data Tables Order -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row mb-3 mb-lg-0 align-items-center justify-content-between">
            <div class="col-12 col-lg-4">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Semua Pesanan</h6>
            </div>
            <div class="col-12 col-lg-4">
                <form class="d-flex gap-1" role="search" method="get">
                    <input class="form-control" type="search" name="q" placeholder="Cari pesanan" aria-label="Search" value="<?= esc($_GET['q'] ?? '') ?>" />
                    <button class="btn btn-outline-success" type="submit"><i class="fa fa-faw fa-search"></i></button>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Penerima</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $search = $_GET['q'] ?? '';
                    $filteredOrders = $orders;
                    if ($search) {
                        $filteredOrders = array_filter($orders, function ($order) use ($search) {
                            return stripos($order['recipient_name'], $search) !== false;
                        });
                    }

                    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
                    $perPage = 5;
                    $total = count($filteredOrders);
                    $totalPages = (int) ceil($total / $perPage);
                    $start = ($page - 1) * $perPage;
                    $paginatedOrders = array_slice($filteredOrders, $start, $perPage);
                    $index = $start + 1;
                    ?>

                    <?php if (!$paginatedOrders) : ?>
                        <tr>
                            <td colspan="6" class="text-center">Pesanan tidak ditemukan!</td>
                        </tr>

                    <?php else : ?>
                        <?php foreach ($paginatedOrders as $order) : ?>
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
                            <tr>
                                <td><?= $index++ ?></td>
                                <td><?= $order['full_name'] ?? $order['username'] ?></td>
                                <td>Rp<?= number_format($order['total_price'], 0, '.', ',') ?></td>
                                <td>
                                    <span class="badge <?php if ($order['status'] === 'tertunda') : ?>text-bg-warning <?php elseif ($order['status'] === 'berhasil') : ?>text-bg-success <?php else: ?>text-bg-danger<?php endif; ?> text-capitalize">
                                        <?= $order['status'] ?>
                                    </span>
                                </td>
                                <td><?= $formattedDate ?></td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center gap-1">
                                        <div>
                                            <a href="<?= base_url(route_to('admin.orders.show', $order['id'])) ?>" class="btn btn-info">
                                                <i class="fas fa-faw fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item<?= $page <= 1 ? ' disabled' : '' ?>">
                                <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $page - 1 ?>"><i class="fas fa-fw fa-angle-left"></i></a>
                            </li>
                            <?php if ($totalPages) : ?>
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item<?= $i == $page ? ' active' : '' ?>">
                                        <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                            <?php else : ?>
                                <li class="page-item active">
                                    <a class="page-link" href="?q=<?= urlencode($search) ?>&page=1">1</a>
                                </li>
                            <?php endif; ?>
                            <li class="page-item<?= $page >= $totalPages ? ' disabled' : '' ?>">
                                <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $page + 1 ?>"><i class="fas fa-fw fa-angle-right"></i></a>
                            </li>
                        </ul>
                    </nav>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>