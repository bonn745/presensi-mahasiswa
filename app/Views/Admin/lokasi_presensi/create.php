<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>

<div class="card col-md-6"> <!-- Menghapus mx-auto untuk menempatkan kartu di sebelah kiri -->
    <div class="card-body">
        <form method="POST" action="<?= base_url('admin/lokasi_presensi/store') ?>">
            <?= csrf_field() ?>
            <!-- Input untuk Nama Lokasi -->
            <div class="input-style-1">
                <label for="nama_lokasi">Nama Lokasi</label>
                <input type="text" id="nama_lokasi" class="form-control<?= ($validation->hasError('nama_lokasi')) ? ' is-invalid' : '' ?>"
                    name="nama_lokasi" placeholder="Nama Lokasi" value="<?= set_value('nama_lokasi', old('nama_lokasi')) ?>" />
                <div class="invalid-feedback"><?= $validation->getError('nama_lokasi') ?></div>
            </div>

            <!-- Input untuk Alamat Lokasi -->
            <div class="input-style-1">
                <label for="alamat_lokasi">Alamat Lokasi</label>
                <textarea id="alamat_lokasi" class="form-control<?= ($validation->hasError('alamat_lokasi')) ? ' is-invalid' : '' ?>"
                    name="alamat_lokasi" placeholder="Alamat Lokasi" rows="4"><?= set_value('alamat_lokasi', old('alamat_lokasi')) ?></textarea>
                <div class="invalid-feedback"><?= $validation->getError('alamat_lokasi') ?></div>
            </div>

            <!-- Input untuk Tipe Lokasi -->
            <div class="input-style-1">
                <label for="tipe_lokasi">Tipe Lokasi</label>
                <select id="tipe_lokasi" class="form-control<?= ($validation->hasError('tipe_lokasi')) ? ' is-invalid' : '' ?>"
                    name="tipe_lokasi">
                    <option value="" disabled selected>Pilih Tipe Lokasi</option>
                    <option value="Tipe 1 - Kantor Utama" <?= set_select('tipe_lokasi', 'Tipe 1 - Kantor Utama', old('tipe_lokasi') == 'Tipe 1 - Kantor Utama') ?>>Tipe 1 - Kantor Utama</option>
                    <option value="Tipe 1 - Ruang Rapat" <?= set_select('tipe_lokasi', 'Tipe 1 - Ruang Rapat', old('tipe_lokasi') == 'Tipe 1 - Ruang Rapat') ?>>Tipe 1 - Ruang Rapat</option>
                    <option value="Tipe 1 - Lobi" <?= set_select('tipe_lokasi', 'Tipe 1 - Lobi', old('tipe_lokasi') == 'Tipe 1 - Lobi') ?>>Tipe 1 - Lobi</option>

                    <option value="Tipe 2 - Bagian Kepegawaian" <?= set_select('tipe_lokasi', 'Tipe 2 - Bagian Kepegawaian', old('tipe_lokasi') == 'Tipe 2 - Bagian Kepegawaian') ?>>Tipe 2 - Bagian Kepegawaian</option>
                    <option value="Tipe 2 - Bagian Keuangan" <?= set_select('tipe_lokasi', 'Tipe 2 - Bagian Keuangan', old('tipe_lokasi') == 'Tipe 2 - Bagian Keuangan') ?>>Tipe 2 - Bagian Keuangan</option>
                    <option value="Tipe 2 - Bagian Umum" <?= set_select('tipe_lokasi', 'Tipe 2 - Bagian Umum', old('tipe_lokasi') == 'Tipe 2 - Bagian Umum') ?>>Tipe 2 - Bagian Umum</option>

                    <option value="Tipe 3 - Area Parkir" <?= set_select('tipe_lokasi', 'Tipe 3 - Area Parkir', old('tipe_lokasi') == 'Tipe 3 - Area Parkir') ?>>Tipe 3 - Area Parkir</option>
                    <option value="Tipe 3 - Kantin" <?= set_select('tipe_lokasi', 'Tipe 3 - Kantin', old('tipe_lokasi') == 'Tipe 3 - Kantin') ?>>Tipe 3 - Kantin</option>
                    <option value="Tipe 3 - Pos Keamanan" <?= set_select('tipe_lokasi', 'Tipe 3 - Pos Keamanan', old('tipe_lokasi') == 'Tipe 3 - Pos Keamanan') ?>>Tipe 3 - Pos Keamanan</option>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('tipe_lokasi') ?></div>
            </div>

            <!-- Input untuk Jam Kerja -->
            <div class="input-style-1 mb-3">
                <label for="jadwal_kerja" class="font-weight-bold">Jadwal Kerja</label>
            </div>
            <div class="form-check form-check-inline <?= ($validation->hasError('jadwal_kerja')) ? 'is-invalid' : '' ?> mb-2">
                <input class="form-check-input" type="radio" name="jadwal_kerja" value="Senin-Kamis"
                    <?= old('jadwal_kerja') == 'Senin-Kamis' ? 'checked' : '' ?>>
                <label class="form-check-label">Senin - Kamis</label>
            </div>
            <div class="form-check form-check-inline <?= ($validation->hasError('jadwal_kerja')) ? 'is-invalid' : '' ?> mb-2">
                <input class="form-check-input" type="radio" name="jadwal_kerja" value="Jumat"
                    <?= old('jadwal_kerja') == 'Jumat' ? 'checked' : '' ?>>
                <label class="form-check-label">Jumat</label>
            </div>
            <div class="invalid-feedback"><?= $validation->getError('jadwal_kerja') ?></div>


            <!-- Input untuk Latitude -->
            <div class="input-style-1">
                <label for="latitude">Latitude</label>
                <input type="text" id="latitude" class="form-control<?= ($validation->hasError('latitude')) ? ' is-invalid' : '' ?>"
                    name="latitude" placeholder="Latitude" value="<?= set_value('latitude', old('latitude')) ?>" />
                <div class="invalid-feedback"><?= $validation->getError('latitude') ?></div>
            </div>

            <!-- Input untuk Longitude -->
            <div class="input-style-1">
                <label for="longitude">Longitude</label>
                <input type="text" id="longitude" class="form-control<?= ($validation->hasError('longitude')) ? ' is-invalid' : '' ?>"
                    name="longitude" placeholder="Longitude" value="<?= set_value('longitude', old('longitude')) ?>" />
                <div class="invalid-feedback"><?= $validation->getError('longitude') ?></div>
            </div>

            <!-- Input untuk Radius -->
            <div class="input-style-1">
                <label for="radius">Radius (meter)</label>
                <input type="number" id="radius" class="form-control<?= ($validation->hasError('radius')) ? ' is-invalid' : '' ?>"
                    name="radius" placeholder="Radius" value="<?= set_value('radius', old('radius')) ?>" />
                <div class="invalid-feedback"><?= $validation->getError('radius') ?></div>
            </div>

            <!-- Input untuk Zona Waktu -->
            <div class="input-style-1">
                <label>Zona Waktu</label>
                <select name="zona_waktu" class="form-control <?=
                                                                ($validation->hasError('zona_waktu'))  ? 'is-invalid' :
                                                                    '' ?>">
                    <option value="" disabled selected>--Pilih Zona Waktu--</option>
                    <option value="WIB">WIB</option>
                    <option value="WITA">WITA</option>
                    <option value="WIT">WIT</option>
                </select>
                <div class="invalid feedback"><?= $validation->getError('zona_waktu') ?></div>
            </div>

            <div class="input-style-1">
                <label>Jam Masuk</label>
                <input type="time" class="form-control<?= ($validation->hasError('jam_masuk')) ? ' is-invalid' : '' ?>"
                    name="jam_masuk" placeholder="Jam Masuk" value="<?= set_value('jam_masuk', old('jam_masuk')) ?>" />
                <div class="invalid-feedback"><?= $validation->getError('jam_masuk') ?></div>
            </div>

            <div class="input-style-1">
                <label>Jam Pulang</label>
                <input type="time" class="form-control<?= ($validation->hasError('jam_pulang')) ? ' is-invalid' : '' ?>"
                    name="jam_pulang" placeholder="Jam Pulang" value="<?= set_value('jam_pulang', old('jam_pulang')) ?>" />
                <div class="invalid-feedback"><?= $validation->getError('jam_pulang') ?></div>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>