<?= $this->extend('dashboard/layouts/app'); ?>

<?= $this->section('page_title'); ?>
<?= $pageTitle ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= route_to('user.profile.index') ?>">Profil</a></li>
                <li class="breadcrumb-item active" aria-current="page">Ganti Sandi</li>
            </ol>
        </nav>

        <h3 class="card-title fw-semibold mb-2">Ganti Sandi</h3>

        <?php if (session()->has('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-stretch justify-content-between px-3" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-fw fa-check"></i>
                    <?= session('error') ?>
                </div>
                <button type="button" class="btn p-0 ms-auto" data-bs-dismiss="alert" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path d="m7.76 14.83-2.83 2.83 1.41 1.41 2.83-2.83 2.12-2.12.71-.71.71.71 1.41 1.42 3.54 3.53 1.41-1.41-3.53-3.54-1.42-1.41-.71-.71 5.66-5.66-1.41-1.41L12 10.59 6.34 4.93 4.93 6.34 10.59 12l-.71.71z"></path>
                    </svg>
                </button>
            </div>
        <?php elseif (session()->has('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-stretch justify-content-between px-3" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-fw fa-ban"></i>
                    <?= session('error') ?>
                </div>
                <button type="button" class="btn p-0 ms-auto" data-bs-dismiss="alert" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path d="m7.76 14.83-2.83 2.83 1.41 1.41 2.83-2.83 2.12-2.12.71-.71.71.71 1.41 1.42 3.54 3.53 1.41-1.41-3.53-3.54-1.42-1.41-.71-.71 5.66-5.66-1.41-1.41L12 10.59 6.34 4.93 4.93 6.34 10.59 12l-.71.71z"></path>
                    </svg>
                </button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form action="<?= route_to('user.profile.update.password') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Sandi Saat Ini <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password"
                                class="form-control <?= session('errors.current_password') ? 'is-invalid' : '' ?>"
                                id="current_password"
                                name="current_password"
                                placeholder="Masukkan sandi saat ini"
                                required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                <i class="fas fa-fw fa-eye"></i>
                            </button>
                            <?php if (session('errors.current_password')) : ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.current_password') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">Sandi Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password"
                                class="form-control <?= session('errors.new_password') ? 'is-invalid' : '' ?>"
                                id="new_password"
                                name="new_password"
                                placeholder="Masukkan sandi baru"
                                required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password')">
                                <i class="fas fa-fw fa-eye"></i>
                            </button>
                            <?php if (session('errors.new_password')) : ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.new_password') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <small class="text-muted">Minimal 8 karakter, kombinasi huruf besar, huruf kecil, dan angka</small>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Konfirmasi Sandi Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password"
                                class="form-control <?= session('errors.confirm_password') ? 'is-invalid' : '' ?>"
                                id="confirm_password"
                                name="confirm_password"
                                placeholder="Konfirmasi sandi baru"
                                required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password')">
                                <i class="fas fa-fw fa-eye"></i>
                            </button>
                            <?php if (session('errors.confirm_password')) : ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.confirm_password') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="fas fa-fw fa-info me-2"></i>
                        <div>
                            Setelah mengganti sandi, Anda akan diminta untuk login kembali dengan sandi baru.
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="<?= route_to('admin.profile.index') ?>" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-fw fa-check-double me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('footer_js'); ?>
<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const button = field.nextElementSibling;
        const icon = button.querySelector('i');

        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
<?= $this->endSection(); ?>