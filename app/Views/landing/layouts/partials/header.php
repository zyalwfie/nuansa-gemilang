<nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">

    <div class="container">
        <a class="navbar-brand" href="<?= base_url() ?>">Nuansa<span>.</span></a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsFurni">
            <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
                <li class="nav-item <?= url_is('/') ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url() ?>">Beranda</a>
                </li>
                <li class="nav-item <?= url_is('shop*') || url_is('cart') ? 'active' : '' ?>"><a class="nav-link" href="<?= base_url(route_to('landing.shop')) ?>">Belanja</a></li>
                <li class="nav-item <?= url_is('about') ? 'active' : '' ?>"><a class="nav-link" href="<?= base_url(route_to('landing.about')) ?>">Tentang kami</a></li>
                <li class="nav-item <?= url_is('service') ? 'active' : '' ?>"><a class="nav-link" href="<?= base_url(route_to('landing.service')) ?>">Layanan</a></li>
                <li class="nav-item <?= url_is('contact') ? 'active' : '' ?>"><a class="nav-link" href="<?= base_url(route_to('landing.contact')) ?>">Kontak</a></li>
            </ul>

            <?php if (logged_in()) : ?>
                <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
                    <?php if (in_groups('admin')) : ?>
                        <li><a class="nav-link" href="<?= base_url(route_to('admin.index')) ?>"><img src="<?= base_url() ?>/img/user.svg"></a></li>
                    <?php else : ?>
                        <li><a class="nav-link" href="<?= base_url(route_to('user.index')) ?>"><img src="<?= base_url() ?>/img/user.svg"></a></li>
                        <li class="position-relative">
                            <a class="nav-link" href="<?= base_url(route_to('landing.cart.index')) ?>">
                                <img src="<?= base_url() ?>/img/cart.svg">
                            </a>
                            <?php if ($carts_count) : ?>
                                <span class="badge rounded-pill bg-danger" style="position: absolute; top: 1px; right: -4px;">
                                    <?= $carts_count ?>
                                    <span class="visually-hidden">unread messages</span>
                                </span>
                            <?php endif; ?>
                        </li>
                    <?php endif; ?>
                </ul>
            <?php else : ?>
                <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
                    <li><a href="<?= base_url('login') ?>" class="btn btn-white-outline">Masuk</a></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>

</nav>