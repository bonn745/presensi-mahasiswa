<?= $this->extend('mahasiswa/layouts.php') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --primary-color: #0d6efd;
        --secondary-color: #6c757d;
        --success-color: #198754;
        --info-color: #0dcaf0;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
    }

    .parent-clock {
        display: grid;
        grid-template-columns: repeat(5, auto);
        font-size: clamp(24px, 4vw, 35px);
        font-weight: bold;
        justify-content: center;
        margin: 10px 0;
    }

    .card {
        height: 100%;
        margin-bottom: 1rem;
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .card-header {
        background: var(--primary-color);
        color: white;
        font-weight: 600;
        padding: 1rem;
        border-radius: 8px 8px 0 0;
    }

    .card-body {
        padding: 1.25rem;
    }

    #map {
        height: 480px;
        width: 100%;
        max-width: 800px;
        margin: 20px auto;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .map-container {
        margin-top: 20px;
        text-align: center;
    }

    .map-info {
        margin-bottom: 15px;
        padding: 15px;
        border-radius: 8px;
        background-color: #f8f9fa;
        display: inline-block;
        max-width: 100%;
    }

    .table-responsive {
        margin: 0;
        border-radius: 8px;
        overflow: hidden;
    }

    .table {
        margin-bottom: 0;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        padding: 12px;
    }

    .table td {
        padding: 12px;
        vertical-align: middle;
    }

    .badge {
        padding: 8px 12px;
        font-weight: 500;
        font-size: 0.85rem;
    }

    .alert {
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .info-box {
        background: #fff;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 1rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    /* Responsif untuk mobile */
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }

        .table {
            font-size: 0.9rem;
        }

        .table td,
        .table th {
            padding: 8px;
        }

        /* Perbaikan tampilan waktu di mobile */
        .parent-clock {
            font-size: 20px;
            margin: 5px 0;
            grid-template-columns: repeat(5, auto);
            gap: 2px;
        }

        /* Perbaikan tampilan status di mobile */
        .status-indicator {
            font-size: 0.8rem;
            padding: 4px 8px;
            white-space: nowrap;
        }

        /* Membuat tabel lebih responsif di mobile */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table td {
            white-space: nowrap;
            min-width: 100px;
        }

        /* Memperbaiki tampilan form di mobile */
        .form-select,
        .form-control {
            font-size: 0.9rem;
            padding: 0.375rem 0.75rem;
        }

        /* Memperbaiki tampilan button di mobile */
        .btn {
            font-size: 0.9rem;
            padding: 0.375rem 0.75rem;
        }

        /* Memperbaiki tampilan alert di mobile */
        .alert {
            padding: 0.75rem;
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
        }

        /* Memperbaiki spacing di mobile */
        .row {
            margin-right: -0.5rem;
            margin-left: -0.5rem;
        }

        .col-12,
        .col-md-6 {
            padding-right: 0.5rem;
            padding-left: 0.5rem;
        }

        /* Memperbaiki tampilan icon di mobile */
        .fas {
            font-size: 0.9rem;
            margin-right: 4px;
        }
    }

    /* Tambahan CSS untuk tampilan extra small devices */
    @media (max-width: 576px) {
        .parent-clock {
            font-size: 18px;
            margin: 3px 0;
        }

        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .status-indicator {
            width: 100%;
            text-align: center;
            justify-content: center;
        }

        .table td {
            font-size: 0.85rem;
            padding: 6px;
        }

        /* Memperbaiki tampilan informasi lokasi di mobile */
        .map-info {
            padding: 8px;
            font-size: 0.85rem;
        }

        .map-info .row {
            flex-direction: column;
            gap: 0.5rem;
        }

        .map-info .col-auto {
            width: 100%;
            text-align: center;
        }
    }

    /* Animasi loading untuk presensi */
    .loading-indicator {
        display: none;
        text-align: center;
        padding: 20px;
    }

    .loading-indicator i {
        animation: spin 1s infinite linear;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    /* Gaya untuk status presensi */
    .status-indicator {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.9rem;
    }

    .status-indicator.active {
        background-color: rgba(25, 135, 84, 0.1);
        color: var(--success-color);
    }

    .status-indicator.inactive {
        background-color: rgba(108, 117, 125, 0.1);
        color: var(--secondary-color);
    }
</style>

<div class="container-fluid px-4">
    <div class="row g-3">
        <!-- Jadwal Kelas Hari Ini -->
        <div class="col-12">
            <div class="card">
                <?php
                $hari_en = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                $hari_id = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                $hari_sekarang = str_replace($hari_en, $hari_id, $hari_ini);
                $ada_kelas = false;

                // Cek apakah ada kelas hari ini
                if (!empty($kelas_list)) {
                    foreach ($kelas_list as $kelas) {
                        if ($kelas['hari'] === $hari_sekarang) {
                            $ada_kelas = true;
                            break;
                        }
                    }
                }
                ?>
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-calendar-alt"></i> Jadwal Kelas Hari Ini
                    </div>
                    <div class="status-indicator <?= $ada_kelas ? 'active' : 'inactive' ?>">
                        <i class="fas <?= $ada_kelas ? 'fa-check-circle' : 'fa-info-circle' ?>"></i>
                        <span><?= $ada_kelas ? 'Ada Kelas' : 'Tidak Ada Kelas' ?></span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Mata Kuliah</th>
                                    <th>Dosen Pengampu</th>
                                    <th>Ruangan</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($kelas_list as $kelas): ?>
                                    <?php if ($kelas['hari'] === $hari_sekarang): ?>
                                        <?php $ada_kelas = true; ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-book text-primary me-2"></i>
                                                    <div>
                                                        <strong><?= esc($kelas['nama_matkul']) ?></strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-tie text-info me-2"></i>
                                                    <?= esc($kelas['dosen_pengampu']) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-door-open text-success me-2"></i>
                                                    <?= esc($kelas['ruangan']) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="far fa-clock text-warning me-2"></i>
                                                    <?= date('H:i', strtotime($kelas['jam_masuk'])) ?> -
                                                    <?= date('H:i', strtotime($kelas['jam_pulang'])) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php
                                                // Konversi waktu ke format timestamp
                                                $current_time = strtotime(date('H:i:s'));
                                                $jam_masuk = strtotime($kelas['jam_masuk']);
                                                $jam_pulang = strtotime($kelas['jam_pulang']);

                                                // Debug waktu (uncomment jika perlu)
                                                /*
                                                echo "Current: " . date('H:i:s', $current_time) . "<br>";
                                                echo "Masuk: " . date('H:i:s', $jam_masuk) . "<br>";
                                                echo "Pulang: " . date('H:i:s', $jam_pulang) . "<br>";
                                                */

                                                if ($current_time >= $jam_masuk && $current_time <= $jam_pulang) {
                                                    echo '<span class="badge bg-success">
                                                        <i class="fas fa-play-circle"></i> Sedang Berlangsung
                                                    </span>';
                                                } elseif ($current_time < $jam_masuk) {
                                                    echo '<span class="badge bg-info">
                                                        <i class="fas fa-hourglass-start"></i> Akan Dimulai
                                                    </span>';
                                                } else {
                                                    echo '<span class="badge bg-secondary">
                                                        <i class="fas fa-check-circle"></i> Selesai
                                                    </span>';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                                <?php if (!$ada_kelas): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                                <p class="mb-0">Tidak ada jadwal kelas hari ini</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lokasi Presensi -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-map-marker-alt"></i> Informasi Lokasi Presensi
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-box">
                                <h5 class="text-center mb-3">
                                    <i class="fas fa-history text-primary"></i>
                                    Presensi Hari Ini
                                </h5>
                                <?php
                                $presensi_hari_ini_ada = false;
                                if (!empty($presensi_hari_ini)):
                                    $presensi_hari_ini_ada = true;
                                ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Mata Kuliah</th>
                                                    <th>Ruangan</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($presensi_hari_ini as $presensi): ?>
                                                    <?php
                                                    // Cari data kelas yang sesuai
                                                    $kelas_info = null;
                                                    foreach ($kelas_list as $kelas) {
                                                        if ($kelas['id_matkul'] == $presensi['id_matkul']) {
                                                            $kelas_info = $kelas;
                                                            break;
                                                        }
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><?= $kelas_info ? esc($kelas_info['nama_matkul']) : 'Tidak diketahui' ?></td>
                                                        <td><?= $kelas_info ? esc($kelas_info['ruangan']) : 'Tidak diketahui' ?></td>
                                                        <td>
                                                            <?php if ($presensi['jam_keluar'] == '00:00:00'): ?>
                                                                <span class="badge bg-warning">
                                                                    <i class="fas fa-clock"></i> Belum Presensi Keluar
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="badge bg-success">
                                                                    <i class="fas fa-check-circle"></i> Selesai
                                                                </span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-info">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <span>Belum ada presensi hari ini</span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <h5 class="text-center mb-3">
                                    <i class="fas fa-info-circle text-primary"></i>
                                    Panduan Presensi
                                </h5>
                                <div class="alert alert-success">
                                    <ol class="mb-0 ps-3">
                                        <li class="mb-2">Pilih lokasi presensi yang sesuai</li>
                                        <li class="mb-2">Pastikan GPS aktif dan akurat</li>
                                        <li class="mb-2">Berada dalam radius yang ditentukan</li>
                                        <li>Siapkan kamera untuk foto selfie</li>
                                    </ol>
                                </div>
                                <div class="alert alert-primary mb-0">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-lightbulb me-2"></i>
                                        <span>Anda dapat memilih lokasi yang berbeda untuk setiap sesi presensi</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Presensi Masuk dan Keluar -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fas fa-sign-in-alt"></i> Presensi Masuk
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="fw-bold"><?= date('d F Y') ?></div>
                        <div class="parent-clock">
                            <div id="jam-Masuk">00</div>
                            <div>:</div>
                            <div id="menit-Masuk">00</div>
                            <div>:</div>
                            <div id="detik-Masuk">00</div>
                        </div>
                    </div>

                    <?php if ($cek_presensi < 1) : ?>
                        <form method="POST" action="<?= base_url('mahasiswa/presensi_masuk') ?>" class="mt-3">
                            <div class="form-group mb-3">
                                <?php
                                // Kelompokkan lokasi berdasarkan mata kuliah yang ada jadwalnya hari ini
                                $current_time = strtotime(date('H:i:s'));
                                $available_locations = [];
                                $ada_kelas_hari_ini = false;

                                foreach ($kelas_list as $kelas) {
                                    if ($kelas['hari'] === $hari_ini) {
                                        $ada_kelas_hari_ini = true;
                                        $jam_masuk = strtotime($kelas['jam_masuk']);
                                        $jam_pulang = strtotime($kelas['jam_pulang']);

                                        // Cek apakah kelas sedang berlangsung
                                        $kelas_berlangsung = ($current_time >= $jam_masuk && $current_time <= $jam_pulang);

                                        // Cek apakah sudah presensi untuk mata kuliah ini
                                        $sudah_presensi = in_array($kelas['id_matkul'], $matkul_sudah_presensi);

                                        // Cari lokasi yang sesuai dengan ruangan kelas
                                        foreach ($lokasi_presensi_list as $lok) {
                                            // Bandingkan nama ruangan dari lokasi dengan ruangan kelas
                                            if ($lok['nama_ruangan'] === $kelas['ruangan']) {
                                                $available_locations[] = [
                                                    'id' => $lok['id'],
                                                    'nama_ruangan' => $lok['nama_ruangan'],
                                                    'latitude' => $lok['latitude'],
                                                    'longitude' => $lok['longitude'],
                                                    'radius' => $lok['radius'],
                                                    'matkul' => $kelas['nama_matkul'],
                                                    'id_matkul' => $kelas['id_matkul'],
                                                    'jam' => date('H:i', $jam_masuk) . ' - ' . date('H:i', $jam_pulang),
                                                    'sedang_berlangsung' => $kelas_berlangsung,
                                                    'sudah_presensi' => $sudah_presensi
                                                ];
                                                break;
                                            }
                                        }
                                    }
                                }

                                if ($ada_kelas_hari_ini) : ?>
                                    <select name="id_lokasi_presensi" class="form-select" required id="pilih_lokasi">
                                        <option value="">Pilih Lokasi Presensi</option>
                                        <?php foreach ($available_locations as $loc) : ?>
                                            <?php if ($loc['sedang_berlangsung'] && !$loc['sudah_presensi']) : ?>
                                                <option value="<?= $loc['id'] ?>"
                                                    data-latitude="<?= $loc['latitude'] ?>"
                                                    data-longitude="<?= $loc['longitude'] ?>"
                                                    data-radius="<?= $loc['radius'] ?>"
                                                    data-id-matkul="<?= $loc['id_matkul'] ?>"
                                                    class="text-success fw-bold">
                                                    <?= esc($loc['nama_ruangan']) ?> -
                                                    <?= esc($loc['matkul']) ?>
                                                    (<?= $loc['jam'] ?>)
                                                    [Sedang Berlangsung]
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (empty($available_locations)): ?>
                                        <div class="alert alert-warning mt-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <span>Ada jadwal kelas hari ini, tetapi ruangan tidak ditemukan dalam daftar lokasi presensi.</span>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Debug info -->
                                    <!-- <div class="mt-2">
                                        <small class="text-muted">Debug Info:</small>
                                        <pre id="debug-info" class="small"></pre>
                                    </div> -->

                                    <script>
                                        // Fungsi untuk update debug info
                                        function updateDebugInfo() {
                                            const select = document.getElementById('pilih_lokasi');
                                            const option = select.options[select.selectedIndex];
                                            // const debugInfo = {
                                            //     selectedValue: select.value,
                                            //     idMatkul: option ? option.dataset.idMatkul : null,
                                            //     hiddenFieldValue: document.getElementById('id_matkul').value
                                            // };
                                            // document.getElementById('debug-info').textContent = JSON.stringify(debugInfo, null, 2);
                                        }

                                        // Update debug info saat dropdown berubah
                                        document.getElementById('pilih_lokasi').addEventListener('change', updateDebugInfo);
                                    </script>
                                <?php else: ?>
                                    <div class="alert alert-info mb-0">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <span>Tidak ada jadwal kelas hari ini (<?= $hari_ini ?>)</span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if ($ada_kelas_hari_ini && !empty($available_locations)) : ?>
                                <input type="hidden" name="latitude_kampus" id="latitude_kampus">
                                <input type="hidden" name="longitude_kampus" id="longitude_kampus">
                                <input type="hidden" name="radius" id="radius">
                                <input type="hidden" name="latitude_mahasiswa" id="latitude_mahasiswa">
                                <input type="hidden" name="longitude_mahasiswa" id="longitude_mahasiswa">
                                <input type="hidden" name="tanggal_masuk" value="<?= date('Y-m-d') ?>">
                                <input type="hidden" name="jam_masuk" value="<?= date('H:i:s') ?>">
                                <input type="hidden" name="id_mahasiswa" value="<?= session()->get('id_mahasiswa') ?>">
                                <input type="hidden" name="id_matkul" id="id_matkul">

                                <button type="submit" class="btn btn-primary w-100" id="btn_presensi" disabled>
                                    <i class="fas fa-sign-in-alt me-2"></i> Masuk
                                </button>
                            <?php endif; ?>
                        </form>
                    <?php else : ?>
                        <?php if ($ambil_presensi_masuk && isset($ambil_presensi_masuk['jam_masuk'])) : ?>
                            <div class="alert alert-success mt-3">
                                <h5 class="text-success mb-2">
                                    <i class="fas fa-check-circle"></i> Presensi Masuk Berhasil
                                </h5>
                                <p class="mb-0">
                                    <i class="fas fa-clock"></i> Jam Masuk:
                                    <strong><?= $ambil_presensi_masuk['jam_masuk'] ?></strong>
                                </p>
                            </div>
                        <?php else : ?>
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-exclamation-triangle"></i>
                                Terjadi kesalahan saat mengambil data presensi masuk.
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fas fa-sign-out-alt"></i> Presensi Keluar
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="fw-bold"><?= date('d F Y') ?></div>
                        <div class="parent-clock">
                            <div id="jam-Keluar">00</div>
                            <div>:</div>
                            <div id="menit-Keluar">00</div>
                            <div>:</div>
                            <div id="detik-Keluar">00</div>
                        </div>
                    </div>

                    <?php if ($cek_presensi < 1) : ?>
                        <div class="alert alert-danger mt-3">
                            <h5 class="text-center mb-0">
                                <i class="fas fa-exclamation-circle"></i>
                                Anda belum melakukan presensi masuk!
                            </h5>
                        </div>
                    <?php elseif ($ambil_presensi_masuk && $ambil_presensi_keluar && $ambil_presensi_keluar['jam_keluar'] != '00:00:00') : ?>
                        <div class="alert alert-success mt-3">
                            <h5 class="text-success mb-2">
                                <i class="fas fa-check-circle"></i> Presensi Keluar Berhasil
                            </h5>
                            <p class="mb-0">
                                <i class="fas fa-clock"></i> Jam Keluar:
                                <strong><?= $ambil_presensi_keluar['jam_keluar'] ?></strong>
                            </p>
                        </div>
                    <?php elseif ($ambil_presensi_masuk) : ?>
                        <?php
                        // Debug info
                        log_message('debug', 'Menampilkan form presensi keluar dengan data: ' . json_encode([
                            'id_presensi' => $ambil_presensi_masuk['id'],
                            'nama_ruangan' => $ambil_presensi_masuk['nama_ruangan'],
                            'id_lokasi_presensi' => $ambil_presensi_masuk['id_lokasi_presensi'],
                            'id_matkul' => $ambil_presensi_masuk['id_matkul']
                        ]));
                        ?>
                        <div class="alert alert-info mt-3">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle"></i> Informasi Lokasi Presensi
                            </h6>
                            <hr>
                            <p class="mb-0">
                                <i class="fas fa-building"></i> Lokasi:
                                <strong><?= $ambil_presensi_masuk['nama_ruangan'] ?></strong>
                            </p>
                        </div>

                        <div id="gps-status-keluar" class="alert alert-warning mb-3" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <i class="fas fa-satellite-dish"></i>
                                    Status GPS: <span id="gps-accuracy-text-keluar">Mengecek...</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-warning" onclick="enableHighAccuracy()">
                                    <i class="fas fa-sync-alt"></i> Refresh GPS
                                </button>
                            </div>
                        </div>

                        <form method="POST" action="<?= base_url('mahasiswa/presensi_keluar/' . $ambil_presensi_masuk['id']) ?>">
                            <input type="hidden" name="id_lokasi_presensi" value="<?= $ambil_presensi_masuk['id_lokasi_presensi'] ?>">
                            <input type="hidden" name="id_matkul" value="<?= $ambil_presensi_masuk['id_matkul'] ?>">
                            <input type="hidden" name="latitude_kampus" id="latitude_kampus_keluar" value="<?= $ambil_presensi_masuk['latitude'] ?>">
                            <input type="hidden" name="longitude_kampus" id="longitude_kampus_keluar" value="<?= $ambil_presensi_masuk['longitude'] ?>">
                            <input type="hidden" name="radius" id="radius_keluar" value="<?= $ambil_presensi_masuk['radius'] ?>">
                            <input type="hidden" name="latitude_mahasiswa" id="latitude_mahasiswa_keluar">
                            <input type="hidden" name="longitude_mahasiswa" id="longitude_mahasiswa_keluar">
                            <input type="hidden" name="gps_accuracy" id="gps_accuracy_keluar">
                            <input type="hidden" name="tanggal_keluar" value="<?= date('Y-m-d') ?>">
                            <input type="hidden" name="jam_keluar" value="<?= date('H:i:s') ?>">

                            <button type="submit" class="btn btn-danger w-100" id="btn_presensi_keluar" disabled>
                                <i class="fas fa-sign-out-alt me-2"></i> Keluar
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-danger mt-3">
                            <i class="fas fa-exclamation-circle"></i>
                            Error: Data lokasi presensi tidak ditemukan
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Peta -->
    <div class="map-container mt-4">
        <div class="map-info">
            <h5 class="mb-3"><i class="fas fa-map-marked-alt"></i> Peta Lokasi Presensi</h5>
            <div class="row g-3 justify-content-center">
                <div class="col-auto">
                    <small>
                        <i class="fas fa-circle text-primary"></i> Radius lokasi presensi
                    </small>
                </div>
                <div class="col-auto">
                    <small>
                        <i class="fas fa-circle text-danger"></i> Akurasi GPS Anda
                    </small>
                </div>
                <div class="col-auto">
                    <small>
                        <i class="fas fa-ruler text-warning"></i> Jarak ke lokasi
                    </small>
                </div>
            </div>
        </div>
        <div id="map"></div>
    </div>
</div>

<div class="loading-indicator">
    <i class="fas fa-circle-notch fa-2x"></i>
    <p>Memuat...</p>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="application/javascript">
    <?php if (session()->getFlashdata('message')) : ?>
        Swal.fire({
            icon: "success",
            title: "berhasil!",
            text: "<?= session()->getFlashdata('message') ?>",
            showConfirmButton: false,
            timer: 2000
        });
    <?php endif; ?>
    // Fungsi untuk mengaktifkan GPS akurasi tinggi
    function enableHighAccuracy() {
        console.log('Memulai pengambilan lokasi...');
        if ("geolocation" in navigator) {
            const geoOptions = {
                enableHighAccuracy: true,
                maximumAge: 0,
                timeout: 10000
            };

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    console.log('Berhasil mendapatkan lokasi:', position);
                    showPosition(position);
                },
                function(error) {
                    console.error('Error GPS:', error);
                    showError(error);
                },
                geoOptions
            );
        } else {
            console.error('Geolocation tidak didukung');
            alert('Browser Anda tidak mendukung geolocation.');
        }
    }

    // Jalankan fungsi update waktu setiap detik
    setInterval(updateWaktu, 1000);

    // Update lokasi GPS setiap 3 detik
    setInterval(enableHighAccuracy, 3000);

    // Panggil getLocation dengan akurasi tinggi saat halaman dimuat
    window.onload = function() {
        enableHighAccuracy();
    };

    function updateWaktu() {
        const waktu = new Date();
        const jam = formatWaktu(waktu.getHours());
        const menit = formatWaktu(waktu.getMinutes());
        const detik = formatWaktu(waktu.getSeconds());

        // Pastikan elemen ada sebelum diperbarui untuk menghindari error
        if (document.getElementById("jam-Masuk")) {
            document.getElementById("jam-Masuk").innerHTML = jam;
            document.getElementById("menit-Masuk").innerHTML = menit;
            document.getElementById("detik-Masuk").innerHTML = detik;
        }

        if (document.getElementById("jam-Keluar")) {
            document.getElementById("jam-Keluar").innerHTML = jam;
            document.getElementById("menit-Keluar").innerHTML = menit;
            document.getElementById("detik-Keluar").innerHTML = detik;
        }
    }

    function formatWaktu(waktu) {
        return waktu < 10 ? "0" + waktu : waktu;
    }

    // Event listener untuk dropdown lokasi
    document.getElementById('pilih_lokasi').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const latitude = selectedOption.dataset.latitude;
        const longitude = selectedOption.dataset.longitude;
        const radius = selectedOption.dataset.radius;
        const id_matkul = selectedOption.dataset.idMatkul;

        console.log('Selected matkul:', id_matkul); // Debug log

        document.getElementById('latitude_kampus').value = latitude;
        document.getElementById('longitude_kampus').value = longitude;
        document.getElementById('radius').value = radius;
        document.getElementById('id_matkul').value = id_matkul;

        // Enable tombol presensi jika lokasi sudah dipilih
        document.getElementById('btn_presensi').disabled = !this.value;

        // Update peta dengan lokasi yang dipilih
        if (latitude && longitude) {
            const latMhs = document.getElementById('latitude_mahasiswa').value;
            const longMhs = document.getElementById('longitude_mahasiswa').value;
            const accuracy = document.getElementById('gps_accuracy').value;

            if (latMhs && longMhs) {
                initMap(parseFloat(latMhs), parseFloat(longMhs),
                    parseFloat(latitude), parseFloat(longitude),
                    parseInt(radius), parseFloat(accuracy));
            }
        }
    });

    function showPosition(position) {
        var latitude_mahasiswa = position.coords.latitude;
        var longitude_mahasiswa = position.coords.longitude;
        var accuracy = position.coords.accuracy;

        // Update untuk form masuk
        updateGPSFields('', latitude_mahasiswa, longitude_mahasiswa, accuracy);

        // Update untuk form keluar
        updateGPSFields('_keluar', latitude_mahasiswa, longitude_mahasiswa, accuracy);

        // Update peta untuk lokasi yang sudah dipilih
        updateMapForSelectedLocation(latitude_mahasiswa, longitude_mahasiswa, accuracy);
    }

    function updateGPSFields(suffix, latitude, longitude) {
        console.log('Memulai updateGPSFields untuk ' + (suffix ? 'keluar' : 'masuk'));

        let latitudeField = document.getElementById("latitude_mahasiswa" + suffix);
        let longitudeField = document.getElementById("longitude_mahasiswa" + suffix);
        let btnPresensi = document.getElementById("btn_presensi" + suffix);

        if (!latitudeField || !longitudeField) {
            console.error('Field latitude/longitude tidak ditemukan');
            return;
        }

        // Set nilai GPS
        latitudeField.value = latitude;
        longitudeField.value = longitude;

        // Ambil koordinat lokasi presensi
        const latKampus = document.getElementById('latitude_kampus' + suffix)?.value;
        const longKampus = document.getElementById('longitude_kampus' + suffix)?.value;
        const radius = document.getElementById('radius' + suffix)?.value;

        console.log('Koordinat yang digunakan:', {
            mahasiswa: {
                lat: latitude,
                lng: longitude
            },
            kampus: {
                lat: latKampus,
                lng: longKampus
            },
            radius: radius
        });

        // Hitung jarak ke lokasi presensi
        if (!latKampus || !longKampus || !radius) {
            console.error('Data lokasi kampus tidak lengkap');
            return;
        }

        const jarakKeLokasi = hitungJarak(
            latitude, longitude,
            parseFloat(latKampus), parseFloat(longKampus)
        );

        const isInRadius = jarakKeLokasi <= parseInt(radius);

        // Update status tombol
        if (btnPresensi) {
            btnPresensi.disabled = !isInRadius;
            console.log('Status tombol presensi:', {
                id: btnPresensi.id,
                disabled: btnPresensi.disabled,
                isInRadius: isInRadius
            });
        }

        // Update peta
        if (latKampus && longKampus && radius) {
            initMap(latitude, longitude, parseFloat(latKampus), parseFloat(longKampus), parseInt(radius));
        }
    }

    // Fungsi untuk menghitung jarak antara dua koordinat (dalam meter)
    function hitungJarak(lat1, lon1, lat2, lon2) {
        const R = 6371000; // Radius bumi dalam meter
        const φ1 = lat1 * Math.PI / 180;
        const φ2 = lat2 * Math.PI / 180;
        const Δφ = (lat2 - lat1) * Math.PI / 180;
        const Δλ = (lon2 - lon1) * Math.PI / 180;

        const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
            Math.cos(φ1) * Math.cos(φ2) *
            Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        return R * c;
    }

    function updateMapForSelectedLocation(latitude_mahasiswa, longitude_mahasiswa, accuracy) {
        console.log('Updating map with coordinates:', {
            latitude_mahasiswa,
            longitude_mahasiswa,
            accuracy
        });

        // Untuk presensi masuk
        const latKampus = document.getElementById('latitude_kampus')?.value;
        const longKampus = document.getElementById('longitude_kampus')?.value;
        const radius = document.getElementById('radius')?.value;

        console.log('Presensi masuk coordinates:', {
            latKampus,
            longKampus,
            radius
        });

        // Untuk presensi keluar
        const latKampusKeluar = document.getElementById('latitude_kampus_keluar')?.value;
        const longKampusKeluar = document.getElementById('longitude_kampus_keluar')?.value;
        const radiusKeluar = document.getElementById('radius_keluar')?.value;

        console.log('Presensi keluar coordinates:', {
            latKampusKeluar,
            longKampusKeluar,
            radiusKeluar
        });

        // Gunakan koordinat presensi masuk jika ada
        if (latKampus && longKampus && radius) {
            console.log('Initializing map with presensi masuk coordinates');
            initMap(latitude_mahasiswa, longitude_mahasiswa,
                parseFloat(latKampus), parseFloat(longKampus),
                parseInt(radius), accuracy);
        }
        // Jika tidak ada koordinat presensi masuk, gunakan koordinat presensi keluar
        else if (latKampusKeluar && longKampusKeluar && radiusKeluar) {
            console.log('Initializing map with presensi keluar coordinates');
            initMap(latitude_mahasiswa, longitude_mahasiswa,
                parseFloat(latKampusKeluar), parseFloat(longKampusKeluar),
                parseInt(radiusKeluar), accuracy);
        } else {
            console.warn('No valid coordinates found for map initialization');
        }
    }

    function initMap(latitude_mahasiswa, longitude_mahasiswa, latitude_kampus, longitude_kampus, radius) {
        console.log('Initializing map with parameters:', {
            latitude_mahasiswa,
            longitude_mahasiswa,
            latitude_kampus,
            longitude_kampus,
            radius
        });

        // Validasi parameter
        if (!latitude_mahasiswa || !longitude_mahasiswa || !latitude_kampus || !longitude_kampus || !radius) {
            console.error('Missing required parameters for map initialization');
            return;
        }

        // Hapus peta yang ada jika sudah ada
        const container = L.DomUtil.get('map');
        if (container._leaflet_id) {
            container._leaflet_id = null;
        }

        try {
            var map = L.map('map').setView([latitude_kampus, longitude_kampus], 15);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // Marker lokasi presensi
            var kantorMarker = L.marker([latitude_kampus, longitude_kampus])
                .addTo(map)
                .bindPopup("Lokasi Presensi")
                .openPopup();

            // Lingkaran radius lokasi presensi
            L.circle([latitude_kampus, longitude_kampus], {
                color: 'blue',
                fillColor: '#30f',
                fillOpacity: 0.1,
                radius: radius
            }).addTo(map);

            // Marker lokasi mahasiswa
            var mahasiswaMarker = L.marker([latitude_mahasiswa, longitude_mahasiswa])
                .addTo(map)
                .bindPopup("Lokasi Anda");

            // Garis penghubung
            var polyline = L.polyline([
                [latitude_kampus, longitude_kampus],
                [latitude_mahasiswa, longitude_mahasiswa]
            ], {
                color: 'orange'
            }).addTo(map);

            // Hitung jarak
            let jarak = map.distance(
                [latitude_kampus, longitude_kampus],
                [latitude_mahasiswa, longitude_mahasiswa]
            ).toFixed(2);

            // Tampilkan popup dengan informasi
            L.popup()
                .setLatLng([latitude_mahasiswa, longitude_mahasiswa])
                .setContent(`
                    <strong>Informasi Lokasi:</strong><br>
                    - Jarak ke lokasi: ${jarak} meter<br>
                `)
                .openOn(map);

            // Fit bounds agar semua elemen terlihat
            map.fitBounds(polyline.getBounds(), {
                padding: [50, 50]
            });

            console.log('Map initialized successfully');
        } catch (error) {
            console.error('Error initializing map:', error);
        }
    }

    function showError(error) {
        let gpsStatus = document.getElementById("gps-status");
        let gpsAccuracyText = document.getElementById("gps-accuracy-text");

        gpsStatus.style.display = "block";
        gpsStatus.className = "alert alert-danger mb-3";

        switch (error.code) {
            case error.PERMISSION_DENIED:
                gpsAccuracyText.innerHTML = "Izin GPS ditolak. Mohon aktifkan izin lokasi di browser Anda.";
                break;
            case error.POSITION_UNAVAILABLE:
                gpsAccuracyText.innerHTML = "Informasi lokasi tidak tersedia. Pastikan GPS aktif.";
                break;
            case error.TIMEOUT:
                gpsAccuracyText.innerHTML = "Waktu permintaan lokasi habis. Silakan coba lagi.";
                break;
            case error.UNKNOWN_ERROR:
                gpsAccuracyText.innerHTML = "Terjadi kesalahan yang tidak diketahui.";
                break;
        }
    }

    // Event listener untuk dropdown lokasi keluar
    document.getElementById('pilih_lokasi_keluar')?.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const latitude = selectedOption.dataset.latitude;
        const longitude = selectedOption.dataset.longitude;
        const radius = selectedOption.dataset.radius;

        document.getElementById('latitude_kampus_keluar').value = latitude;
        document.getElementById('longitude_kampus_keluar').value = longitude;
        document.getElementById('radius_keluar').value = radius;

        // Enable tombol presensi jika lokasi sudah dipilih
        document.getElementById('btn_presensi_keluar').disabled = !this.value;

        // Update peta jika koordinat mahasiswa sudah ada
        const latMhs = document.getElementById('latitude_mahasiswa_keluar').value;
        const longMhs = document.getElementById('longitude_mahasiswa_keluar').value;
        if (latMhs && longMhs && latitude && longitude) {
            initMap(parseFloat(latMhs), parseFloat(longMhs), parseFloat(latitude), parseFloat(longitude), parseInt(radius));
        }
    });

    // Inisialisasi peta untuk presensi keluar saat halaman dimuat
    window.addEventListener('load', function() {
        const latKampusKeluar = document.getElementById('latitude_kampus_keluar')?.value;
        const longKampusKeluar = document.getElementById('longitude_kampus_keluar')?.value;
        const radiusKeluar = document.getElementById('radius_keluar')?.value;

        if (latKampusKeluar && longKampusKeluar && radiusKeluar) {
            const latMhs = document.getElementById('latitude_mahasiswa_keluar')?.value;
            const longMhs = document.getElementById('longitude_mahasiswa_keluar')?.value;
            if (latMhs && longMhs) {
                initMap(parseFloat(latMhs), parseFloat(longMhs),
                    parseFloat(latKampusKeluar), parseFloat(longKampusKeluar),
                    parseInt(radiusKeluar));
            }
        }
    });
</script>

<?= $this->endSection() ?>