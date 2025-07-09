<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>
<div class="card col-md-6">
    <div class="card-body">
        <form action="<?= base_url('admin/data_mahasiswa/store') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="text-dark w-100 text-center">Data Mahasiswa</div>
            <hr>

            <!-- NIM -->
            <div class="input-style-1">
                <label for="nim">NIM</label>
                <input type="text" class="form-control<?= ($validation->hasError('nim')) ? ' is-invalid' : '' ?>"
                    name="nim" id="nim" placeholder="Nomor Induk Mahasiswa"
                    value="<?= set_value('nim', $mahasiswa['nim'] ?? '') ?>" />
                <div class="invalid-feedback"><?= $validation->getError('nim') ?></div>
            </div>

            <!-- Nama -->
            <div class="input-style-1">
                <label for="nama">Nama Lengkap</label>
                <input type="text" class="form-control<?= ($validation->hasError('nama')) ? ' is-invalid' : '' ?>"
                    name="nama" id="nama" placeholder="Masukkan Nama Lengkap"
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
                <input id="no_handphone" type="text" class="form-control<?= ($validation->hasError('no_handphone')) ? ' is-invalid' : '' ?>"
                    name="no_handphone" placeholder="No Handphone"
                    value="<?= set_value('no_handphone', $mahasiswa['no_handphone'] ?? '') ?>" onblur="formatPhoneNumber(this)"/>
                <div class="invalid-feedback"><?= $validation->getError('no_handphone') ?></div>
            </div>

            <!-- Semester -->
            <div class="input-style-1">
                <label for="semester">Semester</label>
                <select id="semester" name="semester" class="form-control<?= ($validation->hasError('semester')) ? ' is-invalid' : '' ?>">
                    <option value="" disabled selected>-- Pilih Semester --</option>
                    <?php for ($i = 1; $i <= 8; $i++): ?>
                        <option value="Semester <?= $i ?>" <?= set_value('semester', $mahasiswa['semester'] ?? '') == "Semester $i" ? 'selected' : '' ?>>Semester <?= $i ?></option>
                    <?php endfor; ?>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('semester') ?></div>
            </div>

            <!-- Jurusan -->
            <div class="input-style-1">
                <label for="jurusan">Program Studi</label>
                <input id="jurusan" type="text" class="form-control<?= ($validation->hasError('jurusan')) ? ' is-invalid' : '' ?>"
                    name="jurusan" placeholder="Program Studi"
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
                    value="<?= set_value('username', $mahasiswa['username'] ?? '') ?>" autocomplete="username"/>
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
            
            <hr>
            <div class="text-dark w-100 text-center">Data Orang Tua</div>
            
            <!-- Nama Orang Tua -->
            <div class="input-style-1">
                <label for="nama_ortu">Nama Orang Tua</label>
                <input id="nama_ortu" type="text" class="form-control<?= ($validation->hasError('nama_ortu')) ? ' is-invalid' : '' ?>"
                    name="nama_ortu" placeholder="Masukkan Nama Lengkap"
                    value="<?= set_value('nama_ortu', $mahasiswa['nama_ortu'] ?? '') ?>" />
                <div class="invalid-feedback"><?= $validation->getError('nama_ortu') ?></div>
            </div>
            
            <!-- Jenis Kelamin Orang Tua -->
            <div class="input-style-1">
                <label for="jenis_kelamin_ortu">Jenis Kelamin</label>
                <select id="jenis_kelamin_ortu" class="form-control<?= ($validation->hasError('jenis_kelamin_ortu')) ? ' is-invalid' : '' ?>" name="jenis_kelamin_ortu">
                    <option value="" disabled selected>--Jenis Kelamin--</option>
                    <option value="Laki-laki" <?= set_select('jenis_kelamin_ortu', 'Laki-laki', ($mahasiswa['jenis_kelamin_ortu'] ?? '') == 'Laki-laki') ?>>Laki-laki</option>
                    <option value="Perempuan" <?= set_select('jenis_kelamin_ortu', 'Perempuan', ($mahasiswa['jenis_kelamin_ortu'] ?? '') == 'Perempuan') ?>>Perempuan</option>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('jenis_kelamin_ortu') ?></div>
            </div>
            
            <!-- No WhatsApp Orang Tua -->
            <div class="input-style-1">
                <label for="no_whatsapp">No WhatsApp</label>
                <input id="no_whatsapp" type="tel" class="form-control<?= ($validation->hasError('no_whatsapp')) ? ' is-invalid' : '' ?>"
                    name="no_whatsapp" placeholder="No WhatsApp"
                    value="<?= set_value('no_whatsapp', $mahasiswa['no_whatsapp'] ?? '') ?>" onblur="formatPhoneNumber(this)"/>
                <div class="invalid-feedback"><?= $validation->getError('no_whatsapp') ?></div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/mhs.js') ?>"></script>
<?= $this->endSection() ?>
