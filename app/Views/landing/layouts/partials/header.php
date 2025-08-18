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
                <li class="nav-item <?= url_is('shop*') ? 'active' : '' ?>"><a class="nav-link" href="<?= base_url(route_to('landing.shop')) ?>">Belanja</a></li>
                <li class="nav-item <?= url_is('about') ? 'active' : '' ?>"><a class="nav-link" href="<?= base_url(route_to('landing.about')) ?>">Tentang kami</a></li>
                <li class="nav-item <?= url_is('service') ? 'active' : '' ?>"><a class="nav-link" href="<?= base_url(route_to('landing.service')) ?>">Layanan</a></li>
                <li class="nav-item <?= url_is('contact') ? 'active' : '' ?>"><a class="nav-link" href="<?= base_url(route_to('landing.contact')) ?>">Kontak</a></li>
            </ul>

            <?php if (logged_in()) : ?>
                <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
                    <?php if (in_groups('admin')) : ?>
                        <li class="m-0">
                            <a class="nav-link" href="<?= base_url(route_to('admin.index')) ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                    fill="currentColor" viewBox="0 0 24 24">
                                    <!--Boxicons v3.0 https://boxicons.com | License  https://docs.boxicons.com/free-->
                                    <path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5m0-8c1.65 0 3 1.35 3 3s-1.35 3-3 3-3-1.35-3-3 1.35-3 3-3M4 22h16c.55 0 1-.45 1-1v-1c0-3.86-3.14-7-7-7h-4c-3.86 0-7 3.14-7 7v1c0 .55.45 1 1 1m6-7h4c2.76 0 5 2.24 5 5H5c0-2.76 2.24-5 5-5"></path>
                                </svg>
                            </a>
                        </li>
                    <?php else : ?>
                        <li class="m-0">
                            <a class="nav-link" href="<?= base_url(route_to('user.index')) ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                    fill="currentColor" viewBox="0 0 24 24">
                                    <!--Boxicons v3.0 https://boxicons.com | License  https://docs.boxicons.com/free-->
                                    <path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5m0-8c1.65 0 3 1.35 3 3s-1.35 3-3 3-3-1.35-3-3 1.35-3 3-3M4 22h16c.55 0 1-.45 1-1v-1c0-3.86-3.14-7-7-7h-4c-3.86 0-7 3.14-7 7v1c0 .55.45 1 1 1m6-7h4c2.76 0 5 2.24 5 5H5c0-2.76 2.24-5 5-5"></path>
                                </svg>
                            </a>
                        </li>
                        <li class="position-relative">
                            <a class="nav-link <?= url_is('cart*') ? 'active' : '' ?>" href="<?= base_url(route_to('landing.cart.index')) ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                    fill="currentColor" viewBox="0 0 24 24">
                                    <!--Boxicons v3.0 https://boxicons.com | License  https://docs.boxicons.com/free-->
                                    <path d="M10.5 18a1.5 1.5 0 1 0 0 3 1.5 1.5 0 1 0 0-3M17.5 18a1.5 1.5 0 1 0 0 3 1.5 1.5 0 1 0 0-3M8.82 15.77c.31.75 1.04 1.23 1.85 1.23h6.18c.79 0 1.51-.47 1.83-1.2l3.24-7.4c.14-.31.11-.67-.08-.95S21.34 7 21 7H7.33L5.92 3.62C5.76 3.25 5.4 3 5 3H2v2h2.33zM19.47 9l-2.62 6h-6.18l-2.5-6z"></path>
                                </svg>
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