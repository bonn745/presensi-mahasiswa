<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>

<div class="card col-md-6">
    <div class="card-body">
        <form action="<?= base_url('admin/data_pegawai/update/' . $pegawai['id']) ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- Nama -->
            <div class="input-style-1">
                <label for="nama">Nama</label>
                <input type="text" class="form-control<?= ($validation->hasError('nama')) ? ' is-invalid' : '' ?>"
                    name="nama" placeholder="Nama" value="<?= old('nama', $pegawai['nama']) ?>" />
                <div class="invalid-feedback"><?= $validation->getError('nama') ?></div>
            </div>

            <!-- Jenis Kelamin -->
            <div class="input-style-1">
                <label for="jenis_kelamin">Jenis Kelamin</label>
                <select id="jenis_kelamin" class="form-control<?= ($validation->hasError('jenis_kelamin')) ? ' is-invalid' : '' ?>" name="jenis_kelamin">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki" <?= ($pegawai['jenis_kelamin'] == 'Laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="Perempuan" <?= ($pegawai['jenis_kelamin'] == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('jenis_kelamin') ?></div>
            </div>

            <!-- Alamat -->
            <div class="input-style-1">
                <label for="alamat">Alamat</label>
                <textarea id="alamat" class="form-control<?= ($validation->hasError('alamat')) ? ' is-invalid' : '' ?>"
                    name="alamat" placeholder="Alamat Pegawai" rows="4"><?= old('alamat', $pegawai['alamat']) ?></textarea>
                <div class="invalid-feedback"><?= $validation->getError('alamat') ?></div>
            </div>

            <!-- No Handphone -->
            <div class="input-style-1">
                <label for="no_handphone">No Handphone</label>
                <input type="text" class="form-control<?= ($validation->hasError('no_handphone')) ? ' is-invalid' : '' ?>"
                    name="no_handphone" placeholder="No Handphone" value="<?= old('no_handphone', $pegawai['no_handphone']) ?>" />
                <div class="invalid-feedback"><?= $validation->getError('no_handphone') ?></div>
            </div>

            <!-- Jabatan -->
            <div class="input-style-1">
                <label for="jabatan">Jabatan</label>
                <select id="jabatan" class="form-control<?= ($validation->hasError('jabatan')) ? ' is-invalid' : '' ?>" name="jabatan">
                    <option value="">--Pilih Jabatan--</option>
                    <?php foreach ($jabatan as $jab) : ?>
                        <option value="<?= $jab['jabatan'] ?>" <?= ($pegawai['jabatan'] == $jab['jabatan']) ? "selected" : "" ?>><?= $jab['jabatan'] ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('jabatan') ?></div>
            </div>

            <!-- Lokasi Presensi -->
            <div class="input-style-1">
                <label for="lokasi_presensi">Lokasi Presensi</label>
                <select id="lokasi_presensi" class="form-control<?= ($validation->hasError('lokasi_presensi')) ? ' is-invalid' : '' ?>" name="lokasi_presensi">
                    <option value="">--Pilih Lokasi Presensi--</option>
                    <?php foreach ($lokasi_presensi as $lok) : ?>
                        <option value="<?= $lok['id'] ?>" <?= ($pegawai['lokasi_presensi'] == $lok['id']) ? "selected" : "" ?>><?= $lok['nama_lokasi'] ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('lokasi_presensi') ?></div>
            </div>

            <!-- Foto -->
            <div class="input-style-1">
                <label for="foto">Foto</label>
                <input type="hidden" value="<?= $pegawai['foto'] ?>" name="foto_lama">
                <input type="file" id="foto" class="form-control<?= ($validation->hasError('foto')) ? ' is-invalid' : '' ?>" name="foto" />
                <div class="invalid-feedback"><?= $validation->getError('foto') ?></div>
            </div>

            <!-- Username -->
            <div class="input-style-1">
                <label for="username">Username</label>
                <input type="text" id="username" class="form-control<?= ($validation->hasError('username')) ? ' is-invalid' : '' ?>"
                    name="username" placeholder="Username" value="<?= ($pegawai['username']) ?>" />
                <div class="invalid-feedback"><?= $validation->getError('username') ?></div>
            </div>

            <!-- Password -->
            <div class="input-style-1">
                <label>Password</label>
                <input type="hidden" value="<?= $pegawai['password'] ?>" name="password_lama">
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
                    <option value="">--Pilih Role--</option>
                    <option value="admin" <?= ($pegawai['role'] == "admin") ? "selected" : "" ?>>Admin</option>
                    <option value="pegawai" <?= ($pegawai['role'] == "pegawai") ? "selected" : "" ?>>Pegawai</option>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('role') ?></div>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <?= $this->endSection() ?>