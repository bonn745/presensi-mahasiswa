<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>
<div class="card col-md-6"> <!-- Menghapus mx-auto untuk menempatkan kartu di sebelah kiri -->
    <div class="card-body">
        <form action="<?= base_url('admin/data_pegawai/store') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- Nama -->
            <div class="input-style-1">
                <label for="nama">Nama Lengkap</label>
                <input type="text" class="form-control<?= ($validation->hasError('nama')) ? ' is-invalid' : '' ?>"
                    name="nama" placeholder="Masukkan Nama" value="<?= set_value('nama', $pegawai['nama'] ?? '') ?>" />
                <div class="invalid-feedback"><?= $validation->getError('nama') ?></div>
            </div>

            <!-- Jenis Kelamin -->
            <div class="input-style-1">
                <label for="jenis_kelamin">Jenis Kelamin</label>
                <select id="jenis_kelamin" class="form-control<?= ($validation->hasError('jenis_kelamin')) ? ' is-invalid' : '' ?>" name="jenis_kelamin">
                    <option value="" disabled selected>--Pilih Jenis Kelamin--</option>
                    <option value="Laki-laki" <?= set_select('jenis_kelamin', 'Laki-laki', ($pegawai['jenis_kelamin'] ?? '') == 'Laki-laki') ?>>Laki-laki</option>
                    <option value="Perempuan" <?= set_select('jenis_kelamin', 'Perempuan', ($pegawai['jenis_kelamin'] ?? '') == 'Perempuan') ?>>Perempuan</option>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('jenis_kelamin') ?></div>
            </div>

            <!-- Alamat -->
            <div class="input-style-1">
                <label for="alamat">Alamat</label>
                <textarea id="alamat" class="form-control<?= ($validation->hasError('alamat')) ? ' is-invalid' : '' ?>"
                    name="alamat" placeholder="Alamat Pegawai" rows="4"><?= set_value('alamat', $pegawai['alamat'] ?? '') ?></textarea>
                <div class="invalid-feedback"><?= $validation->getError('alamat') ?></div>
            </div>

            <!-- No Handphone -->
            <div class="input-style-1">
                <label for="no_handphone">No Handphone</label>
                <input type="text" class="form-control<?= ($validation->hasError('no_handphone')) ? ' is-invalid' : '' ?>"
                    name="no_handphone" placeholder="No Handphone" value="<?= set_value('no_handphone', $pegawai['no_handphone'] ?? '') ?>" />
                <div class="invalid-feedback"><?= $validation->getError('no_handphone') ?></div>
            </div>

            <!-- Jabatan -->
            <div class="input-style-1">
                <label for="jabatan">Jabatan</label>
                <select id="jabatan" class="form-control<?= ($validation->hasError('jabatan')) ? ' is-invalid' : '' ?>" name="jabatan">
                    <option value="" disabled selected>--Pilih Jabatan--</option>
                    <?php foreach ($jabatan as $jab) : ?>
                        <option value="<?= $jab['jabatan'] ?>" <?= set_select('jabatan', $jab['jabatan'], ($pegawai['jabatan'] ?? '') == $jab['jabatan']) ?>><?= $jab['jabatan'] ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('jabatan') ?></div>
            </div>

            <!-- Lokasi Presensi -->
            <div class="input-style-1">
                <label for="lokasi_presensi">Lokasi Presensi</label>
                <select id="lokasi_presensi" class="form-control<?= ($validation->hasError('lokasi_presensi')) ? ' is-invalid' : '' ?>" name="lokasi_presensi">
                    <option value="" disabled selected>--Pilih Lokasi Presensi--</option>
                    <?php foreach ($lokasi_presensi as $lok) : ?>
                        <option value="<?= $lok['id'] ?>" <?= set_select('lokasi_presensi', $lok['id'], ($pegawai['lokasi_presensi'] ?? '') == $lok['id']) ?>><?= $lok['nama_lokasi'] ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('lokasi_presensi') ?></div>
            </div>

            <!-- Foto -->
            <div class="input-style-1">
                <label for="foto">Foto</label>
                <input type="file" id="foto" class="form-control<?= ($validation->hasError('foto')) ? ' is-invalid' : '' ?>" name="foto" />
                <div class="invalid-feedback"><?= $validation->getError('foto') ?></div>
            </div>

            <!-- Username -->
            <div class="input-style-1">
                <label for="username">Username</label>
                <input type="text" id="username" class="form-control<?= ($validation->hasError('username')) ? ' is-invalid' : '' ?>"
                    name="username" placeholder="Username" value="<?= set_value('username', $pegawai['username'] ?? '') ?>" />
                <div class="invalid-feedback"><?= $validation->getError('username') ?></div>
            </div>

            <!-- Password -->
            <div class="input-style-1">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control<?= ($validation->hasError('password')) ? ' is-invalid' : '' ?>"
                    name="password" placeholder="Password" />
                <div class="invalid-feedback"><?= $validation->getError('password') ?></div>
            </div>

            <!-- Konfirmasi Password -->
            <div class="input-style-1">
                <label for="konfirmasi_password">Konfirmasi Password</label>
                <input type="password" id="konfirmasi_password" class="form-control<?= ($validation->hasError('konfirmasi_password')) ? ' is-invalid' : '' ?>"
                    name="konfirmasi_password" placeholder="Konfirmasi Password" />
                <div class="invalid-feedback"><?= $validation->getError('konfirmasi_password') ?></div>
            </div>

            <!-- Role -->
            <div class="input-style-1">
                <label for="role">Role</label>
                <select id="role" class="form-control<?= ($validation->hasError('role')) ? ' is-invalid' : '' ?>" name="role">
                    <option value="" disabled selected>--Pilih Role--</option>
                    <option value="admin" <?= set_select('role', 'admin', ($pegawai['role'] ?? '') == 'admin') ?>>Admin</option>
                    <option value="pegawai" <?= set_select('role', 'pegawai', ($pegawai['role'] ?? '') == 'pegawai') ?>>Pegawai</option>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('role') ?></div>
            </div>


            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>