<?= $this->extend('dashboard/layouts/app'); ?>

<?= $this->section('page_title'); ?>
<?= $page_title ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Halaman Profil</h1>
<p class="mb-4">Halaman ini memungkinkan admin untuk melihat dan memperbarui informasi profil pribadi, termasuk foto profil, nama lengkap, dan email. Pastikan data profil Anda selalu akurat untuk kemudahan identifikasi dan komunikasi di dalam sistem.</p>

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

<div class="row">
    <div class="col-12 col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Gambar Profil</h6>
            </div>
            <div class="card-body">
                <img src="<?= base_url('img/uploads/avatar/') . user()->avatar ?>" class="img-thumbnail rounded-circle w-full" alt="Avatar">
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Detail Profil</h6>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-4">
                    <div class="position-relative px-3 pb-2 pt-3 border">
                        <small class="position-absolute p-1 bg-white text-secondary" style="left: .5rem; top: -.75rem;">Nama Lengkap</small>
                        <?= (!empty(user()->full_name)) ? user()->full_name : 'Belum ada nama lengkap!' ?>
                    </div>
                    <div class="position-relative px-3 pb-2 pt-3 border">
                        <small class="position-absolute p-1 bg-white text-secondary" style="left: .5rem; top: -.75rem;">Email</small>
                        <?= user()->email ?>
                    </div>
                    <div class="position-relative px-3 pb-2 pt-3 border">
                        <small class="position-absolute p-1 bg-white text-secondary" style="left: .5rem; top: -.75rem;">Nama Pengguna</small>
                        <?= user()->username ?>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="<?= route_to('admin.profile.edit') ?>" class="btn btn-primary">
                            <i class="fas fa-fw fa-pen me-2"></i>Ubah Profil
                        </a>
                        <a href="<?= route_to('admin.profile.change.password') ?>" class="btn btn-warning">
                            <i class="fas fa-fw fa-lock me-2"></i>Ganti Sandi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>