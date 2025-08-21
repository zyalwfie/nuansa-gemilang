<?= $this->extend('dashboard/layouts/app'); ?>

<?= $this->section('page_title'); ?>
<?= isset($product) ? $page_title_edit : $page_title_create ?>
<?= $this->endSection(); ?>

<?= $this->section('head_css'); ?>
<style>
    .upload-box {
        cursor: pointer;
        transition: 0.3s;
        background-color: #f8f9fa;
        border-style: dashed !important;
    }

    .upload-box:hover {
        background-color: #e9ecef;
        border-color: #0d6efd;
        box-shadow: 0 0 10px rgba(13, 110, 253, 0.3);
    }

    .upload-box.dragover {
        background-color: #e3f2fd;
        border-color: #0d6efd;
        box-shadow: 0 0 15px rgba(13, 110, 253, 0.6);
    }
</style>
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

                    <!-- Upload Box -->
                    <div class="upload-box text-center p-4 border border-2 rounded" id="uploadBox">
                        <i class="bi bi-cloud-arrow-up display-4 text-primary"></i>
                        <p class="mt-2 mb-1">Klik atau tarik gambar ke sini</p>
                        <small class="text-muted">Maksimal ukuran 2MB (png, jpg, jpeg)</small>
                        <input type="file" class="form-control d-none <?= session('error_image') ? 'is-invalid' : '' ?>" id="image" name="image" accept="image/*">
                        <?php if (session('error_image')) : ?>
                            <div class="invalid-feedback">
                                <?= session('error_image') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Preview Image -->
                    <div class="mt-3 d-none" id="previewContainer">
                        <p class="mb-2">Preview:</p>
                        <img id="previewImage" src="" alt="Preview" class="img-thumbnail" style="height: 200px; object-fit: cover;">
                    </div>

                    <?php if (isset($product['image'])): ?>
                        <div class="mt-2">
                            <p class="mb-1">Gambar Sebelumnya:</p>
                            <img src="<?= base_url() ?>img/uploads/main/<?= $product['image'] ?>"
                                alt="Main Image"
                                style="height: 150px; width: 150px; object-fit: cover;"
                                class="img-thumbnail">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>

                    <!-- Quill editor container -->
                    <div id="editor" style="height: 200px;">
                        <?= htmlspecialchars_decode(old('description', $product['description'] ?? '')) ?>
                    </div>

                    <!-- Hidden input untuk simpan isi editor -->
                    <input type="hidden" name="description" id="description">
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

<?= $this->section('footer_js'); ?>
<!-- Rich Editor Script -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const uploadBox = document.getElementById("uploadBox");
        const inputFile = document.getElementById("image");
        const previewContainer = document.getElementById("previewContainer");
        const previewImage = document.getElementById("previewImage");

        const quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: "Tulis deskripsi produk di sini...",
        });

        const hiddenInput = document.getElementById('description');
        hiddenInput.value = quill.root.innerHTML;

        quill.on('text-change', function() {
            hiddenInput.value = quill.root.innerHTML;
        });

        uploadBox.addEventListener("click", () => inputFile.click());

        inputFile.addEventListener("change", function() {
            handleFile(this.files[0]);
        });

        uploadBox.addEventListener("dragover", (e) => {
            e.preventDefault();
            uploadBox.classList.add("dragover");
        });

        uploadBox.addEventListener("dragleave", () => {
            uploadBox.classList.remove("dragover");
        });

        uploadBox.addEventListener("drop", (e) => {
            e.preventDefault();
            uploadBox.classList.remove("dragover");

            const file = e.dataTransfer.files[0];
            if (file) {
                inputFile.files = e.dataTransfer.files;
                handleFile(file);
            }
        });

        function handleFile(file) {
            if (file && file.type.startsWith("image/")) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove("d-none");
                };
                reader.readAsDataURL(file);
            }
        }
    });
</script>
<?= $this->endSection(); ?>