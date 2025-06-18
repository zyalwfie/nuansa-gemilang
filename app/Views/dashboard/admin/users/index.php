<?= $this->extend('dashboard/layouts/app'); ?>

<?= $this->section('page_title'); ?>
<?= $page_title ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Data Pengguna</h1>
<p class="mb-4">Admin dapat mengelola pengguna dengan menghapus akun yang tidak aktif atau melanggar kebijakan. Fitur ini memungkinkan pengelolaan data pengguna secara sederhana dan efisien tanpa kemampuan untuk melakukan pengeditan data pengguna.</p>

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

<!-- Data Users -->
<div class="card shadow mb-4">
    <div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center py-3">
        <div class="col-12 col-lg-4 mb-2 mb-lg-0">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengguna</h6>
        </div>
        <div class="col-12 col-lg-5">
            <form class="d-flex gap-1" role="search">
                <input class="form-control" type="search" placeholder="Cari pengguna" aria-label="Search" />
                <button class="btn btn-outline-success" type="submit"><i class="fa fa-faw fa-search"></i></button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Gambar</th>
                        <th>Nama Pengguna</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <td><?= $index++ ?></td>
                            <td><img src="<?= base_url() ?>img/uploads/avatar/<?= $user->avatar ?>" alt="<?= $user->username ?>" width="100"></td>
                            <td><?= $user->username ?></td>
                            <td>
                                <?php if ($user->active) : ?>
                                    <span class="badge text-bg-primary">Active</span>
                                <?php else : ?>
                                    <span class="badge text-bg-secondary">Disabled</span>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center gap-1">
                                    <div>
                                        <button type="button" class="btn btn-info btn-detail-modal"
                                            data-bs-toggle="modal" data-bs-target="#detailModal"
                                            data-email="<?= esc($user->email) ?>"
                                            data-full_name="<?= esc($user->full_name) ?>"
                                            data-username="<?= esc($user->username) ?>"
                                            data-avatar="<?= base_url('img/uploads/avatar/' . $user->avatar) ?>">
                                            <i class="fas fa-faw fa-eye"></i>
                                        </button>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-delete-modal"
                                        data-bs-toggle="modal" data-bs-target="#confirmModal"
                                        data-username="<?= esc($user->username) ?>">
                                        <i class="fas fa-fw fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" tabindex="-1" id="detailModal" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-capitalize" id="headingFullName"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="fullName" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control text-capitalize"
                        id="fullName" disabled>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group mb-3">
                            <input id="email" type="text" class="form-control" aria-label="Email" aria-describedby="email" disabled>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Nama Pengguna</label>
                        <input type="text" class="form-control"
                            id="username" disabled>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Avatar</label>
                    <div class="mt-2">
                        <img id="avatar" style="height: 150px; width: 150px; object-fit: cover;" class="img-thumbnail">
                    </div>
                </div>
            </div>
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
            <div class="modal-body">Kamu yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.</div>
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
    document.addEventListener('DOMContentLoaded', function() {
        const detailModal = document.querySelector('#detailModal');
        const fullNameHead = document.querySelector('#headingFullName');
        const fullName = document.querySelector('#fullName');
        const email = document.querySelector('#email');
        const username = document.querySelector('#username');
        const avatar = document.querySelector('#avatar');

        document.querySelectorAll('.btn-detail-modal').forEach(btn => {
            btn.addEventListener('click', function() {
                fullNameHead.innerHTML = this.dataset.full_name || 'Belum ada nama lengkap!';
                fullName.value = this.dataset.full_name || 'Belum ada nama lengkap!';
                email.value = this.dataset.email;
                username.value = this.dataset.username;
                avatar.src = this.dataset.avatar;
                avatar.alt = this.dataset.full_name && 'Belum ada nama lengkap!';
            })
        });

        document.querySelectorAll('.btn-delete-modal').forEach(btn => {
            btn.addEventListener('click', function() {
                const form = document.querySelector('#deleteProductForm');
                const username = this.getAttribute('data-username');

                form.action = `<?= base_url() ?>dashboard/admin/users/destroy/${username}`;
            });
        });
    })
</script>
<?= $this->endSection(); ?>