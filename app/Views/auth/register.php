<?= $this->extend('auth/layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                <div class="col-lg-7">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4"><?= lang('Auth.register') ?></h1>
                        </div>

                        <?= view('Myth\Auth\Views\_message_block') ?>

                        <form class="user" action="<?= base_url(route_to('register')) ?>" method="post">
                            <?= csrf_field() ?>

                            <div class="form-group">
                                <input type="text" class="form-control form-control-user <?php if (session('errors.username')) : ?>is-invalid<?php endif ?>" name="username" value="<?= old('username') ?>"
                                    placeholder="Username">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control form-control-user <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>" value="<?= old('email') ?>" name="email"
                                    placeholder="Email Address">
                                <small id="emailHelp" class="form-text text-muted"><?= lang('Auth.weNeverShare') ?></small>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" class="form-control form-control-user <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>"
                                        name="password" placeholder="Password" autocomplete="off">
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control form-control-user <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>"
                                        name="pass_confirm" placeholder="Repeat Password" autocomplete="off">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                <?= lang('Auth.register') ?>
                            </button>
                        </form>
                        <hr>
                        <div class="text-center">
                            <a class="small" href="<?= base_url('login') ?>"><?= lang('Auth.alreadyRegistered') ?> <?= lang('Auth.signIn') ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection(); ?>