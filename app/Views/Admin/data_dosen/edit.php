<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>

<div class="card col-md-6">
    <div class="card-body">
        <form action="<?= base_url('admin/data_dosen/update/' . $dosen['id']) ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- Nama -->
            <div class="input-style-1">
                <label for="nama_dosen">Nama</label>
                <input type="text" class="form-control<?= ($validation->hasError('nama')) ? ' is-invalid' : '' ?>"
                    name="nama_dosen" placeholder="Nama Dosen" value="<?= old('nama_dosen', $dosen['nama_dosen']) ?>" />
                <div class="invalid-feedback"><?= $validation->getError('nama_dosen') ?></div>
            </div>

            <!-- Jenis Kelamin -->
            <div class="input-style-1">
                <label for="jenis_kelamin">Jenis Kelamin</label>
                <select id="jenis_kelamin" class="form-control<?= ($validation->hasError('jenis_kelamin')) ? ' is-invalid' : '' ?>" name="jenis_kelamin">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki" <?= ($dosen['jenis_kelamin'] == 'Laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="Perempuan" <?= ($dosen['jenis_kelamin'] == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('jenis_kelamin') ?></div>
            </div>

            <!-- No Handphone -->
            <div class="input-style-1">
                <label for="no_hp">No Handphone</label>
                <input type="text" class="form-control<?= ($validation->hasError('no_hp')) ? ' is-invalid' : '' ?>"
                    name="no_hp" placeholder="No Handphone" value="<?= old('no_hp', $dosen['no_hp']) ?>" />
                <div class="invalid-feedback"><?= $validation->getError('no_hp') ?></div>
            </div>

            <!-- Alamat -->
            <div class="input-style-1">
                <label for="alamat">Alamat</label>
                <textarea id="alamat" class="form-control<?= ($validation->hasError('alamat')) ? ' is-invalid' : '' ?>"
                    name="alamat" placeholder="Alamat dosen" rows="4"><?= old('alamat', $dosen['alamat']) ?></textarea>
                <div class="invalid-feedback"><?= $validation->getError('alamat') ?></div>
            </div>

            <!-- Input untuk Jadwal Kuliah -->
            <div class="input-style-1 mb-3">
                <label for="jadwal_ngajar" class="font-weight-bold">Jadwal Ngajar</label>
                <select name="jadwal_ngajar" id="jadwal_ngajar" class="form-control<?= ($validation->hasError('jadwal_ngajar')) ? ' is-invalid' : '' ?>">
                    <option value="" disabled <?= old('jadwal_ngajar', $dosen['jadwal_ngajar'] ?? '') == '' ? 'selected' : '' ?>>-- Pilih Hari --</option>
                    <?php
                    $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
                    foreach ($hari as $h) :
                        $selected = old('jadwal_ngajar', $dosen['jadwal_ngajar'] ?? '') == $h ? 'selected' : '';
                    ?>
                        <option value="<?= $h ?>" <?= $selected ?>><?= $h ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('jadwal_ngajar') ?></div>
            </div>


            <!-- Username -->
            <div class="input-style-1">
                <label for="username">Username</label>
                <input type="text" id="username" class="form-control<?= ($validation->hasError('username')) ? ' is-invalid' : '' ?>"
                    name="username" placeholder="Username" value="<?= ($dosen['username']) ?>" />
                <div class="invalid-feedback"><?= $validation->getError('username') ?></div>
            </div>

            <!-- Password -->
            <div class="input-style-1">
                <label>Password</label>
                <input type="hidden" value="<?= $dosen['password'] ?>" name="password_lama">
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
                <!-- Menampilkan role secara otomatis dengan readonly -->
                <input type="text" id="role" class="form-control" name="role" value="<?= $dosen['role'] ?>" readonly>
                <div class="invalid-feedback"><?= $validation->getError('role') ?></div>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <?= $this->endSection() ?>