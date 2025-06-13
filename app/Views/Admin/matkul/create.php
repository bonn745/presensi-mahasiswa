<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>

<div class="card col-md-6"> <!-- Menghapus mx-auto untuk menempatkan kartu di sebelah kiri -->
    <div class="card-body">
        <form method="POST" action="<?= base_url('admin/matkul/store') ?>">

            <div class="input-style-1">
                <label for="matkul">Nama Matkul</label>
                <input type="text" id="matkul" class="form-control<?= ($validation->hasError('matkul')) ? ' is-invalid' : '' ?>" name="matkul" placeholder="Nama Matkul" value="<?= old('matkul') ?>" />
                <div class="invalid-feedback"><?= $validation->getError('matkul') ?></div>
            </div>

            <div class="input-style-1">
                <label for="dosen_pengampu">Dosen Pengampu</label>
                <input type="text" id="dosen_pengampu" class="form-control<?= ($validation->hasError('dosen_pengampu')) ? ' is-invalid' : '' ?>" name="dosen_pengampu" placeholder="Nama Dosen" value="<?= old('dosen_pengampu') ?>" />
                <div class="invalid-feedback"><?= $validation->getError('dosen_pengampu') ?></div>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>