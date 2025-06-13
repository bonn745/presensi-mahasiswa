<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="<?= base_url('admin/data_mahasiswa/create') ?>" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Tambah Data
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center" id="datatables">
            <thead class="table-primary">
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 10%;">NIM</th>
                    <th style="width: 15%;">Nama Lengkap</th>
                    <th style="width: 15%;">Jenis Kelamin</th>
                    <th style="width: 15%;">Alamat</th>
                    <th style="width: 10%;">No Handphone</th>
                    <th style="width: 10%;">Semester</th>
                    <th style="width: 10%;">Jurusan</th>
                    <th style="width: 10%;">Foto</th>
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
                        <td><?= $mhs['alamat'] ?></td>
                        <td><?= $mhs['no_handphone'] ?></td>
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
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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