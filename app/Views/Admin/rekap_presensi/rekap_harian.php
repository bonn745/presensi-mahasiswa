<?= $this->extend('admin/layouts.php') ?>
<?= $this->section('content') ?>

<div class="card shadow-sm p-4 mb-4">
    <div class="card-body">
        <form class="row g-3 mb-3">
            <div class="col-md-4">
                <input type="date" class="form-control" name="filter_tanggal">
            </div>
            <div class="col-md-4">
                <select name="filter_matkul" id="filter_matkul" class="form-control select2">
                    <option value="0" selected disabled>--Pilih Mata Kuliah--</option>
                    <?php foreach ($matkul as $x) : ?>
                        <option value="<?= $x['id'] ?>"><?= $x['matkul'] . ' - ' . $x['nama_dosen'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </div>
            <div class="col-md-auto">
                <button type="submit" name="excel" class="btn btn-success">Export Excel</button>
            </div>
        </form>

        <p><strong>Menampilkan data:</strong>
            <?php if ($tanggal) : ?>
                <!-- Gunakan Carbon untuk memformat tanggal -->
                <?= \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM YYYY') ?>
            <?php else : ?>
                <?= \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') ?>
            <?php endif; ?>
            <?php if ($nama_matkul) : ?>
                <?= ' | '.$nama_matkul ?>
            <?php endif; ?>

        </p>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover text-center align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>Mata Kuliah</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Total Jam Kuliah</th>
                        <th>Total Keterlambatan</th>
                        <th>Total Cepat Pulang</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($rekap_harian) : ?>
                        <?php $no = 1; ?>
                        <?php foreach ($rekap_harian as $rekap) : ?>
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

                            if ($rekap['jam_keluar'] != '00:00:00') {
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
                                <td><?= $rekap['nama'] ?></td>
                                <td><?= $rekap['nama_matkul'] ?></td>
                                <td><?= \Carbon\Carbon::parse($rekap['tanggal'])->locale('id')->isoFormat('D MMMM YYYY') ?></td>
                                <td><?= date('H:i',strtotime($rekap['jam_masuk'])) ?></td>
                                <td><?= date('H:i', strtotime($rekap['jam_keluar'])) ?></td>
                                <td>
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
                                    <?php if ($rekap['jam_keluar'] == '00:00:00') : ?>
                                        <span class="badge bg-warning">Menunggu Presensi Keluar</span>
                                    <?php elseif ($selisih_cepat_pulang > 0) : ?>
                                        <span class="badge bg-warning">
                                            <?= $jam_cepat_pulang . ' Jam ' . $menit_cepat_pulang . ' Menit' ?>
                                        </span>
                                    <?php else : ?>
                                        <span class="badge bg-success">Tepat Waktu</span>
                                    <?php endif; ?>
                                </td>
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
    </div>
</div>

<?= $this->endSection() ?>