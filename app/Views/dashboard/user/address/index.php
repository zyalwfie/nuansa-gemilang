<?= $this->extend('dashboard/layouts/app'); ?>

<?= $this->section('page_title'); ?>
<?= $page_title ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Data Alamat</h1>
<p class="mb-4">Kelola informasi alamat pelanggan melalui tabel interaktif dengan pencarian data.</p>

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

<!-- Data Tables Address -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row mb-3 mb-lg-0 align-items-center justify-content-between">
            <div class="col-12 col-lg-4">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Semua Alamat</h6>
            </div>
            <div class="col-12 col-lg-4 d-flex align-items-center gap-1">
                <form class="d-flex gap-1 flex-grow-1" role="search" method="get">
                    <input class="form-control" type="search" name="q" placeholder="Cari alamat" aria-label="Search" value="<?= esc($_GET['q'] ?? '') ?>" />
                    <button class="btn btn-outline-info" type="submit"><i class="fa fa-faw fa-search"></i></button>
                </form>
                <a href="<?= route_to('user.address.index') ?>" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        fill="currentColor" viewBox="0 0 24 24">
                        <!--Boxicons v3.0 https://boxicons.com | License  https://docs.boxicons.com/free-->
                        <path d="m7.76 14.83-2.83 2.83 1.41 1.41 2.83-2.83 2.12-2.12.71-.71.71.71 1.41 1.42 3.54 3.53 1.41-1.41-3.53-3.54-1.42-1.41-.71-.71 5.66-5.66-1.41-1.41L12 10.59 6.34 4.93 4.93 6.34 10.59 12l-.71.71z"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-baddressed" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Label</th>
                        <th>Nama Penerima</th>
                        <th>Nomor Telepon</th>
                        <th>Alamat Lengkap</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $search = $_GET['q'] ?? '';
                    $filteredAddresses = $addresses;
                    if ($search) {
                        $filteredAddresses = array_filter($addresses, function ($address) use ($search) {
                            return stripos($address['street_address'], $search) !== false;
                        });
                    }
                    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
                    $perPage = 5;
                    $total = count($filteredAddresses);
                    $totalPages = (int) ceil($total / $perPage);
                    $start = ($page - 1) * $perPage;
                    $paginatedAddresses = array_slice($filteredAddresses, $start, $perPage);
                    $index = $start + 1;
                    ?>
                    <?php if (!$paginatedAddresses) : ?>
                        <tr>
                            <td colspan="6" class="text-center">Alamat tidak ditemukan!</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($paginatedAddresses as $address) : ?>
                            <tr>
                                <td><?= $index++ ?></td>
                                <td>
                                    <span class="badge <?php if ($address['label'] === 'Rumah') : ?>text-bg-primary <?php elseif ($address['label'] === 'Kos') : ?>text-bg-secondary <?php else: ?>text-bg-dark<?php endif; ?> text-capitalize">
                                        <?= $address['label'] ?>
                                    </span>
                                </td>
                                <td><?= $address['full_name'] ?? $address['username'] ?></td>
                                <td><?= $address['phone_number'] ?></td>
                                <td><?= $address['street_address'] ?></td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center gap-2">
                                        <button class="btn btn-warning btn-edit"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal"
                                            data-id="<?= $address['id'] ?>"
                                            data-label="<?= $address['label'] ?>"
                                            data-phone="<?= $address['phone_number'] ?>"
                                            data-street="<?= $address['street_address'] ?>">
                                            <i class="fas fa-faw fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-delete" data-id="<?= $address['id'] ?>">
                                            <i class="fas fa-faw fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination mb-0">
                                <li class="page-item<?= $page <= 1 ? ' disabled' : '' ?>">
                                    <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $page - 1 ?>"><i class="fas fa-fw fa-angle-left"></i></a>
                                </li>
                                <?php if ($totalPages) : ?>
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item<?= $i == $page ? ' active' : '' ?>">
                                            <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                <?php else : ?>
                                    <li class="page-item active">
                                        <a class="page-link" href="?q=<?= urlencode($search) ?>&page=1">1</a>
                                    </li>
                                <?php endif; ?>
                                <li class="page-item<?= $page >= $totalPages ? ' disabled' : '' ?>">
                                    <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $page + 1 ?>"><i class="fas fa-fw fa-angle-right"></i></a>
                                </li>
                            </ul>
                        </nav>
                        <button data-bs-toggle="modal" data-bs-target="#createModal" class="btn btn-outline-success d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="me-1">
                                <path d="M19 11h-6V5h-2v6H5v2h6v6h2v-6h6z"></path>
                            </svg>
                            <span>Tambah</span>
                        </button>
                    </div>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="createModalLabel">Tambah alamat baru</h1>
            </div>
            <form id="createForm" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="label" class="col-form-label">Label</label>
                        <input type="text" class="form-control" id="label" name="label">
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="col-form-label">Nomor telepon</label>
                        <div class="input-group">
                            <span class="input-group-text" id="label">+62</span>
                            <input type="number" class="form-control" aria-label="label" aria-describedby="label" name="phone_number">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="street_address" class="col-form-label">Alamat lengkap</label>
                        <textarea class="form-control" id="street_address" name="street_address" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2" id="submitBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text">Tambah</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">Ubah alamat</h1>
            </div>
            <form id="editForm" method="post">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_label" class="col-form-label">Label</label>
                        <input type="text" class="form-control" id="edit_label" name="label">
                    </div>
                    <div class="mb-3">
                        <label for="edit_phone_number" class="col-form-label">Nomor telepon</label>
                        <div class="input-group">
                            <span class="input-group-text" id="label">+62</span>
                            <input type="number" class="form-control" aria-label="label" id="edit_phone_number" aria-describedby="label" name="phone_number">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_street_address" class="col-form-label">Alamat lengkap</label>
                        <textarea class="form-control" id="edit_street_address" name="street_address" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2" id="submitBtnEdit">
                        <span class="spinner-border-edit spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text-edit">Simpan perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('footer_js'); ?>
<script>
    const createForm = document.getElementById('createForm');
    const editForm = document.getElementById('editForm');
    const submitBtn = document.getElementById('submitBtn');
    const spinner = submitBtn.querySelector('.spinner-border');
    const btnText = submitBtn.querySelector('.btn-text');
    const submitBtnEdit = document.getElementById('submitBtnEdit');
    const spinnerEdit = submitBtnEdit.querySelector('.spinner-border-edit');
    const btnTextEdit = submitBtnEdit.querySelector('.btn-text-edit');

    createForm.addEventListener('submit', async function(event) {
        event.preventDefault()

        submitBtn.disabled = true;
        spinner.classList.remove('d-none');
        btnText.textContent = 'Loading...';

        const formData = new FormData(createForm);

        const res = await fetch('<?= route_to('user.address.store') ?>', {
            method: 'POST',
            body: formData
        })

        const result = await res.json();

        if (result.errors) {
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
            btnText.textContent = 'Tambah';

            Object.keys(result.errors).forEach(field => {
                const input = createForm.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.add('is-invalid');
                    let feedback = input.nextElementSibling;
                    if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                        feedback = document.createElement('div');
                        feedback.classList.add('invalid-feedback');
                        input.parentNode.appendChild(feedback);
                    }
                    feedback.textContent = result.errors[field];
                }
            });
        } else if (result.success) {
            alert(result.message);
            location.reload();
        }
    });

    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('edit_id').value = this.dataset.id;
            document.getElementById('edit_label').value = this.dataset.label;
            document.getElementById('edit_phone_number').value = this.dataset.phone;
            document.getElementById('edit_street_address').value = this.dataset.street;
        });
    });

    editForm.addEventListener('submit', async function(event) {
        event.preventDefault();

        submitBtnEdit.disabled = true;
        spinnerEdit.classList.remove('d-none');
        btnTextEdit.textContent = 'Loading...';

        const id = document.getElementById('edit_id').value;
        const formData = new FormData(editForm);

        const response = await fetch(`<?= base_url('dashboard/user/address/update') ?>/${id}`, {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        if (result.success) {
            alert(result.message);
            location.reload();
        } else if (result.errors) {
            submitBtnEdit.disabled = false;
            spinnerEdit.classList.add('d-none');
            btnTextEdit.textContent = 'Simpan perubahan';
            Object.keys(result.errors).forEach(field => {
                const input = editForm.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.add('is-invalid');
                    let feedback = input.nextElementSibling;
                    if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                        feedback = document.createElement('div');
                        feedback.classList.add('invalid-feedback');
                        input.parentNode.appendChild(feedback);
                    }
                    feedback.textContent = result.errors[field];
                }
            });
        }
    });

    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', async function() {
            if (!confirm('Yakin mau hapus alamat ini?')) return;
            const id = this.dataset.id;

            const response = await fetch(`<?= base_url('dashboard/user/address/destroy') ?>/${id}`, {
                method: 'DELETE'
            });

            const result = await response.json();
            if (result.success) {
                alert(result.message);
                location.reload();
            }
        });
    });
</script>
<?= $this->endSection(); ?>