<html>

<head>
    <style>
        .table {
            width: 100%;
        }

        .border {
            border: solid 1px black;
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    <table>
        <tbody>
            <tr>
                <th scope="row" align="left">Program Studi</th>
                <td style="width: 1%; white-space: nowrap">:</td>
                <td><?= $program_studi ?></td>
            </tr>
            <tr>
                <th scope="row" align="left">Dosen</th>
                <td style="width: 1%; white-space: nowrap">:</td>
                <td><?= $nama_dosen ?></td>
            </tr>
            <tr>
                <th scope="row" align="left">Mata Kuliah</th>
                <td style="width: 1%; white-space: nowrap">:</td>
                <td><?= $mata_kuliah ?></td>
            </tr>
            <?php if (!$pertemuan_is_empty) : ?>
                <tr>
                    <th scope="row" align="left">Pertemuan</th>
                    <td style="width: 1%; white-space: nowrap">:</td>
                    <td><?= $pertemuan_text ?></td>
                </tr>
                <tr>
                    <th scope="row" align="left">Tanggal</th>
                    <td style="width: 1%; white-space: nowrap">:</td>
                    <td><?= $tanggal ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <br>
    <?php if ($pertemuan_is_empty) : ?>
        <table class="table border">
            <thead>
                <tr class="border">
                    <th class="border">No</th>
                    <th class="border">NPM</th>
                    <th class="border">Nama</th>
                    <?php foreach ($presensi[0]['kehadiran'] as $p) : ?>
                        <th class="border"><?= $p['index'] ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php $index = 1;
                foreach ($presensi as $mhs) : ?>
                    <tr class="border">
                        <th class="border"><?= $index ?></th>
                        <td class="border" style="width: 1%; white-space: nowrap;"><?= $mhs['npm_mahasiswa'] ?></td>
                        <td class="border"><?= $mhs['nama_mahasiswa'] ?></td>
                        <?php foreach ($mhs['kehadiran'] as $kehadiran) : ?>
                            <?php if (!$kehadiran['izin']) : ?>
                                <td class="border" align="center"><?= $kehadiran['jam_masuk'] != '-' && $kehadiran['jam_masuk'] != '00:00:00' ? 'H' : '-' ?></td>
                            <?php else : ?>
                                <td class="border" align="center"><?= substr($kehadiran['keterangan'], 0, 1) ?></td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                <?php $index++;
                endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <table class="table border">
            <thead>
                <tr class="border">
                    <th class="border" style="width: 1%; white-space: nowrap;">No</th>
                    <th class="border">NPM</th>
                    <th class="border">Nama</th>
                    <th class="border">Jam Masuk</th>
                    <th class="border">Jam Keluar</th>
                    <th class="border">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php $index = 1;
                foreach ($presensi as $p) : ?>
                    <tr class="border">
                        <th scope="row" class="border">
                            <?= $index ?>
                        </th>
                        <td class="border" style="width: 1%; white-space: nowrap;">
                            <?= $p['npm_mahasiswa'] ?>
                        </td>
                        <td class="border">
                            <?= $p['nama_mahasiswa'] ?>
                        </td>
                        <td class="border" align="center">
                            <?= $p['jam_masuk'] ?>
                        </td>
                        <td class="border" align="center">
                            <?= $p['jam_keluar'] ?>
                        </td>
                        <td class="border" align="center">
                            <?= $p['keterangan'] ?>
                        </td>
                    </tr>
                <?php $index++;
                endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>

</html>