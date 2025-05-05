<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>

<div class="card col-md-6">
    <div class="card-body">
        <form method="POST" action="<?= base_url('admin/lokasi_presensi/update/' . $lokasi_presensi['id']) ?>">
            <?= csrf_field() ?>
            <!-- Input untuk Nama Lokasi -->
            <div class="input-style-1">
                <label for="nama_lokasi">Nama Lokasi</label>
                <input type="text" class="form-control<?= ($validation->hasError('nama_lokasi')) ? ' is-invalid' : '' ?>"
                    name="nama_lokasi" placeholder="Nama Lokasi" value="<?= old('nama_lokasi', $lokasi_presensi['nama_lokasi']) ?>" />
                <div class="invalid-feedback"><?= $validation->getError('nama_lokasi') ?></div>
            </div>

            <!-- Input untuk Alamat Lokasi -->
            <div class="input-style-1">
                <label for="alamat_lokasi">Alamat Lokasi</label>
                <textarea id="alamat_lokasi" class="form-control<?= ($validation->hasError('alamat_lokasi')) ? ' is-invalid' : '' ?>"
                    name="alamat_lokasi" placeholder="Alamat Lokasi" rows="4"><?= old('alamat_lokasi', $lokasi_presensi['alamat_lokasi']) ?></textarea>
                <div class="invalid-feedback"><?= $validation->getError('alamat_lokasi') ?></div>
            </div>

            <!-- Input untuk Tipe Lokasi -->
            <div class="input-style-1">
                <label for="tipe_lokasi">Tipe Lokasi</label>
                <select id="tipe_lokasi" class="form-control<?= ($validation->hasError('tipe_lokasi')) ? ' is-invalid' : '' ?>"
                    name="tipe_lokasi">
                    <option value="" disabled selected>Pilih Tipe Lokasi</option>

                    <?php
                    $tipeLokasiOptions = [
                        "Tipe 1 - Kantor Utama",
                        "Tipe 1 - Ruang Rapat",
                        "Tipe 1 - Lobi",
                        "Tipe 2 - Bagian Kepegawaian",
                        "Tipe 2 - Bagian Keuangan",
                        "Tipe 2 - Bagian Umum",
                        "Tipe 3 - Area Parkir",
                        "Tipe 3 - Kantin",
                        "Tipe 3 - Pos Keamanan"
                    ];

                    foreach ($tipeLokasiOptions as $option) {
                        $selected = (old('tipe_lokasi', $lokasi_presensi['tipe_lokasi']) == $option) ? 'selected' : '';
                        echo "<option value=\"$option\" $selected>$option</option>";
                    }
                    ?>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('tipe_lokasi') ?></div>
            </div>

            <!-- Input untuk Jam Kerja -->
            <div class="input-style-1 mb-3">
                <label for="jadwal_kerja" class="font-weight-bold">Jadwal Kerja</label>
            </div>
            <?php
            $jadwalKerja = old('jadwal_kerja', $lokasi_presensi['jadwal_kerja'] ?? '');
            ?>
            <div class="form-check form-check-inline <?= ($validation->hasError('jadwal_kerja')) ? 'is-invalid' : '' ?> mb-2">
                <input class="form-check-input" type="radio" name="jadwal_kerja" value="Senin-Kamis"
                    <?= $jadwalKerja == 'Senin-Kamis' ? 'checked' : '' ?>>
                <label class="form-check-label">Senin - Kamis</label>
            </div>
            <div class="form-check form-check-inline <?= ($validation->hasError('jadwal_kerja')) ? 'is-invalid' : '' ?> mb-2">
                <input class="form-check-input" type="radio" name="jadwal_kerja" value="Jumat"
                    <?= $jadwalKerja == 'Jumat' ? 'checked' : '' ?>>
                <label class="form-check-label">Jumat</label>
            </div>
            <div class="invalid-feedback"><?= $validation->getError('jadwal_kerja') ?></div>

            <!-- Input untuk Latitude -->
            <div class="input-style-1 mt-3">
                <label for="latitude">Latitude</label>
                <input type="text" class="form-control<?= ($validation->hasError('latitude')) ? ' is-invalid' : '' ?>"
                    name="latitude" placeholder="Latitude" value="<?= old('latitude', $lokasi_presensi['latitude']) ?>" />
                <div class="invalid-feedback"><?= $validation->getError('latitude') ?></div>
            </div>

            <!-- Input untuk Longitude -->
            <div class="input-style-1">
                <label for="longitude">Longitude</label>
                <input type="text" class="form-control<?= ($validation->hasError('longitude')) ? ' is-invalid' : '' ?>"
                    name="longitude" placeholder="Longitude" value="<?= old('longitude', $lokasi_presensi['longitude']) ?>" />
                <div class="invalid-feedback"><?= $validation->getError('longitude') ?></div>
            </div>

            <!-- Input untuk Radius -->
            <div class="input-style-1">
                <label for="radius">Radius (meter)</label>
                <input type="number" class="form-control<?= ($validation->hasError('radius')) ? ' is-invalid' : '' ?>"
                    name="radius" placeholder="Radius" value="<?= old('radius', $lokasi_presensi['radius']) ?>" />
                <div class="invalid-feedback"><?= $validation->getError('radius') ?></div>
            </div>

            <!-- Input untuk Zona Waktu -->
            <div class="input-style-1">
                <label>Zona Waktu</label>
                <?php $zonaWaktu = old('zona_waktu', $lokasi_presensi['zona_waktu'] ?? ''); ?>
                <select name="zona_waktu" class="form-control <?= ($validation->hasError('zona_waktu')) ? 'is-invalid' : '' ?>">
                    <option value="" disabled <?= ($zonaWaktu == '') ? 'selected' : '' ?>>--Pilih Zona Waktu--</option>
                    <option value="WIB" <?= $zonaWaktu == 'WIB' ? 'selected' : '' ?>>WIB</option>
                    <option value="WITA" <?= $zonaWaktu == 'WITA' ? 'selected' : '' ?>>WITA</option>
                    <option value="WIT" <?= $zonaWaktu == 'WIT' ? 'selected' : '' ?>>WIT</option>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('zona_waktu') ?></div>
            </div>


            <div class="input-style-1">
                <label>Jam Masuk</label>
                <input type="time" class="form-control<?= ($validation->hasError('jam_masuk')) ? ' is-invalid' : '' ?>"
                    name="jam_masuk" placeholder="Jam Masuk"
                    value="<?= set_value('jam_masuk', old('jam_masuk', isset($lokasi_presensi['jam_masuk']) ? $lokasi_presensi['jam_masuk'] : '')) ?>" />
                <div class="invalid-feedback"><?= $validation->getError('jam_masuk') ?></div>
            </div>

            <div class="input-style-1">
                <label>Jam Pulang</label>
                <input type="time" class="form-control<?= ($validation->hasError('jam_pulang')) ? ' is-invalid' : '' ?>"
                    name="jam_pulang" placeholder="Jam Pulang"
                    value="<?= set_value('jam_pulang', old('jam_pulang', isset($lokasi_presensi['jam_pulang']) ? $lokasi_presensi['jam_pulang'] : '')) ?>" />
                <div class="invalid-feedback"><?= $validation->getError('jam_pulang') ?></div>
            </div>


            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>