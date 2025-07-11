<?= $this->extend('mahasiswa/layouts.php') ?>

<?= $this->section('content') ?>
<div class="card col-md-6"> <!-- Menghapus mx-auto untuk menempatkan kartu di sebelah kiri -->
    <div class="card-body">
        <form action="<?= base_url('mahasiswa/ketidakhadiran/store') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" value="<?= session()->get('id_mahasiswa') ?>" name="id_mahasiswa">

            <div class="input-style-1">
                <label for="keterangan">Keterangan</label>
                <select id="keterangan" class="form-control<?= ($validation->hasError('keterangan')) ? ' is-invalid' : '' ?>" name="keterangan">
                    <option value disabled selected="">--Pilih Keterangan--</option>
                    <option value="izin" <?= set_select('keterangan', 'izin', ($mahasiswa['keterangan'] ?? '') == 'izin') ?>>Izin</option>
                    <option value="sakit" <?= set_select('keterangan', 'sakit', ($mahasiswa['keterangan'] ?? '') == 'sakit') ?>>Sakit</option>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('keterangan') ?></div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="input-style-1">
                        <label for="tanggal_mulai">Tanggal Mulai</label>
                        <input id="tanggal_mulai" type="date" class="form-control<?= ($validation->hasError('tanggal_mulai')) ? ' is-invalid' : '' ?>"
                            name="tanggal_mulai" value="<?= set_value('tanggal_mulai', $mahasiswa['tanggal_mulai'] ?? '') ?>"/>
                        <div class="invalid-feedback"><?= $validation->getError('tanggal_mulai') ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-style-1">
                        <label for="tanggal_selesai">Tanggal Selesai (optional)</label>
                        <input id="tanggal_selesai" type="date" class="form-control<?= ($validation->hasError('tanggal_selesai')) ? ' is-invalid' : '' ?>"
                            name="tanggal_selesai" value="<?= set_value('tanggal_selesai', $mahasiswa['tanggal_selesai'] ?? '') ?>" />
                        <div class="invalid-feedback"><?= $validation->getError('tanggal_selesai') ?></div>
                    </div>
                </div>
            </div>

            <div id="data-mata-kuliah" class="d-none">

            </div>

            <div class="input-style-1">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" class="form-control<?= ($validation->hasError('deskripsi')) ? ' is-invalid' : '' ?>"
                    name="deskripsi" placeholder="Deskripsi ketidakhadiran" rows="4"><?= set_value('deskripsi', $mahasiswa['deskripsi'] ?? '') ?></textarea>
                <div class="invalid-feedback"><?= $validation->getError('deskripsi') ?></div>
            </div>

            <div class="input-style-1">
                <label for="file">File</label>
                <input type="file" accept="application/pdf, image/png, image/gif, image/jpeg" class="form-control<?= ($validation->hasError('file')) ? ' is-invalid' : '' ?>"
                    name="file" value="<?= set_value('file', $mahasiswa['file'] ?? '') ?>" />
                <div class="invalid-feedback"><?= $validation->getError('file') ?></div>
            </div>

            <button type="submit" class="btn btn-primary">Ajukan</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/mhs.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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