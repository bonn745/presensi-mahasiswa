<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>

<div class="card col-md-6">
    <div class="card-body">
        <form method="POST" action="<?= base_url('admin/prodi/store') ?>">

            <div class="input-style-1">
                <label for="prodi">Nama Program Studi</label>
                <input type="text" id="maproditkul" class="form-control<?= ($validation->hasError('prodi')) ? ' is-invalid' : '' ?>" name="prodi" placeholder="Program Studi" value="<?= old('prodi') ?>" required />
                <div class="invalid-feedback"><?= $validation->getError('prodi') ?></div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="application/javascript">
    <?php if (session()->getFlashdata('error')) : ?>
        Swal.fire({
            icon: "error",
            title: "Error!",
            text: "<?= session()->getFlashdata('error') ?>",
            showConfirmButton: false,
            timer: 2000
        });
    <?php endif; ?>
</script>
<?= $this->endSection() ?>