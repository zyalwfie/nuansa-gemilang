<?= $this->extend('auth/layouts/app'); ?>

<?= $this->section('page_title'); ?>
Nuansa | Halaman Atur Ulang Sandi
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
                                    <h1 class="h4 text-gray-900 mb-2"><?= lang('Auth.resetYourPassword') ?></h1>
                                    <p class="mb-4"><?= lang('Auth.enterCodeEmailPassword') ?></p>

                                    <?= view('Myth\Auth\Views\_message_block') ?>

                                </div>
                                <form class="user" action="<?= url_to('reset-password') ?>" method="post">
                                    <?= csrf_field() ?>

                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user <?php if (session('errors.token')) : ?>is-invalid<?php endif ?>" name="token" value="<?= old('token', $token ?? '') ?>"
                                            placeholder="<?= lang('Auth.token') ?>">
                                        <div class="invalid-feedback">
                                            <?= session('errors.token') ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control form-control-user <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>"
                                            id="email" aria-describedby="emailHelp"
                                            placeholder="<?= lang('Auth.email') ?>">
                                        <div class="invalid-feedback">
                                            <?= session('errors.email') ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>"
                                            name="password" placeholder="<?= lang('Auth.newPassword') ?>">
                                        <div class="invalid-feedback">
                                            <?= session('errors.password') ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>"
                                            name="pass_confirm" placeholder="<?= lang('Auth.newPasswordRepeat') ?>">
                                        <div class="invalid-feedback">
                                            <?= session('errors.pass_confirm') ?>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        <?= lang('Auth.resetYourPassword') ?>
                                    </button>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
<?= $this->endSection(); ?>