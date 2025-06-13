<?= $this->extend('admin/layouts') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="<?= base_url('admin/kelas/create') ?>" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Tambah Jadwal Kelas
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center" id="datatables">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Mata Kuliah</th>
                    <th>Hari</th>
                    <th>Ruangan</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>

                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach ($kelas as $kelas_item): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $kelas_item['matkul'] ?> <!-- Bisa diubah untuk nama mata kuliah jika perlu --></td>
                        <td><?= $kelas_item['hari'] ?></td>
                        <td><?= $kelas_item['ruangan'] ?></td>
                        <td><?= $kelas_item['jam_masuk'] ?></td>
                        <td><?= $kelas_item['jam_pulang'] ?></td>

                        <td>
                            <a href="<?= base_url('admin/kelas/edit/' . $kelas_item['id']) ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="<?= $kelas_item['id'] ?>">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </a>
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
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Mencegah tautan default
            let pegawaiId = this.getAttribute('data-id');

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
                    window.location.href = "<?= base_url('admin/kelas/delete/') ?>" + pegawaiId;
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>