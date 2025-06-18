<?= $this->extend('dashboard/layouts/app'); ?>

<?= $this->section('page_title'); ?>
<?= $page_title ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Halaman Ubah Profil</h1>
<p class="mb-4">Perbarui informasi pribadi seperti foto profil, nama lengkap, dan email untuk menjaga data tetap akurat.</p>

<?= form_open_multipart(base_url(route_to('user.profile.update'))) ?>
<div class="row">
    <div class="col-12 col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Gambar Profil</h6>
            </div>
            <div class="card-body">
                <div class="position-relative rounded-circle d-flex justify-content-center align-items-center">
                    <img id="avatarPreview" src="<?= base_url('img/uploads/avatar/') . user()->avatar ?>" class="rounded-circle object-fit-cover" alt="Avatar" style="width: 100%; height: 100%;">
                    <div class="position-absolute top-50 start-50 translate-middle z-1">
                        <input type="file" id="avatar" name="avatar" class="d-none" accept="image/*">
                        <label for="avatar">
                            <div style="width: 50px; height: 50px; border-radius: 9999rem; border: 1px solid gray;" class="d-flex align-items-center justify-content-center btn btn-primary fs-5">
                                <i class="fas fa-fw fa-pen"></i>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-8">
        <?= csrf_field() ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Detail Profil</h6>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-4">
                    <div>
                        <label for="fullName">Nama Lengkap</label>
                        <input type="text" class="form-control <?= (session('errors.full_name')) ? 'is-invalid' : '' ?>" id="fullName" name="full_name" value="<?= old('full_name', user()->full_name) ?>" placeholder="Masukkan nama lengkap">
                        <?php if (session('errors.full_name')) : ?>
                            <divm class="invalid-feedback">
                                <?= session('errors.full_name') ?>
                            </divm>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label for="email">Email</label>
                        <input type="email" class="form-control <?= (session('errors.email')) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= old('email', user()->email) ?>" placeholder="Masukkan email">
                        <?php if (session('errors.email')) : ?>
                            <divm class="invalid-feedback">
                                <?= session('errors.email') ?>
                            </divm>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label for="username">Nama Pengguna</label>
                        <input type="text" class="form-control <?= (session('errors.username')) ? 'is-invalid' : '' ?>" id="username" name="username" value="<?= old('username', user()->username) ?>" placeholder="Masukkan nama pengguna">
                        <?php if (session('errors.username')) : ?>
                            <divm class="invalid-feedback">
                                <?= session('errors.username') ?>
                            </divm>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?= base_url(route_to('user.profile.index')) ?>" class="btn btn-secondary">Batalkan</a>
                <button type="submit" class="btn btn-primary">Ubah</button>
            </div>
        </div>
    </div>
</div>
<?= form_close() ?>
<?= $this->endSection(); ?>

<?= $this->section('footer_js'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const avatarInput = document.getElementById('avatar');
        const avatarPreview = document.getElementById('avatarPreview');
        if (avatarInput && avatarPreview) {
            avatarInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(evt) {
                        avatarPreview.src = evt.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
<?= $this->endSection(); ?>