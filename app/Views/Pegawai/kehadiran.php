<?= $this->extend('pegawai/layouts.php') ?>
<?= $this->section('content') ?>

<?php

use Carbon\Carbon;

Carbon::setLocale('id');
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="<?= base_url('pegawai/kehadiran/create') ?>" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Ajukan
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center" id="datatables">
            <thead class="table-primary">
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 10%;">Tanggal</th>
                    <th style="width: 15%;">Keterangan</th>
                    <th style="width: 20%;">Deskripsi</th>
                    <th style="width: 15%;">File</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>

            <?php if ($kehadiran) : ?>
                <tbody>
                    <?php $no = 1;
                    foreach ($kehadiran as $row) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= Carbon::parse($row['tanggal'])->translatedFormat('d F Y') ?></td>
                            <td><?= ucfirst($row['keterangan']) ?></td>
                            <td><?= $row['deskripsi'] ?></td>
                            <td>
                                <?php if (!empty($row['file'])) : ?>
                                    <a class="badge bg-info text-white" href="<?= base_url('file_kehadiran/' . $row['file']) ?>" target="_blank">
                                        Lihat File
                                    </a>
                                <?php else : ?>
                                    <span class="text-muted">Tidak ada file</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if ($row['status_pengajuan'] === 'Approved') : ?>
                                    <span class="badge bg-success text-white">
                                        <i class="fas fa-check-circle"></i> <?= $row['status_pengajuan'] ?>
                                    </span>
                                <?php elseif ($row['status_pengajuan'] === 'Pending') : ?>
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-hourglass-half"></i> <?= $row['status_pengajuan'] ?>
                                    </span>
                                <?php elseif ($row['status_pengajuan'] === 'Rejected') : ?>
                                    <span class="badge bg-danger text-white">
                                        <i class="fas fa-times-circle"></i> <?= $row['status_pengajuan'] ?>
                                    </span>
                                <?php else : ?>
                                    <span class="badge bg-secondary"><?= $row['status_pengajuan'] ?></span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                <?php if ($row['status_pengajuan'] === 'Pending') : ?>
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        <a href="<?= base_url('pegawai/kehadiran/edit/' . $row['id']) ?>"
                                            class="badge bg-warning text-dark text-decoration-none"
                                            title="Edit Pengajuan" style="cursor: pointer;">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <span class="badge bg-danger text-white btn-delete"
                                            data-id="<?= $row['id'] ?>" style="cursor: pointer;" title="Hapus Pengajuan">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </span>
                                    </div>
                                <?php else : ?>
                                    <span class="badge bg-secondary"><i class="fas fa-lock"></i> Tidak tersedia</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            <?php else : ?>
                <tbody>
                    <tr>
                        <td colspan="7">Data Masih Kosong</td>
                    </tr>
                </tbody>
            <?php endif; ?>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Notifikasi berhasil menambahkan pengajuan kehadiran
    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({
            icon: "success",
            title: "Pengajuan Berhasil",
            text: "<?= session()->getFlashdata('success') ?>",
            showConfirmButton: false,
            timer: 2000
        });
    <?php endif; ?>

    // Notifikasi berhasil mengupdate pengajuan
    <?php if (session()->getFlashdata('updated')) : ?>
        Swal.fire({
            icon: "success",
            title: "Pengajuan Diperbarui",
            text: "<?= session()->getFlashdata('updated') ?>",
            showConfirmButton: false,
            timer: 2000
        });
    <?php endif; ?>

    // Notifikasi berhasil menghapus pengajuan
    <?php if (session()->getFlashdata('deleted')) : ?>
        Swal.fire({
            icon: "success",
            title: "Pengajuan Dihapus",
            text: "<?= session()->getFlashdata('deleted') ?>",
            showConfirmButton: false,
            timer: 2000
        });
    <?php endif; ?>

    // Notifikasi jika terjadi error
    <?php if (session()->getFlashdata('error')) : ?>
        Swal.fire({
            icon: "error",
            title: "Terjadi Kesalahan",
            text: "<?= session()->getFlashdata('error') ?>",
            showConfirmButton: false,
            timer: 2000
        });
    <?php endif; ?>

    // Konfirmasi sebelum menghapus data
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            let kehadiranId = this.getAttribute('data-id');

            Swal.fire({
                title: "Yakin ingin membatalkan pengajuan?",
                text: "Data pengajuan ini akan dihapus secara permanen.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= base_url('pegawai/kehadiran/delete/') ?>" + kehadiranId;
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>