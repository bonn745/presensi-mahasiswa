<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>
<div class="card col-md-6"> <!-- Menghapus mx-auto untuk menempatkan kartu di sebelah kiri -->
    <div class="card-body">
        <form action="<?= base_url('admin/data_dosen/store') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- Nama -->
            <div class="input-style-1">
                <label for="nama_dosen">Nama Lengkap</label>
                <input type="text" class="form-control<?= ($validation->hasError('nama_dosen')) ? ' is-invalid' : '' ?>"
                    name="nama_dosen" placeholder="Masukkan Nama" value="<?= set_value('nama_dosen', $dosen['nama_dosen'] ?? '') ?>" />
                <div class="invalid-feedback"><?= $validation->getError('nama_dosen') ?></div>
            </div>

            <!-- Jenis Kelamin -->
            <div class="input-style-1">
                <label for="jenis_kelamin">Jenis Kelamin</label>
                <select id="jenis_kelamin" class="form-control<?= ($validation->hasError('jenis_kelamin')) ? ' is-invalid' : '' ?>" name="jenis_kelamin">
                    <option value="" disabled selected>--Pilih Jenis Kelamin--</option>
                    <option value="Laki-laki" <?= set_select('jenis_kelamin', 'Laki-laki', ($dosen['jenis_kelamin'] ?? '') == 'Laki-laki') ?>>Laki-laki</option>
                    <option value="Perempuan" <?= set_select('jenis_kelamin', 'Perempuan', ($dosen['jenis_kelamin'] ?? '') == 'Perempuan') ?>>Perempuan</option>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('jenis_kelamin') ?></div>
            </div>

            <!-- No Handphone -->
            <div class="input-style-1">
                <label for="no_hp">No Handphone</label>
                <input type="text" class="form-control<?= ($validation->hasError('no_hp')) ? ' is-invalid' : '' ?>"
                    name="no_hp" placeholder="No Handphone" value="<?= set_value('no_hp', $dosen['no_hp'] ?? '') ?>" />
                <div class="invalid-feedback"><?= $validation->getError('no_hp') ?></div>
            </div>

            <!-- Alamat -->
            <div class="input-style-1">
                <label for="alamat">Alamat</label>
                <textarea id="alamat" class="form-control<?= ($validation->hasError('alamat')) ? ' is-invalid' : '' ?>"
                    name="alamat" placeholder="Alamat dosen" rows="4"><?= set_value('alamat', $dosen['alamat'] ?? '') ?></textarea>
                <div class="invalid-feedback"><?= $validation->getError('alamat') ?></div>
            </div>

            <!-- Username -->
            <div class="input-style-1">
                <label for="username">Username</label>
                <input type="text" id="username" class="form-control<?= ($validation->hasError('username')) ? ' is-invalid' : '' ?>"
                    name="username" placeholder="Username" value="<?= set_value('username', $dosen['username'] ?? '') ?>" />
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
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>