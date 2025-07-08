<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>

<div class="card col-md-6"> <!-- Menghapus mx-auto untuk menempatkan kartu di sebelah kiri -->
    <div class="card-body">
        <form method="POST" action="<?= base_url('admin/matkul/store') ?>">
            <div class="input-style-1">
                <label for="prodi">Program Studi</label>
                <select id="prodi" name="prodi" class="form-control" required>
                    <option value="0" selected disabled>-- Pilih Program Studi --</option>
                    <?php foreach ($prodi as $prd) : ?>
                        <option value="<?= $prd['id'] ?>"><?= $prd['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-style-1">
                <label for="matkul">Mata Kuliah</label>
                <input type="text" id="matkul" class="form-control<?= ($validation->hasError('matkul')) ? ' is-invalid' : '' ?>" name="matkul" placeholder="Nama Mata Kuliah" value="<?= old('matkul') ?>" required />
                <div class="invalid-feedback"><?= $validation->getError('matkul') ?></div>
            </div>

            <div class="input-style-1">
                <label for="dosen_pengampu">Dosen Pengampu</label>
                <select id="dosen_pengampu" name="dosen_pengampu" class="form-control" required>
                    <option value="0" selected disabled>-- Pilih Dosen --</option>
                    <?php foreach ($dosen as $dsn) : ?>
                        <option value="<?= $dsn['id'] ?>"><?= $dsn['nama_dosen'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href=" <?= base_url('assets/select2/css/select2.min.css') ?>" />
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="application/javascript">
    <?php if (session()->getFlashdata('error')) : ?>
        Swal.fire({
            icon: "error",
            title: "Import Gagal!",
            text: "<?= session()->getFlashdata('error') ?>",
            showConfirmButton: false,
            timer: 2000
        });
    <?php endif; ?>
</script>
<?= $this->endSection() ?>