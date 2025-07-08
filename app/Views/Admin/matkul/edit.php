<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>

<div class="card col-md-6"> <!-- Menghapus mx-auto untuk menempatkan kartu di sebelah kiri -->
    <div class="card-body">
        <form method="POST" action="<?= base_url('admin/matkul/update/' . $matkul['id']) ?>">
            <div class="input-style-1">
                <label for="prodi">Program Studi</label>
                <select id="prodi" name="prodi" class="form-control" required>
                    <option value="0" selected disabled>-- Pilih Program Studi --</option>
                    <?php foreach ($prodi as $prd) : ?>
                        <option value="<?= $prd['id'] ?>" <?= $prd['id'] == $matkul['prodi_id'] ? 'Selected' : '' ?>><?= $prd['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-style-1">
                <label for="matkul">Mata Kuliah</label>
                <input type="text" id="matkul" class="form-control<?= ($validation->hasError('matkul')) ? ' is-invalid' : '' ?>"
                    name="matkul" placeholder="Nama Mata Kuliah" value="<?= $matkul['matkul'] ?>" />
                <div class="invalid-feedback"><?= $validation->getError('matkul') ?></div>
            </div>
            <div class="input-style-1">
                <label for="dosen_pengampu">Dosen Pengampu</label>
                <select id="dosen_pengampu" name="dosen_pengampu" class="form-control" required>
                    <option value="0" selected disabled>-- Pilih Dosen --</option>
                    <?php foreach ($dosen as $dsn) : ?>
                        <option value="<?= $dsn['id'] ?>" <?= $dsn['id'] == $matkul['dosen_pengampu'] ? 'Selected' : '' ?>><?= $dsn['nama_dosen'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>