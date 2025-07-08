<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>

<div class="container mt-4">

    <div class="d-flex justify-content-start gap-3 align-items-center mb-4">
        <a href="<?= base_url('admin/data_mahasiswa/create') ?>" class="btn btn-success">
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
                    <th style="width: 5%;">No</th>
                    <th style="width: 10%;">NIM</th>
                    <th style="width: 15%;">Nama Lengkap</th>
                    <th style="width: 15%;">Jenis Kelamin</th>
                    <th style="width: 10%;">Semester</th>
                    <th style="width: 10%;">Jurusan</th>
                    <th style="width: 10%;">Foto</th>
                    <th style="width: 15%;">Nama Ortu</th>
                    <th style="width: 10%;">Kontak Ortu</th>
                    <th style="width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($mahasiswa as $mhs) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $mhs['nim'] ?></td>
                        <td><?= $mhs['nama'] ?></td>
                        <td><?= $mhs['jenis_kelamin'] ?></td>
                        <td><?= $mhs['semester'] ?></td>
                        <td><?= $mhs['jurusan'] ?></td>
                        <td>
                            <?php if (!empty($mhs['foto'])) : ?>
                                <img src="<?= base_url('profile/' . $mhs['foto']) ?>"
                                    alt="Foto Mahasiswa"
                                    style="width: 60px; height: 60px; object-fit: cover;">
                            <?php else : ?>
                                <span class="text-muted">Tidak Ada Foto</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $mhs['nama_ortu'] ?></td>
                        <td><?= $mhs['nohp_ortu'] ?></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center">
                                <a href="<?= base_url('admin/data_mahasiswa/detail/' . $mhs['id']) ?>" class="btn btn-sm btn-info me-1">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                                <a href="<?= base_url('admin/data_mahasiswa/edit/' . $mhs['id']) ?>" class="btn btn-sm btn-warning me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-danger btn-delete" data-id="<?= $mhs['id'] ?>">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal-import" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form id="import-form" action="<?= url_to('admin.mahasiswa.import') ?>" method="post" enctype="multipart/form-data">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Import Excel</h1>
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
<script>
    // Notifikasi saat data berhasil ditambahkan atau dihapus
    <?php if (session()->getFlashdata('success_add')) : ?>
        Swal.fire({
            icon: "success",
            title: "Berhasil!",
            text: "<?= session()->getFlashdata('success_add') ?>",
            showConfirmButton: false,
            timer: 2000
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('success_delete')) : ?>
        Swal.fire({
            icon: "success",
            title: "Terhapus!",
            text: "<?= session()->getFlashdata('success_delete') ?>",
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
            timer: 2000
        });
    <?php endif; ?>

    // Konfirmasi sebelum menghapus data
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            let mahasiswaId = this.getAttribute('data-id');

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= base_url('admin/data_mahasiswa/delete/') ?>" + mahasiswaId;
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>