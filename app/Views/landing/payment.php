<?= $this->extend('landing/layouts/app'); ?>

<?= $this->section('page_title'); ?>
<?= $page_title ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<!-- Start Hero Section -->
<div class="hero">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="intro-excerpt">
                    <h1>Pembayaran</h1>
                    <p>Unggah bukti pembayaran kamu supaya pesananmu segera diproses.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->

<div class="untree_co-section before-footer-section">
    <div class="container">

        <?= form_open_multipart(base_url(route_to('landing.cart.payment.upload'))) ?>
        <div class="row justify-content-center">
            <div class="col-md-6 mb-5 mb-md-0">
                <h2 class="h3 mb-3 text-black">Bukti Pembayaran</h2>
                <div class="p-3 p-lg-5 border bg-white">
                    <div class="mb-4">
                        <label for="proof_of_payment" class="form-label">File Bukti Pembayaran <span class="text-danger">*</span></label>
                        <input class="form-control <?= session('errors.proof_of_payment') ? 'is-invalid' : '' ?>" type="file" id="proof_of_payment" name="proof_of_payment" accept="image/*,application/pdf" onchange="previewProof(event)">
                        <?php if (session('errors.proof_of_payment')) : ?>
                            <div class="invalid-feedback">
                                <?= session('errors.proof_of_payment') ?>
                            </div>
                        <?php endif; ?>
                        <div id="previewContainer" class="mt-3"></div>
                    </div>
                    <input type="hidden" name="order_id" value="<?= $order_id ?>">
                    <div class="d-flex justify-content-end align-items-center gap-3">
                        <a href="<?= base_url(route_to('landing.cart.payment.done')) ?>" class="btn btn-secondary">Lakukan Nanti</a>
                        <button type="submit" class="btn btn-primary">Unggah Bukti</button>
                    </div>
                </div>
            </div>
        </div>
        <?= form_close() ?>

    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('footer_js'); ?>
<script>
    function previewProof(event) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById('previewContainer');
        previewContainer.innerHTML = '';
        if (!file) return;
        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.style.maxWidth = '100%';
            img.style.maxHeight = '300px';
            img.className = 'img-fluid border rounded';
            previewContainer.appendChild(img);
        } else if (file.type === 'application/pdf') {
            const pdfIcon = document.createElement('div');
            pdfIcon.innerHTML = '<span class="text-secondary">Preview tidak tersedia untuk PDF. File siap diunggah.</span>';
            previewContainer.appendChild(pdfIcon);
        } else {
            previewContainer.innerHTML = '<span class="text-danger">File tidak didukung.</span>';
        }
    }
</script>
<?= $this->endSection(); ?>