<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>

<div class="card col-md-6"> <!-- Menghapus mx-auto untuk menempatkan kartu di sebelah kiri -->
    <div class="card-body">
        <form method="POST" action="<?= base_url('admin/jabatan/store') ?>">

            <div class="input-style-1">
                <label for="jabatan">Nama Jabatan</label>
                <input type="text" id="jabatan" class="form-control<?= ($validation->hasError('jabatan')) ? ' is-invalid' : '' ?>" name="jabatan" placeholder="Nama Jabatan" value="<?= old('jabatan') ?>" />
                <div class="invalid-feedback"><?= $validation->getError('jabatan') ?></div>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>