<?= $this->extend('dashboard/layouts/app'); ?>

<?= $this->section('page_title'); ?>
<?= $page_title ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Halo, selamat datang <span class="text-capitalize"><?= user()->full_name ? user()->full_name : 'Pengguna' ?></span>!</h1>
<p class="mb-4">Jelajahi platform kami dan nikmati pengalaman yang lebih personal dengan akses mudah ke informasi akun, riwayat pesanan, dan preferensi yang disesuaikan untuk kebutuhan Anda. Kelola semuanya dengan cepat dan praktis di satu tempat!</p>

<div class="row">

    <!-- Spending Card-->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Pengeluaran</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp<?= number_format($total_spent, '0', '.', ',') ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-coins fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Completed Orders Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pesanan Sukses</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $completed_orders_count ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Orders Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pesanan Tertunda</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $pending_orders_count ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-truck fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Center Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Pusat Layanan</div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h-5 mb-0 mr-3 font-weight-bold text-gray-800 lead">nuansa@gmail.com</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-headset fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row">

    <!-- Table Orders Area -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Pesanan</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Aksi:</div>
                        <a class="dropdown-item" href="<?= base_url(route_to('user.orders.index')) ?>">Kelola</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="table-responsive">
                    <?php if (!$orders) : ?>
                        <p class="lead text-center">Belum ada pesanan oleh <?= user()->full_name ? user()->full_name : user()->username ?>.</p>
                    <?php else : ?>
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Penerima</th>
                                    <th>Total Harga</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $index = 1; ?>
                                <?php foreach ($orders as $order) : ?>
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
                                        <td><?= $order['recipient_name'] ?></td>
                                        <td>Rp<?= number_format($order['total_price'], 0, ',', '.') ?></td>
                                        <td><?= $formattedDate ?></td>
                                        <td>
                                            <span class="badge <?php if ($order['status'] === 'tertunda') : ?>text-bg-warning <?php elseif ($order['status'] === 'berhasil') : ?>text-bg-success <?php else: ?>text-bg-danger<?php endif; ?> text-capitalize">
                                                <?= $order['status'] ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Pie Chart -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Profil</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Aksi:</div>
                        <a class="dropdown-item" href="<?= base_url(route_to('user.profile.edit')) ?>">Ubah</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div>
                    <img src="<?= base_url('img/uploads/avatar/') . user()->avatar ?>" alt="<?= user()->username ?>" style="width: 100%; object-fit: cover; border-radius: 9999rem;">
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-id-card text-primary"></i> <?= user()->full_name ? user()->full_name : 'Nama lengkap' ?>
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-user text-success"></i> <?= user()->username ?>
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-envelope text-info"></i> <?= user()->email ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>