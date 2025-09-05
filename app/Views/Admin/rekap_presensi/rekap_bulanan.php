<?= $this->extend('admin/layouts.php') ?>
<?= $this->section('content') ?>

<form class="row g-3 mb-3" method="GET">
    <div class="col-md-4">
        <select name="filter_bulan" class="form-select">
            <option value="">-- Pilih Bulan --</option>
            <?php
            $bulan_array = [
                '01' => 'Januari',
                '02' => 'Februari',
                '03' => 'Maret',
                '04' => 'April',
                '05' => 'Mei',
                '06' => 'Juni',
                '07' => 'Juli',
                '08' => 'Agustus',
                '09' => 'September',
                '10' => 'Oktober',
                '11' => 'November',
                '12' => 'Desember'
            ];
            foreach ($bulan_array as $key => $value) {
                $selected = ($filter_bulan ?? '') == $key ? 'selected' : '';
                echo "<option value='$key' $selected>$value</option>";
            }
            ?>
        </select>
    </div>

    <div class="col-md-4">
        <select name="filter_tahun" class="form-select">
            <option value="">-- Pilih Tahun --</option>
            <?php
            $tahun_sekarang = date('Y');
            for ($i = $tahun_sekarang; $i >= $tahun_sekarang - 5; $i--) {
                $selected = ($filter_tahun ?? '') == $i ? 'selected' : '';
                echo "<option value='$i' $selected>$i</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-md-auto">
        <button type="submit" class="btn btn-primary">Tampilkan</button>
    </div>
    <div class="col-md-auto">
        <button type="submit" name="pdf" class="btn btn-danger">Export PDF</button>
    </div>

</form>

<?php

use Carbon\Carbon;

// Jika $tanggal belum ada, tapi filter bulan dan tahun ada, kita buat manual
if (empty($tanggal) && !empty($filter_bulan) && !empty($filter_tahun)) {
    $tanggal = $filter_tahun . '-' . $filter_bulan . '-01'; // default ke tanggal 1
}
?>
<p><strong>Menampilkan data:</strong>
    <?= Carbon::parse($tanggal ?? Carbon::now())->locale('id')->isoFormat('MMMM YYYY') ?>
</p>

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover text-center align-middle">
        <thead class="table-primary">
            <tr>
                <th>No</th>
                <th>NPM</th>
                <th>Nama Mahasiswa</th>
                <th>Mata Kuliah</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <!-- <th>Total Jam Kuliah</th>
                <th>Total Keterlambatan</th>
                <th>Total Cepat Pulang</th> -->
            </tr>
        </thead>
        <tbody>
            <?php if ($rekap_bulanan) : ?>
                <?php $no = 1; ?>
                <?php foreach ($rekap_bulanan as $rekap) : ?>
                    <?php
                    $timestamp_jam_masuk = strtotime($rekap['jam_masuk']);
                    $timestamp_jam_keluar = strtotime($rekap['jam_keluar']);
                    $selisih = $timestamp_jam_keluar - $timestamp_jam_masuk;
                    $jam = floor($selisih / 3600);
                    $menit = floor(($selisih % 3600) / 60);

                    // Hitung keterlambatan
                    $jam_masuk_real = strtotime($rekap['jam_masuk']);
                    $jam_masuk_kampus = isset($rekap['jam_masuk_kampus']) ? strtotime($rekap['jam_masuk_kampus']) : $jam_masuk_real;
                    $selisih_terlambat = $jam_masuk_real - $jam_masuk_kampus;
                    $jam_terlambat = floor($selisih_terlambat / 3600);
                    $menit_terlambat = floor(($selisih_terlambat % 3600) / 60);

                    // Hitung pulang cepat hanya jika ada presensi keluar
                    $jam_cepat_pulang = 0;
                    $menit_cepat_pulang = 0;
                    $selisih_cepat_pulang = 0;

                    if ($rekap['jam_keluar'] != '00:00:00' && $rekap['tanggal'] != null) {
                        $jam_keluar_real = strtotime($rekap['jam_keluar']);
                        $jam_pulang_kampus = strtotime($rekap['jam_pulang_kampus']);

                        if ($jam_keluar_real < $jam_pulang_kampus) {
                            $selisih_cepat_pulang = $jam_pulang_kampus - $jam_keluar_real;
                            $jam_cepat_pulang = floor($selisih_cepat_pulang / 3600);
                            $menit_cepat_pulang = floor(($selisih_cepat_pulang % 3600) / 60);
                        }
                    }

                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $rekap['npm'] ?></td>
                        <td><?= $rekap['nama'] ?></td>
                        <td><?= $rekap['nama_matkul'] ?></td>
                        <td><?= \Carbon\Carbon::parse($rekap['tanggal'])->locale('id')->isoFormat('D MMMM YYYY') ?></td>
                        <td><?= $rekap['jam_masuk'] ?></td>
                        <td><?= $rekap['jam_keluar'] == '00:00:00' ? '-' : date('H:i', strtotime($rekap['jam_keluar'])) ?></td>
                        <!-- <td>
                            <?php if ($rekap['jam_keluar'] == '00:00:00') : ?>
                                0 Jam 0 Menit
                            <?php else : ?>
                                <?= $jam . ' Jam ' . $menit . ' Menit' ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($jam_terlambat < 0 || $menit_terlambat < 0) : ?>
                                <span class="badge bg-success">On Time</span>
                            <?php else : ?>
                                <span class="badge bg-danger">
                                    <?= $jam_terlambat . ' Jam ' . $menit_terlambat . ' Menit' ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($rekap['jam_keluar'] == '00:00:00' || $rekap['tanggal'] == null) : ?>
                                <span class="badge bg-warning">Tidak Ada Presensi Keluar</span>
                            <?php elseif ($selisih_cepat_pulang > 0) : ?>
                                <span class="badge bg-warning">
                                    <?= $jam_cepat_pulang . ' Jam ' . $menit_cepat_pulang . ' Menit' ?>
                                </span>
                            <?php else : ?>
                                <span class="badge bg-success">Tepat Waktu</span>
                            <?php endif; ?>
                        </td> -->
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="9" class="text-muted">Data Tidak Tersedia</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>