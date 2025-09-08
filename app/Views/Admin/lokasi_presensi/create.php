<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>

<div class="card col-md-6"> <!-- Menghapus mx-auto untuk menempatkan kartu di sebelah kiri -->
    <div class="card-body">
        <form method="POST" action="<?= base_url('admin/lokasi_presensi/store') ?>">
            <?= csrf_field() ?>
            <!-- Input untuk Nama Ruangan -->
            <div class="input-style-1">
                <label for="nama_ruangan">Nama Ruangan</label>
                <input type="number" id="nama_ruangan" class="form-control<?= ($validation->hasError('nama_ruangan')) ? ' is-invalid' : '' ?>"
                    name="nama_ruangan" placeholder="Nomor Ruangan" value="<?= set_value('nama_ruangan', old('nama_ruangan')) ?>" />
                <div class="invalid-feedback"><?= $validation->getError('nama_ruangan') ?></div>
            </div>


            <!-- Input untuk Alamat Lokasi -->
            <div class="input-style-1">
                <label for="alamat_lokasi">Alamat Lokasi</label>
                <select id="alamat_lokasi" name="alamat_lokasi" class="form-control<?= ($validation->hasError('alamat_lokasi')) ? ' is-invalid' : '' ?>">
                    <option value="" disabled selected>Pilih Alamat Lokasi</option>
                    <option value="Universitas Bung Hatta I" <?= old('alamat_lokasi') == 'Universitas Bung Hatta I' ? 'selected' : '' ?>>Universitas Bung Hatta I</option>
                    <option value="Universitas Bung Hatta II" <?= old('alamat_lokasi') == 'Universitas Bung Hatta II' ? 'selected' : '' ?>>Universitas Bung Hatta II</option>
                    <option value="Universitas Bung Hatta III" <?= old('alamat_lokasi') == 'Universitas Bung Hatta III' ? 'selected' : '' ?>>Universitas Bung Hatta III</option>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('alamat_lokasi') ?></div>
            </div>

            <!-- Input untuk Tipe Lokasi -->
            <div class="input-style-1">
                <label for="tipe_lokasi">Tipe Lokasi</label>
                <select id="tipe_lokasi" name="tipe_lokasi" class="form-control<?= ($validation->hasError('tipe_lokasi')) ? ' is-invalid' : '' ?>">
                    <option value="" disabled selected>Pilih Tipe Lokasi</option>
                    <!-- Opsi akan terisi otomatis lewat JavaScript -->
                </select>
                <div class="invalid-feedback"><?= $validation->getError('tipe_lokasi') ?></div>
            </div>

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
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
        <script>
            const tipeLokasiData = {
                "Universitas Bung Hatta I": [
                    "Fakultas Perikanan dan Ilmu Kelautan",
                    "Fakultas Teknik Sipil dan Perencanaan"
                ],
                "Universitas Bung Hatta II": [
                    "Fakultas Keguruan dan Ilmu Pendidikan",
                    "Fakultas Ekonomi",
                    "Fakultas Ilmu Budaya",
                    "Fakultas Hukum"
                ],
                "Universitas Bung Hatta III": [
                    "Fakultas Teknik Kimia",
                    "Fakultas Teknologi Industri",
                    "Fakultas Teknik Rekayasa Komputer Jaringan"
                ]
            };

            document.addEventListener("DOMContentLoaded", function() {
                const alamatSelect = document.getElementById("alamat_lokasi");
                const tipeSelect = document.getElementById("tipe_lokasi");

                function updateTipeLokasi() {
                    const selectedAlamat = alamatSelect.value;
                    const options = tipeLokasiData[selectedAlamat] || [];

                    tipeSelect.innerHTML = '<option disabled selected>Pilih Tipe Lokasi</option>';
                    options.forEach(tipe => {
                        const option = document.createElement("option");
                        option.value = tipe;
                        option.text = tipe;
                        option.selected = tipe === "<?= old('tipe_lokasi') ?>";
                        tipeSelect.appendChild(option);
                    });
                }

                alamatSelect.addEventListener("change", updateTipeLokasi);

                // Jalankan saat load jika old() sudah ada
                if (alamatSelect.value) {
                    updateTipeLokasi();
                }
            });
        </script>

    </div>
</div>

<?= $this->endSection() ?>