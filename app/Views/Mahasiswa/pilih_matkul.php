<?= $this->extend('mahasiswa/layouts.php') ?>

<?= $this->section('content') ?>
<div class="card col-md-6"> <!-- Menghapus mx-auto untuk menempatkan kartu di sebelah kiri -->
    <div class="card-body">
        <form action="<?= url_to('mahasiswa.matkul.store') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" value="<?= session()->get('id_mahasiswa') ?>" name="id_mahasiswa">
            <?php if (isset($info)) : ?>
                <div class="alert alert-warning" role="alert">
                    <?= $info ?>
                </div>
            <?php endif; ?>
            <div class="input-style-1" id="matkul">
                <label for="matkul">Mata Kuliah</label>
                <select id="matkul" name="matkul[]" class="select2 form-control<?= isset(session()->getFlashdata('errors')['matkul']) ? ' is-invalid' : '' ?>" multiple>
                    <?php foreach ($matkul as $mtk): ?>
                        <option value="<?= $mtk['id'] ?>" <?= (isset(session()->getFlashdata('data')['matkul']) ? session()->getFlashdata('data')['matkul'] : '') == $mtk['id'] ? 'selected' : '' ?>><?= $mtk['matkul'] ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback"><?= session()->getFlashdata('error') ?? '' ?></div>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/select2/css/select2.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/select2/js/select2.min.js') ?>"></script>
<script src="<?= base_url('assets/js/matkul.js') ?>"></script>
<?= $this->endSection() ?>