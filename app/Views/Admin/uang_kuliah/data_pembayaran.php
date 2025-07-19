<?= $this->extend('admin/layouts.php') ?>
<?= $this->section('content') ?>
<div class="container mt-4">

    <div class="d-flex justify-content-start gap-3 align-items-center mb-4">
        <a href="<?= url_to('admin.uangkuliah.create') ?>" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Tambah Data
        </a>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-import">
            <i class="fas fa-file-import"></i> Import Excel
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center" id="datatables">
            <thead class="table-primary">
                <tr>
                    <th style="width: 1%; white-space: nowrap;">No</th>
                    <th>NPM</th>
                    <th>Nama Lengkap</th>
                    <th style="text-align: center; width: 30%">Jenis Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                <?php for($i = 0; $i < count($uang_kuliah); $i++): ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td style="width: 1%; white-space:nowrap;"><?= $uang_kuliah[$i]['npm'] ?></td>
                        <td align="left"><?= $uang_kuliah[$i]['nama'] ?></td>
                        <td align="center"><?= $uang_kuliah[$i]['jenis_pembayaran'] ?></td>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal-import" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form id="import-form" action="<?= url_to('admin.uangkuliah.import') ?>" method="post" enctype="multipart/form-data">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Import Data Pembayaran</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="alert alert-light">
                            Gunakan fitur Import Excel untuk menambahkan data dalam jumlah yang besar.<br>
                        </p>
                        <input name="file" type="file" class="form form-control" accept=".xls, .xlsx" required>
                        <p class="alert alert-light mt-3">Format didukung: .xls, .xlsx
                        </p>
                    </div>
                    <div id="modal-footer" class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button id="btn-simpan" type="button" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/app.js') ?>"></script>
<script type="application/javascript">
    <?php if (session()->getFlashdata('message')) : ?>
        Swal.fire({
            icon: "success",
            title: "Berhasil!",
            text: "<?= session()->getFlashdata('message') ?>",
            showConfirmButton: false,
            timer: 2000
        });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        Swal.fire({
            icon: "error",
            title: "Import Gagal!",
            text: "<?= session()->getFlashdata('error') ?>",
            showConfirmButton: false,
            timer: 5000
        });
    <?php endif; ?>
</script>
<?= $this->endSection() ?>