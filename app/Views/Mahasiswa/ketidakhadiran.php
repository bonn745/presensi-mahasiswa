<?= $this->extend('mahasiswa/layouts.php') ?>
<?= $this->section('content') ?>

<?php

use Carbon\Carbon;

Carbon::setLocale('id');
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="<?= base_url('mahasiswa/ketidakhadiran/create') ?>" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Ajukan
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center"  <?php if ($ketidakhadiran) : ?> id="datatables" <?php endif; ?>>
            <thead class="table-primary">
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 20%;">Tanggal</th>
                    <th style="width: 20%;">Mata Kuliah</th>
                    <th style="width: 10%;">Keterangan</th>
                    <th style="width: 20%;">Deskripsi</th>
                    <th style="width: 15%;">File</th>
                    <th style="width: 10%;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($ketidakhadiran) : ?>
                    <?php $no = 1;
                    foreach ($ketidakhadiran as $row) :
                        $tanggal = Carbon::parse($row['tanggal']);
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <?= $tanggal->translatedFormat('l, d F Y') ?>
                            </td>
                            <td><?= ucfirst($row['matkul']) ?></td>
                            <td><?= ucfirst($row['keterangan']) ?></td>
                            <td><?= $row['deskripsi'] ?></td>
                            <td>
                                <?php if (!empty($row['file'])) : ?>
                                    <a class="badge bg-info text-white" href="<?= base_url('file_ketidakhadiran/' . $row['file']) ?>" target="_blank">
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
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td></td>
                        <td colspan="6">Data Masih Kosong</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Notifikasi berhasil menambahkan pengajuan ketidakhadiran
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
</script>

<?= $this->endSection() ?>