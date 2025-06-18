<?= $this->extend('auth/layouts/app'); ?>

<?= $this->section('page_title'); ?>
Nuansa | Halaman Lupa Sandi
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-2">Lupa Kata Sandi Anda?</h1>
                                    <p class="mb-4">Cukup masukkan alamat emailmu di bawah ini dan kami akan mengirimkan tautan untuk mengatur ulang kata sandimu!</p>

                                    <?= view('Myth\Auth\Views\_message_block') ?>

                                </div>
                                <form class="user" action="<?= url_to('forgot') ?>" method="post">
                                    <?= csrf_field() ?>
                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control form-control-user <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>"
                                            id="email" aria-describedby="emailHelp"
                                            placeholder="Masukkan emailmu">
                                        <div class="invalid-feedback">
                                            <?= session('errors.email') ?>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        <?= lang('Auth.sendInstructions') ?>
                                    </button>
                                </form>
                                <hr>
                                <?php if ($config->allowRegistration) : ?>
                                    <div class="text-center">
                                        <a class="small" href="<?= url_to('register') ?>">Belum punya akun?</a>
                                    </div>
                                <?php endif; ?>
                                <div class="text-center">
                                    <a class="small" href="<?= url_to('login') ?>"><?= lang('Auth.alreadyRegistered') ?> <?= lang('Auth.signIn') ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
<?= $this->endSection(); ?>