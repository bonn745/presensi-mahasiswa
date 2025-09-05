<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rekap Presensi Bulanan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: #eee;
        }

        .badge-success {
            color: green;
        }

        .badge-danger {
            color: red;
        }

        .badge-warning {
            color: orange;
        }
    </style>
</head>

<body>

    <h2>REKAP PRESENSI BULANAN</h2>
    <?php

    use Carbon\Carbon;

    Carbon::setLocale('id');
    $tanggal = Carbon::createFromDate($tahun, $bulan, 1);
    ?>
    <p><strong>Bulan:</strong> <?= $tanggal->isoFormat('MMMM YYYY') ?></p>


    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NPM</th>
                <th>Nama Mahasiswa</th>
                <th>Mata Kuliah</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <!-- <th>Total Jam Kuliah</th> -->
                <!-- <th>Keterlambatan</th> -->
                <!-- <th>Cepat Pulang</th> -->
            </tr>
        </thead>
        <tbody>
            <?php if ($rekap_bulanan): ?>
                <?php $no = 1; ?>
                <?php foreach ($rekap_bulanan as $rekap): ?>
                    <?php
                    $jam_masuk = strtotime($rekap['jam_masuk']);
                    $jam_keluar = strtotime($rekap['jam_keluar']);
                    $jam_kerja = $jam_keluar - $jam_masuk;
                    $jam = floor($jam_kerja / 3600);
                    $menit = floor(($jam_kerja % 3600) / 60);

                    $jam_masuk_kantor = isset($rekap['jam_masuk_kampus']) ? strtotime($rekap['jam_masuk_kampus']) : $jam_masuk;
                    $terlambat = $jam_masuk - $jam_masuk_kantor;
                    $jam_terlambat = floor($terlambat / 3600);
                    $menit_terlambat = floor(($terlambat % 3600) / 60);

                    // Hitung pulang cepat hanya jika ada presensi keluar
                    $jam_cepat_pulang = 0;
                    $menit_cepat_pulang = 0;
                    $cepat_pulang = 0;

                    if ($rekap['jam_keluar'] != '00:00:00' && $rekap['tanggal'] != null) {
                        $jam_pulang_kantor = strtotime($rekap['jam_pulang_kampus']);
                        if ($jam_keluar < $jam_pulang_kantor) {
                            $cepat_pulang = $jam_pulang_kantor - $jam_keluar;
                            $jam_cepat_pulang = floor($cepat_pulang / 3600);
                            $menit_cepat_pulang = floor(($cepat_pulang % 3600) / 60);
                        }
                    }
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $rekap['npm'] ?></td>
                        <td><?= $rekap['nama'] ?></td>
                        <td><?= $rekap['nama_matkul'] ?></td>
                        <td><?= Carbon::createFromFormat('Y-m-d',date('Y-m-d', strtotime($rekap['tanggal'])))->locale('id')->translatedFormat('l, j F Y') ?></td>
                        <td><?= date('H:i', strtotime($rekap['jam_masuk'])) ?></td>
                        <td><?= $rekap['jam_keluar'] == '00:00:00' ? '-' : date('H:i', strtotime($rekap['jam_keluar'])) ?></td>
                        <!-- <td><?= $jam . ' jam ' . $menit . ' menit' ?></td>
                        <td>
                            <?php //if ($jam_terlambat > 0 || $menit_terlambat > 0): ?>
                                <span class="badge-danger"><?= $jam_terlambat . ' jam ' . $menit_terlambat . ' menit' ?></span>
                            <?php //else: ?>
                                <span class="badge-success">On Time</span>
                            <?php //endif; ?>
                        </td>
                        <td>
                            <?php //if ($rekap['jam_keluar'] == '00:00:00' || $rekap['tanggal'] == null): ?>
                                <span class="badge-warning">Menunggu Presensi Keluar</span>
                            <?php //elseif ($cepat_pulang > 0): ?>
                                <span class="badge-warning"><?= $jam_cepat_pulang . ' jam ' . $menit_cepat_pulang . ' menit' ?></span>
                            <?php //else: ?>
                                <span class="badge-success">Tepat Waktu</span>
                            <?php //endif; ?>
                        </td> -->
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">Data tidak tersedia</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>

</html>