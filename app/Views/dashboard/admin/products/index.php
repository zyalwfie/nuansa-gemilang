<?= $this->extend('dashboard/layouts/app'); ?>

<?= $this->section('page_title'); ?>
<?= $page_title ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Data Produk Bean Bag</h1>
<p class="mb-4">Kelola dan pantau informasi produk secara efisien melalui tabel interaktif yang mendukung pencarian dan pengurutan data.</p>

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

<!-- Data Tables Product -->
<div class="card shadow mb-4">
    <div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center py-3">
        <div class="row mb-3 mb-lg-0 align-items-lg-center">
            <div class="col-12 col-lg-4 mb-2 mb-lg-0">
                <h6 class="m-0 font-weight-bold text-primary">Katalog Produk</h6>
            </div>
            <div class="col-12 col-lg">
                <form class="d-flex gap-1" role="search" method="get">
                    <input class="form-control" type="search" name="q" placeholder="Cari bean bag" aria-label="Search" value="<?= esc($_GET['q'] ?? '') ?>" />
                    <button class="btn btn-outline-success" type="submit"><i class="fa fa-faw fa-search"></i></button>
                </form>
            </div>
        </div>
        <a class="btn btn-primary" href="<?= url_to('admin.products.create') ?>"><i class="fas fa-fw fa-cart-plus"></i>&nbsp;&nbsp;<span>Tambah Produk</span></a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $search = $_GET['q'] ?? '';
                    $filteredProducts = $products;
                    if ($search) {
                        $filteredProducts = array_filter($products, function ($product) use ($search) {
                            return stripos($product['name'], $search) !== false;
                        });
                    }

                    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
                    $perPage = 5;
                    $total = count($filteredProducts);
                    $totalPages = (int) ceil($total / $perPage);
                    $start = ($page - 1) * $perPage;
                    $paginatedProducts = array_slice($filteredProducts, $start, $perPage);
                    $index = $start + 1;
                    foreach ($paginatedProducts as $product) :
                    ?>
                        <tr>
                            <td><?= $index++ ?></td>
                            <td><img src="<?= base_url() ?>img/uploads/main/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" width="100"></td>
                            <td><?= $product['name'] ?></td>
                            <td>Rp<?= number_format($product['price'], '0', '.', ',') ?></td>
                            <td><?= $product['stock'] ?></td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center gap-1">
                                    <div>
                                        <button type="button" class="btn btn-info btn-detail-modal"
                                            data-bs-toggle="modal" data-bs-target="#detailModal"
                                            data-name="<?= esc($product['name']) ?>"
                                            data-price="<?= esc($product['price']) ?>"
                                            data-stock="<?= esc($product['stock']) ?>"
                                            data-image="<?= base_url('img/uploads/main/' . $product['image']) ?>"
                                            data-additional-images='<?= esc(json_encode($product['additional_images'])) ?>'
                                            data-description="<?= esc($product['description']) ?>">
                                            <i class="fas fa-faw fa-eye"></i>
                                        </button>
                                    </div>
                                    <div><a href="<?= base_url(route_to('admin.products.edit', $product['slug'])) ?>" class="btn btn-warning"><i class="fas fa-faw fa-pen"></i></a></div>
                                    <button type="button" class="btn btn-danger btn-delete-modal"
                                        data-bs-toggle="modal" data-bs-target="#confirmModal"
                                        data-slug="<?= esc($product['slug']) ?>">
                                        <i class="fas fa-fw fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item<?= $page <= 1 ? ' disabled' : '' ?>">
                                <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $page - 1 ?>"><i class="fas fa-fw fa-angle-left"></i></a>
                            </li>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item<?= $i == $page ? ' active' : '' ?>">
                                    <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item<?= $page >= $totalPages ? ' disabled' : '' ?>">
                                <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $page + 1 ?>"><i class="fas fa-fw fa-angle-right"></i></a>
                            </li>
                        </ul>
                    </nav>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" tabindex="-1" id="detailModal" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productName"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Produk</label>
                    <input type="text" class="form-control"
                        id="name" value="Produk Name" disabled>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Harga</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="price">Rp</span>
                            <input id="price" type="number" class="form-control" aria-label="Price" aria-describedby="price" value="20000" disabled>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">Stok</label>
                        <input type="number" class="form-control"
                            id="stock" value="20" disabled>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Gambar Utama</label>
                    <div class="mt-2">
                        <img src="<?= base_url('img/default-img-product.svg') ?>" alt="Main Image" style="height: 150px; width: 150px; object-fit: cover;" class="img-thumbnail">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="additional_images" class="form-label">Gambar Lainnya <small class="text-muted">Opsional</small></label>
                    <div class="d-flex flex-wrap gap-3 mt-2">
                        <img src="<?= base_url('img/default-img-product.svg') ?>" alt="Additional Image" class="img-thumbnail" style="height: 150px; width: 150px; object-fit: cover;">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <div class="form-control" id="description" style="min-height:100px; background:#fff; overflow:auto"><?= $product['description'] ?></div>
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
            <div class="modal-body">Kamu yakin ingin menghapus data produk ini? Tindakan ini tidak dapat dibatalkan.</div>
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
        const detailModal = document.getElementById('detailModal');
        const title = detailModal.querySelector('#productName');
        const nameInput = detailModal.querySelector('#name');
        const priceInput = detailModal.querySelector('#price');
        const stockInput = detailModal.querySelector('#stock');
        const imageTag = detailModal.querySelector('img[alt="Main Image"]');
        const additionalImagesDiv = detailModal.querySelector('.mb-3 .d-flex.flex-wrap');
        const descriptionTextarea = detailModal.querySelector('#description');

        document.querySelectorAll('.btn-detail-modal').forEach(btn => {
            btn.addEventListener('click', function() {
                title.textContent = this.dataset.name
                nameInput.value = this.dataset.name;
                priceInput.value = this.dataset.price;
                stockInput.value = this.dataset.stock;
                imageTag.src = this.dataset.image;
                descriptionTextarea.value = this.dataset.description;

                let additionalImages = [];
                try {
                    additionalImages = JSON.parse(this.dataset.additionalImages);
                } catch (e) {
                    additionalImages = [];
                }
                additionalImagesDiv.innerHTML = '';
                if (Array.isArray(additionalImages) && additionalImages.length > 0) {
                    additionalImages.forEach(img => {
                        const imgEl = document.createElement('img');
                        imgEl.src = '<?= base_url('img/uploads/adds/') ?>' + img;
                        imgEl.alt = 'Additional Image';
                        imgEl.className = 'img-thumbnail';
                        imgEl.style.height = '150px';
                        imgEl.style.width = '150px';
                        imgEl.style.objectFit = 'cover';
                        additionalImagesDiv.appendChild(imgEl);
                    });
                } else {
                    const imgEl = document.createElement('img');
                    imgEl.src = '<?= base_url('img/default-img-product.svg') ?>';
                    imgEl.alt = 'Additional Image';
                    imgEl.className = 'img-thumbnail';
                    imgEl.style.height = '150px';
                    imgEl.style.width = '150px';
                    imgEl.style.objectFit = 'cover';
                    additionalImagesDiv.appendChild(imgEl);
                }
            });
        });

        document.querySelectorAll('.btn-delete-modal').forEach(btn => {
            btn.addEventListener('click', function() {
                const form = document.querySelector('#deleteProductForm');
                const slug = this.getAttribute('data-slug');

                form.action = `<?= base_url() ?>dashboard/admin/products/destroy/${slug}`;
            });
        });
    });
</script>
<?= $this->endSection(); ?>