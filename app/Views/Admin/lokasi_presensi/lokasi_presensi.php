<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="<?= base_url('admin/lokasi_presensi/create') ?>" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Tambah Data
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="datatables">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Nama Ruangan</th>
                    <th>Alamat Lokasi</th>
                    <th>Tipe Lokasi</th>
                    <th>Hari Kuliah</th>
                    <th>Mata Kuliah</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($lokasi_presensi as $lok) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $lok['nama_ruangan'] ?></td>
                        <td><?= $lok['alamat_lokasi'] ?></td>
                        <td><?= $lok['tipe_lokasi'] ?></td>
                        <td><?= $lok['jadwal_kuliah'] ?></td>
                        <td><?= $lok['matkul'] ?></td>
                        <td><?= $lok['jam_masuk'] ?></td>
                        <td><?= $lok['jam_pulang'] ?></td>

                        <td class="text-center">

                            <a href="<?= base_url('admin/lokasi_presensi/detail/' . $lok['id']) ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-info-circle"></i>
                            </a>

                            <a href="<?= base_url('admin/lokasi_presensi/edit/') . $lok['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-danger btn-delete" data-id="<?= $lok['id'] ?>">
                                <i class="fas fa-trash-alt"></i>
                            </button>
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
            let jabatanId = this.getAttribute('data-id');

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
                    window.location.href = "<?= base_url('admin/lokasi_presensi/delete/') ?>" + jabatanId;
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>