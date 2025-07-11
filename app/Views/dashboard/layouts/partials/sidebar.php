<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= in_groups('admin') ? base_url(route_to('admin.index')) : base_url(route_to('user.index')) ?>">
        <div class="sidebar-brand-icon">
            <img src="<?= base_url('img/nuansa.svg') ?>" alt="Nuansa Logo" width="30">
        </div>
        <div class="sidebar-brand-text mx-3">Nuansa</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url(route_to('landing.index')) ?>">
            <i class="fas fa-fw fa-home"></i>
            <span>Kembali</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= (uri_string() === 'dashboard/user' || uri_string() === 'dashboard/admin') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= in_groups('admin') ? base_url(route_to('admin.index')) : base_url(route_to('user.index')) ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dasbor</span>
        </a>
    </li>

    <?php if (in_groups('admin')) : ?>
        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Manajemen
        </div>

        <!-- Nav Item - Users -->
        <li class="nav-item <?= url_is('dashboard/admin/users*') ? 'active' : '' ?>">
            <a class="nav-link" href="<?= base_url(route_to('admin.users.index')) ?>">
                <i class="fas fa-fw fa-users"></i>
                <span>Pengguna</span></a>
        </li>

        <!-- Nav Item - Products -->
        <li class="nav-item <?= url_is('dashboard/admin/products*') ? 'active' : '' ?>">
            <a class="nav-link" href="<?= base_url(route_to('admin.products.index')) ?>">
                <i class="fas fa-fw fa-shopping-bag"></i>
                <span>Produk</span>
            </a>
        </li>
    <?php endif; ?>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Detail
    </div>

    <!-- Nav Item - My Orders -->
    <li class="nav-item <?= (url_is('dashboard/user/orders*') || url_is('dashboard/admin/orders*')) ? 'active' : '' ?>">
        <a class="nav-link" href="<?= in_groups('admin') ? base_url(route_to('admin.orders.index')) : base_url(route_to('user.orders.index')) ?>">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Pesanan</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Pengaturan
    </div>

    <!-- Nav Item - My Profile -->
    <li class="nav-item <?= (uri_string() === 'dashboard/user/profile' || uri_string() === 'dashboard/admin/profile') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= in_groups('admin') ? base_url(route_to('admin.profile.index')) : base_url(route_to('user.profile.index')) ?>">
            <i class="fas fa-fw fa-user"></i>
            <span>Profil</span></a>
    </li>

    <!-- Nav Item - Edit Profile -->
    <li class="nav-item <?= (uri_string() === 'dashboard/user/profile/edit' || uri_string() === 'dashboard/admin/profile/edit') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= in_groups('admin') ? base_url(route_to('admin.profile.edit')) : base_url(route_to('user.profile.edit')) ?>">
            <i class="fas fa-fw fa-user-edit"></i>
            <span>Ubah Profil</span></a>
    </li>

    <!-- Nav Item - Logout -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('logout') ?>">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Keluar</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>