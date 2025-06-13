<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>

<div class="card col-md-6">
    <div class="card-body">
        <form method="POST" action="<?= base_url('admin/lokasi_presensi/update/' . $lokasi_presensi['id']) ?>">
            <?= csrf_field() ?>
            <!-- Input untuk Nama Ruangan -->
            <div class="input-style-1">
                <label for="nama_ruangan">Nama Ruangan</label>
                <input type="number" id="nama_ruangan"
                    class="form-control<?= ($validation->hasError('nama_ruangan')) ? ' is-invalid' : '' ?>"
                    name="nama_ruangan"
                    placeholder="Nomor Ruangan"
                    value="<?= old('nama_ruangan', $lokasi_presensi['nama_ruangan'] ?? '') ?>" />
                <div class="invalid-feedback"><?= $validation->getError('nama_ruangan') ?></div>
            </div>

            <!-- Input untuk Alamat Lokasi -->
            <div class="input-style-1">
                <label for="alamat_lokasi">Alamat Lokasi</label>
                <select id="alamat_lokasi" class="form-control<?= ($validation->hasError('alamat_lokasi')) ? ' is-invalid' : '' ?>"
                    name="alamat_lokasi">
                    <option value="" disabled selected>-- Pilih Alamat Lokasi --</option>
                    <option value="Universitas Bung Hatta I" <?= old('alamat_lokasi', $lokasi_presensi['alamat_lokasi'] ?? '') == 'Universitas Bung Hatta I' ? 'selected' : '' ?>>Universitas Bung Hatta I</option>
                    <option value="Universitas Bung Hatta II" <?= old('alamat_lokasi', $lokasi_presensi['alamat_lokasi'] ?? '') == 'Universitas Bung Hatta II' ? 'selected' : '' ?>>Universitas Bung Hatta II</option>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('alamat_lokasi') ?></div>
            </div>

            <!-- Input untuk Tipe Lokasi -->
            <div class="input-style-1">
                <label for="tipe_lokasi">Tipe Lokasi</label>
                <select id="tipe_lokasi" name="tipe_lokasi" class="form-control<?= ($validation->hasError('tipe_lokasi')) ? ' is-invalid' : '' ?>">
                    <!-- Options diisi lewat JS -->
                </select>
                <div class="invalid-feedback"><?= $validation->getError('tipe_lokasi') ?></div>
            </div>

            <!-- Input untuk Jadwal Kuliah -->
            <div class="input-style-1 mb-3">
                <label for="jadwal_kuliah" class="font-weight-bold">Jadwal Kuliah</label>
                <select name="jadwal_kuliah" id="jadwal_kuliah" class="form-control<?= ($validation->hasError('jadwal_kuliah')) ? ' is-invalid' : '' ?>">
                    <option value="" disabled <?= old('jadwal_kuliah', $lokasi_presensi['jadwal_kuliah'] ?? '') == '' ? 'selected' : '' ?>>-- Pilih Hari --</option>
                    <?php
                    $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
                    foreach ($hari as $h) :
                        $selected = old('jadwal_kuliah', $lokasi_presensi['jadwal_kuliah'] ?? '') == $h ? 'selected' : '';
                    ?>
                        <option value="<?= $h ?>" <?= $selected ?>><?= $h ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('jadwal_kuliah') ?></div>
            </div>

            <!-- Matkul -->
            <div class="input-style-1 mb-3">
                <label for="matkul">Mata Kuliah</label>
                <select id="matkul" class="form-control<?= ($validation->hasError('matkul')) ? ' is-invalid' : '' ?>" name="matkul">
                    <option value="" disabled>--Pilih Matkul--</option>
                    <?php foreach ($matkul as $mk) : ?>
                        <option value="<?= $mk['matkul'] ?>" <?= old('matkul', $lokasi_presensi['matkul'] ?? '') == $mk['matkul'] ? 'selected' : '' ?>><?= $mk['matkul'] ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('matkul') ?></div>
            </div>

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
        <!-- Script untuk update Tipe Lokasi -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const alamatSelect = document.getElementById('alamat_lokasi');
                const tipeSelect = document.getElementById('tipe_lokasi');

                const tipeOptions = {
                    "Universitas Bung Hatta I": [
                        "Fakultas Teknik Mesin",
                        "Fakultas Teknik Sipil"
                    ],
                    "Universitas Bung Hatta II": [
                        "Fakultas FKIP",
                        "Fakultas Ekonomi",
                        "Fakultas Hukum"
                    ]
                };

                function updateTipeOptions(selectedAlamat, selectedTipe = '') {
                    tipeSelect.innerHTML = '<option value="" disabled>Pilih Tipe Lokasi</option>';
                    if (tipeOptions[selectedAlamat]) {
                        tipeOptions[selectedAlamat].forEach(function(option) {
                            const opt = document.createElement('option');
                            opt.value = option;
                            opt.textContent = option;
                            if (option === selectedTipe) {
                                opt.selected = true;
                            }
                            tipeSelect.appendChild(opt);
                        });
                    }
                }

                const currentAlamat = alamatSelect.value;
                const currentTipe = "<?= old('tipe_lokasi', $lokasi_presensi['tipe_lokasi'] ?? '') ?>";
                if (currentAlamat) {
                    updateTipeOptions(currentAlamat, currentTipe);
                }

                alamatSelect.addEventListener('change', function() {
                    updateTipeOptions(this.value);
                });
            });
        </script>
    </div>
</div>

<?= $this->endSection() ?>