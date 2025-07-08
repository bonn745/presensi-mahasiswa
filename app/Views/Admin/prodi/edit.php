<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>

<div class="card col-md-6">
    <div class="card-body">
        <form method="POST" action="<?= base_url('admin/prodi/update/' . $prodi['id']) ?>">
            <div class="input-style-1">
                <label for="prodi">Nama Program Studi</label>
                <input type="text" id="prodi" class="form-control<?= ($validation->hasError('prodi')) ? ' is-invalid' : '' ?>"
                    name="prodi" placeholder="Nama Program Studi" value="<?= $prodi['nama'] ?>" />
                <div class="invalid-feedback"><?= $validation->getError('prodi') ?></div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>