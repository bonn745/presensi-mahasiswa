<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>
<div class="card col-md-6">
    <div class="card-body">
        <form action="<?= base_url('admin/data_mahasiswa/store') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- Nama -->
            <div class="input-style-1">
                <label for="nama">Nama Lengkap</label>
                <input type="text" class="form-control<?= ($validation->hasError('nama')) ? ' is-invalid' : '' ?>"
                    name="nama" placeholder="Masukkan Nama Lengkap"
                    value="<?= set_value('nama', $mahasiswa['nama'] ?? '') ?>" />
                <div class="invalid-feedback"><?= $validation->getError('nama') ?></div>
            </div>

            <!-- Jenis Kelamin -->
            <div class="input-style-1">
                <label for="jenis_kelamin">Jenis Kelamin</label>
                <select id="jenis_kelamin" class="form-control<?= ($validation->hasError('jenis_kelamin')) ? ' is-invalid' : '' ?>" name="jenis_kelamin">
                    <option value="" disabled selected>--Pilih Jenis Kelamin--</option>
                    <option value="Laki-laki" <?= set_select('jenis_kelamin', 'Laki-laki', ($mahasiswa['jenis_kelamin'] ?? '') == 'Laki-laki') ?>>Laki-laki</option>
                    <option value="Perempuan" <?= set_select('jenis_kelamin', 'Perempuan', ($mahasiswa['jenis_kelamin'] ?? '') == 'Perempuan') ?>>Perempuan</option>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('jenis_kelamin') ?></div>
            </div>

            <!-- Alamat -->
            <div class="input-style-1">
                <label for="alamat">Alamat</label>
                <textarea id="alamat" class="form-control<?= ($validation->hasError('alamat')) ? ' is-invalid' : '' ?>"
                    name="alamat" placeholder="Alamat Mahasiswa" rows="4"><?= set_value('alamat', $mahasiswa['alamat'] ?? '') ?></textarea>
                <div class="invalid-feedback"><?= $validation->getError('alamat') ?></div>
            </div>

            <!-- No Handphone -->
            <div class="input-style-1">
                <label for="no_handphone">No Handphone</label>
                <input type="text" class="form-control<?= ($validation->hasError('no_handphone')) ? ' is-invalid' : '' ?>"
                    name="no_handphone" placeholder="No Handphone"
                    value="<?= set_value('no_handphone', $mahasiswa['no_handphone'] ?? '') ?>" />
                <div class="invalid-feedback"><?= $validation->getError('no_handphone') ?></div>
            </div>

            <!-- Semester -->
            <div class="input-style-1">
                <label for="semester">Semester</label>
                <select name="semester" class="form-control<?= ($validation->hasError('semester')) ? ' is-invalid' : '' ?>">
                    <option value="" disabled selected>-- Pilih Semester --</option>
                    <?php for ($i = 1; $i <= 8; $i++): ?>
                        <option value="Semester <?= $i ?>" <?= set_value('semester', $mahasiswa['semester'] ?? '') == "Semester $i" ? 'selected' : '' ?>>Semester <?= $i ?></option>
                    <?php endfor; ?>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('semester') ?></div>
            </div>

            <!-- Jurusan -->
            <div class="input-style-1">
                <label for="jurusan">Jurusan</label>
                <input type="text" class="form-control<?= ($validation->hasError('jurusan')) ? ' is-invalid' : '' ?>"
                    name="jurusan" placeholder="Jurusan"
                    value="<?= set_value('jurusan', $mahasiswa['jurusan'] ?? '') ?>" />
                <div class="invalid-feedback"><?= $validation->getError('jurusan') ?></div>
            </div>

            <!-- Foto -->
            <div class="input-style-1 mb-4">
                <label for="foto">Foto</label>
                <input type="file" id="foto" class="form-control<?= ($validation->hasError('foto')) ? ' is-invalid' : '' ?>" name="foto" />
                <div class="invalid-feedback"><?= $validation->getError('foto') ?></div>
            </div>

            <!-- Username -->
            <div class="input-style-1 mb-4">
                <label for="username">Username</label>
                <input type="text" id="username" class="form-control<?= ($validation->hasError('username')) ? ' is-invalid' : '' ?>"
                    name="username" placeholder="Username"
                    value="<?= set_value('username', $mahasiswa['username'] ?? '') ?>" />
                <div class="invalid-feedback"><?= $validation->getError('username') ?></div>
            </div>

            <!-- Password -->
            <div class="input-style-1 mb-4">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control<?= ($validation->hasError('password')) ? ' is-invalid' : '' ?>"
                    name="password" placeholder="Password" />
                <div class="invalid-feedback"><?= $validation->getError('password') ?></div>
            </div>

            <!-- Konfirmasi Password -->
            <div class="input-style-1 mb-4">
                <label for="konfirmasi_password">Konfirmasi Password</label>
                <input type="password" id="konfirmasi_password" class="form-control<?= ($validation->hasError('konfirmasi_password')) ? ' is-invalid' : '' ?>"
                    name="konfirmasi_password" placeholder="Konfirmasi Password" />
                <div class="invalid-feedback"><?= $validation->getError('konfirmasi_password') ?></div>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>