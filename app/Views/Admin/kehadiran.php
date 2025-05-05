<?= $this->extend('admin/layouts.php') ?>
<?= $this->section('content') ?>

<?php

use Carbon\Carbon;

Carbon::setLocale('id');
?>

<div class="table-responsive">
    <table class="table table-striped table-bordered text-center" id="datatables">
        <thead class="table-primary">
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 10%;">Nip</th>
                <th style="width: 10%;">Tanggal</th>
                <th style="width: 10%;">Nama</th>
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
                        <td><?= esc($row['nip']); ?></td>
                        <td><?= Carbon::parse($row['tanggal'])->translatedFormat('d F Y') ?></td>
                        <td><?= esc($row['nama']); ?></td>
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
                                    <a class="badge bg-success text-decoration-none" href="<?= base_url('admin/approved_kehadiran/' . $row['id']) ?>" title="Setujui Pengajuan">
                                        <i class="fas fa-check-circle"></i> Approved
                                    </a>
                                    <a class="badge bg-danger text-decoration-none" href="<?= base_url('admin/rejected_kehadiran/' . $row['id']) ?>" title="Tolak Pengajuan">
                                        <i class="fas fa-times-circle"></i> Rejected
                                    </a>
                                </div>
                            <?php else : ?>
                                <a class="badge bg-danger text-decoration-none" href="<?= base_url('admin/delete_kehadiran/' . $row['id']) ?>" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')" title="Hapus Data">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
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

<?= $this->endSection() ?>