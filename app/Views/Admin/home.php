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
                    <span class="badge bg-danger"><?= $total_mahasiswa > 0 ? round((($total_mahasiswa - $total_hadir) / $total_mahasiswa) * 100) : 0 ?>%</span>
                </div>
                <h3 class="fw-bold mb-1"><?= $total_mahasiswa - $total_hadir ?></h3>
                <p class="text-muted mb-0">Mahasiswa Absen</p>
            </div>
        </div>
    </div>

    <!-- Total Dosen -->
    <div class="col-12 col-sm-6 col-xl-3">
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