<?= $this->extend('admin/layouts.php') ?>
<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="card-body">
        <form action="<?= url_to('admin.uangkuliah.store') ?>" method="POST">
            <?= csrf_field() ?>
            <!-- Mahasiswa -->
            <div class="row col-md-12">
                <div class="input-style-1 col-md-6">
                    <label for="mahasiswa">Mahasiswa</label>
                    <select id="mahasiswa" name="mahasiswa" class="form-control<?= isset(session()->get('errors')['mahasiswa']) ? ' is-invalid' : '' ?>">
                        <option value="" disabled selected>-- Pilih Mahasiswa --</option>
                        <?php foreach ($mahasiswa as $mhs): ?>
                            <option value="<?= $mhs['npm'] ?>" <?= isset(session()->get('data')['mahasiswa']) ? (session()->get('data')['mahasiswa'] == $mhs['id'] ? 'selected' : '') : '' ?>><?= $mhs['nama'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback"><?= isset(session()->get('errors')['mahasiswa']) ? session()->get('errors')['mahasiswa'] : '' ?></div>
                </div>
            </div>

            <!-- Jenis Pembayaran -->
            <div class="row col-md-12">
                <div class="input-style-1 col-md-6">
                    <label for="jenis_pembayaran">Jenis Pembayaran</label>
                    <select id="jenis_pembayaran" class="form-control<?= isset(session()->get('errors')['jenis_pembayaran']) ? ' is-invalid' : '' ?>" name="jenis_pembayaran">
                        <option value="" disabled selected>--Jenis Pembayaran--</option>
                        <option value="Lunas" <?= isset(session()->get('data')['jenis_pembayaran']) ? (session()->get('data')['jenis_pembayaran'] == 'Lunas' ? 'selected' : '') : '' ?>>Lunas</option>
                        <option value="Bertahap" <?= isset(session()->get('data')['jenis_pembayaran']) ? (session()->get('data')['jenis_pembayaran'] == 'Bertahap' ? 'selected' : '') : '' ?>>Bertahap</option>
                    </select>
                    <div class="invalid-feedback"><?= isset(session()->get('errors')['jenis_pembayaran']) ? session()->get('errors')['jenis_pembayaran'] : '' ?></div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>