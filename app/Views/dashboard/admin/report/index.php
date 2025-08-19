<?= $this->extend('dashboard/layouts/app'); ?>

<?= $this->section('page_title'); ?>
<?= $pageTitle ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-body">
        <div class="d-md-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="card-title">Laporan Penjualan</h4>
                <p class="card-subtitle">
                    Laporan detail penjualan yang bisa diurutkan berdasarkan rentang waktu
                </p>
            </div>
        </div>

        <?php
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;

        $filteredOrders = $filteredOrders ?? [];
        $totalSales = $totalSales ?? 0;

        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = 10;
        $total = count($filteredOrders);
        $totalPages = (int) ceil($total / $perPage);
        $start = ($page - 1) * $perPage;
        $paginatedOrders = array_slice($filteredOrders, $start, $perPage);
        ?>

        <div class="d-flex flex-column">
            <form method="get" class="mb-4 d-flex align-items-end">
                <div class="flex-grow-1 me-2">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?= htmlspecialchars($startDate ?? '') ?>">
                </div>
                <div class="flex-grow-1 me-2">
                    <label for="end_date" class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?= htmlspecialchars($endDate ?? '') ?>">
                </div>
                <div class="d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Urutkan</button>
                    <a href="<?= route_to('admin.reports.index') ?>" class="btn btn-outline-secondary me-2">Bersihkan</a>
                    <?php if ($filteredOrders) : ?>
                        <a href="<?= route_to('admin.reports.preview') . '?' . http_build_query([
                                        'start_date' => $startDate,
                                        'end_date' => $endDate
                                    ]) ?>" class="btn btn-success">
                            <i class="ti ti-file-text"></i> Pratinjau
                        </a>
                    <?php endif; ?>
                </div>
            </form>

            <!-- Total Sales Summary -->
            <div class="py-4 bg-light mb-4 rounded d-flex justify-content-center flex-column align-items-center">
                <h5 class="mb-0">Total Penjualan</h5>
                <p class="fs-3 fw-bold text-success mb-0">
                    Rp<?= number_format($totalSales, 0, '.', ',') ?>
                </p>
            </div>

            <!-- Sales Report Table -->
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
                        $filteredOrders = $filteredOrders;
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
</div>
<?= $this->endSection(); ?>

<?= $this->section('foot_js'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        if (startDateInput && endDateInput) {
            startDateInput.addEventListener('change', function() {
                endDateInput.min = this.value;
            });

            endDateInput.addEventListener('change', function() {
                startDateInput.max = this.value;
            });
        }
    });
</script>
<?= $this->endSection(); ?>