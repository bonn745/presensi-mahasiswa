<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="<?= base_url('admin/data_pegawai/create') ?>" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Tambah Data
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center" id="datatables">
            <thead class="table-primary">
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 10%;">NIP</th>
                    <th style="width: 15%;">Nama</th>
                    <th style="width: 15%;">Alamat</th>
                    <th style="width: 10%;">No Handphone</th>
                    <th style="width: 10%;">Jabatan</th>
                    <th style="width: 10%;">Foto</th>
                    <th style="width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($pegawai as $peg) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $peg['nip'] ?></td>
                        <td><?= $peg['nama'] ?></td>
                        <td><?= $peg['alamat'] ?></td>
                        <td><?= $peg['no_handphone'] ?></td>
                        <td><?= $peg['jabatan'] ?></td>
                        <td>
                            <?php if (!empty($peg['foto'])) : ?>
                                <img src="<?= base_url('profile/' . $peg['foto']) ?>"
                                    alt="Foto Pegawai"
                                    style="width: 60px; height: 60px; object-fit: cover;">
                            <?php else : ?>
                                <span class="text-muted">Tidak Ada Foto</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center">
                                <a href="<?= base_url('admin/data_pegawai/detail/' . $peg['id']) ?>" class="btn btn-sm btn-info me-1">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                                <a href="<?= base_url('admin/data_pegawai/edit/' . $peg['id']) ?>" class="btn btn-sm btn-warning me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-danger btn-delete" data-id="<?= $peg['id'] ?>">
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
                    window.location.href = "<?= base_url('admin/data_pegawai/delete/') ?>" + pegawaiId;
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>