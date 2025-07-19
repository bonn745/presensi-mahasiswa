<?= $this->extend('dosen/layouts.php') ?>

<?= $this->section('content') ?>

<?php date_default_timezone_set('Asia/Jakarta'); ?>
<!-- Header Section -->
<div class="mb-4">
    <p class="text-muted mb-0">Data Presensi Tanggal <?= date('d F Y') ?></p>
</div>

<!-- Statistik Cards -->
<div class="row g-3">
    <!-- Total Mahasiswa -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="fas fa-users fa-lg text-primary"></i>
                    </div>
                    <span class="badge bg-success">Total</span>
                </div>
                <h3 class="fw-bold mb-1"><?= $total_mahasiswa ?></h3>
                <p class="text-muted mb-0">Total Mahasiswa</p>
            </div>
        </div>
    </div>

    <!-- Mahasiswa Hadir -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="fas fa-check-circle fa-lg text-success"></i>
                    </div>
                    <span class="badge bg-success"><?= round(($total_hadir / $total_mahasiswa) * 100) ?>%</span>
                </div>
                <h3 class="fw-bold mb-1"><?= $total_hadir ?></h3>
                <p class="text-muted mb-0">Mahasiswa Hadir</p>
            </div>
        </div>
    </div>

    <!-- Mahasiswa Absen -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                        <i class="fas fa-times-circle fa-lg text-danger"></i>
                    </div>
                    <span class="badge bg-danger"><?= round((($total_mahasiswa - $total_hadir) / $total_mahasiswa) * 100) ?>%</span>
                </div>
                <h3 class="fw-bold mb-1"><?= $total_mahasiswa - $total_hadir ?></h3>
                <p class="text-muted mb-0">Mahasiswa Absen</p>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive mt-5">
    <table class="table table-striped table-bordered text-center" id="datatables">
        <thead class="table-primary">
            <tr>
                <th rowspan="2" style="width:1%; white-space:nowrap;">No</th>
                <th rowspan="2">Mata Kuliah</th>
                <th colspan="3" style="text-align: center;">Jadwal</th>
            </tr>
            <tr>
                <th style="text-align: center;">Hari</th>
                <th style="text-align: center;">Masuk</th>
                <th style="text-align: center;">Keluar</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($data_matakuliah as $dm) : ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td align="left"><?= $dm['matkul'] ?></td>
                    <td><?= $dm['hari'] ?></td>
                    <td><?= date('H:i',strtotime($dm['jam_masuk'])) ?></td>
                    <td><?= date('H:i',strtotime($dm['jam_pulang'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- CSS untuk animasi statistik -->
<style>
    .card {
        transition: transform 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .rounded-circle {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .badge {
        padding: 0.5em 0.75em;
    }
</style>
<?= $this->endSection() ?>