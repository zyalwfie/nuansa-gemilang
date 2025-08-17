<?= $this->extend('dashboard/layouts/app'); ?>

<?= $this->section('page_title'); ?>
<?= $page_title ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Data Alamat</h1>
<p class="mb-4">Kelola informasi alamat pelanggan melalui tabel interaktif dengan pencarian data.</p>

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

<!-- Data Tables Address -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row mb-3 mb-lg-0 align-items-center justify-content-between">
            <div class="col-12 col-lg-4">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Semua Alamat</h6>
            </div>
            <div class="col-12 col-lg-4 d-flex align-items-center gap-1">
                <form class="d-flex gap-1 flex-grow-1" role="search" method="get">
                    <input class="form-control" type="search" name="q" placeholder="Cari alamat" aria-label="Search" value="<?= esc($_GET['q'] ?? '') ?>" />
                    <button class="btn btn-outline-success" type="submit"><i class="fa fa-faw fa-search"></i></button>
                </form>
                <a href="<?= route_to('user.address.index') ?>" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        fill="currentColor" viewBox="0 0 24 24">
                        <!--Boxicons v3.0 https://boxicons.com | License  https://docs.boxicons.com/free-->
                        <path d="m7.76 14.83-2.83 2.83 1.41 1.41 2.83-2.83 2.12-2.12.71-.71.71.71 1.41 1.42 3.54 3.53 1.41-1.41-3.53-3.54-1.42-1.41-.71-.71 5.66-5.66-1.41-1.41L12 10.59 6.34 4.93 4.93 6.34 10.59 12l-.71.71z"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-baddressed" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Label</th>
                        <th>Nama Penerima</th>
                        <th>Nomor Telepon</th>
                        <th>Jalan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $search = $_GET['q'] ?? '';
                    $filteredAddresses = $addresses;
                    if ($search) {
                        $filteredAddresses = array_filter($addresses, function ($address) use ($search) {
                            return stripos($address['street_address'], $search) !== false;
                        });
                    }

                    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
                    $perPage = 5;
                    $total = count($filteredAddresses);
                    $totalPages = (int) ceil($total / $perPage);
                    $start = ($page - 1) * $perPage;
                    $paginatedAddresses = array_slice($filteredAddresses, $start, $perPage);
                    $index = $start + 1;
                    ?>

                    <?php if (!$paginatedAddresses) : ?>
                        <tr>
                            <td colspan="6" class="text-center">Alamat tidak ditemukan!</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($paginatedAddresses as $address) : ?>
                            <tr>
                                <td><?= $index++ ?></td>
                                <td>
                                    <span class="badge <?php if ($address['label'] === 'Rumah') : ?>text-bg-primary <?php elseif ($address['label'] === 'Kos') : ?>text-bg-secondary <?php else: ?>text-bg-dark<?php endif; ?> text-capitalize">
                                        <?= $address['label'] ?>
                                    </span>
                                </td>
                                <td><?= $address['full_name'] ?></td>
                                <td><?= $address['phone_number'] ?></td>
                                <td><?= $address['street_address'] ?></td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <button class="btn btn-danger" data-id="<?= $address['id'] ?>">
                                            <i class="fas fa-faw fa-trash"></i>
                                        </button>
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

<!-- Confirm Modal-->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModal"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModal">Apakah kamu yakin?</h5>
            </div>
            <div class="modal-body">Kamu yakin ingin menghapus data alamat ini? Tindakan ini tidak dapat dibatalkan.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batalkan</button>
                <form id="deleteProductForm" method="post">
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('footer_js'); ?>
<script>
    document.querySelectorAll('.btn-delete-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = document.querySelector('#deleteProductForm');
            const id = this.getAttribute('data-id');

            form.action = `<?= base_url() ?>dashboard/user/address/destroy/${slug}`;
        });
    });
</script>
<?= $this->endSection(); ?>