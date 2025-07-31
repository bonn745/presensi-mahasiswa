<?= $this->extend('admin/layouts.php') ?>

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
                    <span class="badge bg-success"><?= $total_mahasiswa > 0 ? round(($total_hadir / $total_mahasiswa) * 100) : 0 ?>%</span>
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
                    <span class="badge bg-danger"><?= $total_mahasiswa > 0 ? round(((($total_mahasiswa - $total_izin) - $total_hadir) / $total_mahasiswa) * 100) : 0 ?>%</span>
                </div>
                <h3 class="fw-bold mb-1"><?= $total_mahasiswa > 0 ? ($total_mahasiswa - $total_izin) - $total_hadir : 0 ?></h3>
                <p class="text-muted mb-0">Mahasiswa Absen</p>
            </div>
        </div>
    </div>

    <!-- Mahasiswa Izin -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="fas fa-exclamation-circle fa-lg text-warning"></i>
                    </div>
                    <span class="badge bg-warning"><?= $total_mahasiswa > 0 ? ($total_izin > 0 ? round(($total_izin / $total_mahasiswa) * 100) : 0) : 0 ?>%</span>
                </div>
                <h3 class="fw-bold mb-1"><?= $total_mahasiswa > 0 ? ($total_izin ?? "0") : 0 ?></h3>
                <p class="text-muted mb-0">Mahasiswa Izin/Sakit</p>
            </div>
        </div>
    </div>

    <!-- Total Dosen -->
    <!-- <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3">
                        <i class="fas fa-chalkboard-teacher fa-lg text-info"></i>
                    </div>
                    <span class="badge bg-info">Total</span>
                </div>
                <h3 class="fw-bold mb-1"><?= $total_dosen ?></h3>
                <p class="text-muted mb-0">Total Dosen</p>
            </div>
        </div>
    </div> -->
    <hr>
    Mahasiswa Izin/Tanpa Keterangan
    <div class="col-12 col-sm-12 col-xl-12">
        <div class="card h-100 shadow-sm">
            <table class="table table-striped table-bordered text-center"">
                <thead>
                    <tr>
                        <th style="text-align:center">No</th>
                        <th>NPM</th>
                        <th>Nama</th>
                        <th>Mata Kuliah</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $index = 1;
                    if (count($data_mahasiswa_izin) > 0 || count($data_mahasiswa_absen) > 0) :
                        foreach ($data_mahasiswa_izin as $di) :
                    ?>
                            <tr>
                                <td align="center"><?= $index; ?></td>
                                <td><?= $di['npm']; ?></td>
                                <td><?= $di['nama']; ?></td>
                                <td><?= $di['nama_matkul']; ?></td>
                                <td><?= $di['keterangan']; ?></td>
                            </tr>
                        <?php $index++;
                        endforeach; ?>
                        <?php foreach ($data_mahasiswa_absen as $da) : ?>
                            <?php foreach ($da as $x) : ?>
                                <tr>
                                    <td align="center"><?= $index; ?></td>
                                    <td><?= $x['npm']; ?></td>
                                    <td><?= $x['nama']; ?></td>
                                    <td><?= $x['nama_matkul']; ?></td>
                                    <td>Tanpa Keterangan</td>
                                </tr>
                            <?php $index++;
                            endforeach; ?>
                        <?php
                        endforeach;
                    else :
                        ?>
                        <tr>
                            <td colspan="5" align="center"> Tidak ada data.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
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