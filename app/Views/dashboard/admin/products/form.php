<?= $this->extend('dashboard/layouts/app'); ?>

<?= $this->section('page_title'); ?>
<?= isset($product) ? $page_title_edit : $page_title_create ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <?= isset($product) ? 'Ubah Produk' : 'Tambah Produk Baru' ?>
                </h6>
            </div>
            <div class="card-body">

                <?= form_open_multipart(isset($product) ? url_to('admin.products.update', $product['id']) : url_to('admin.products.store')) ?>

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?= session('errors.name') ? 'is-invalid' : '' ?>"
                        id="name" name="name" value="<?= old('name', $product['name'] ?? '') ?>" autocomplete="on">
                    <?php if (session('errors.name')): ?>
                        <div class="invalid-feedback"><?= session('errors.name') ?></div>
                    <?php endif; ?>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Harga <span class="text-danger">*</span></label>
                        <div class="input-group mb-3">
                            <span class="input-group-text <?= session('errors.price') ? 'is-invalid' : '' ?>" id="price">Rp</span>
                            <input id="price" type="number" class="form-control" aria-label="Price" aria-describedby="price" name="price" value="<?= old('price', $product['price'] ?? '') ?>">
                            <?php if (session('errors.price')): ?>
                                <div class="invalid-feedback"><?= session('errors.price') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                        <input type="number" class="form-control <?= session('errors.stock') ? 'is-invalid' : '' ?>"
                            id="stock" name="stock" value="<?= old('stock', $product['stock'] ?? '') ?>">
                        <?php if (session('errors.stock')): ?>
                            <div class="invalid-feedback"><?= session('errors.stock') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Gambar Utama <span class="text-danger">*</span></label>
                    <input class="form-control <?= session()->has('error_image') ? 'is-invalid' : '' ?>" type="file" id="image" name="image" <?= !isset($product) ? '' : '' ?>>
                    <?php if (session()->has('error_image')): ?>
                        <div class="invalid-feedback"><?= session('error_image') ?></div>
                    <?php endif; ?>
                    <small class="text-muted">Maksimal ukuran 2MB (png, jpg, jpeg)</small>
                    <?php if (isset($product['image'])): ?>
                        <div class="mt-2">
                            <img src="<?= base_url() ?>img/uploads/main/<?= $product['image'] ?>" alt="Main Image" style="height: 150px; width: 150px; object-fit: cover;" class="img-thumbnail">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="additional_images" class="form-label">Gambar Lainnya <small class="text-muted">Opsional</small></label>
                    <input type="file" class="form-control <?= session()->has('error_additional_images') ? 'is-invalid' : '' ?>" id="additional_images"
                        name="additional_images[]" multiple accept="image/*">
                    <?php if (session()->has('error_additional_images')): ?>
                        <div class="invalid-feedback"><?= session('error_additional_images') ?></div>
                    <?php endif; ?>
                    <small class="text-muted">Maksimal 5 gambar (1MB)</small>
                    <?php if (!empty($product['additional_images'])): ?>
                        <div class="d-flex gap-3 mt-2">
                            <?php foreach (json_decode($product['additional_images']) as $img): ?>
                                <img src="<?= base_url() ?>img/uploads/adds/<?= $img ?>" alt="Additional Image" class="img-thumbnail" style="height: 150px; width: 150px; object-fit: cover;">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="is_featured" name="is_featured">
                        <label class="form-check-label" for="is_featured">
                            Rekomendasi
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description"><?= old('description', $product['description'] ?? '') ?></textarea>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="<?= base_url(route_to('admin.products.index')) ?>" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary"><?= isset($product) ? 'Perbarui' : 'Tambah' ?></button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('foot_js'); ?>
<!-- Rich Editor Script -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/skins/ui/oxide/skin.min.css">
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js"></script>
<script src="<?= base_url('js/rich-editor.js') ?>"></script>
<?= $this->endSection(); ?>