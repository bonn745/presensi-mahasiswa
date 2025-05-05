<?= $this->extend('pegawai/layouts.php') ?>

<?= $this->section('content') ?>
<div class="card col-md-6"> <!-- Menghapus mx-auto untuk menempatkan kartu di sebelah kiri -->
    <div class="card-body">
        <form action="<?= base_url('pegawai/kehadiran/store') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" value="<?= session()->get('id_pegawai') ?>" name="id_pegawai">


            <div class="input-style-1">
                <label for="keterangan">Keterangan</label>
                <select id="keterangan" class="form-control<?= ($validation->hasError('keterangan')) ? ' is-invalid' : '' ?>" name="keterangan">
                    <option value disabled selected="">--Pilih Keterangan--</option>
                    <option value="izin" <?= set_select('keterangan', 'izin', ($pegawai['keterangan'] ?? '') == 'izin') ?>>Izin</option>
                    <option value="sakit" <?= set_select('keterangan', 'sakit', ($pegawai['keterangan'] ?? '') == 'sakit') ?>>Sakit</option>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('keterangan') ?></div>
            </div>


            <div class="input-style-1">
                <label for="tanggal">Tanggal</label>
                <input type="date" class="form-control<?= ($validation->hasError('tanggal')) ? ' is-invalid' : '' ?>"
                    name="tanggal" placeholder="tanggal" value="<?= set_value('tanggal', $pegawai['tanggal'] ?? '') ?>" />
                <div class="invalid-feedback"><?= $validation->getError('tanggal') ?></div>
            </div>


            <div class="input-style-1">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" class="form-control<?= ($validation->hasError('deskripsi')) ? ' is-invalid' : '' ?>"
                    name="deskripsi" placeholder="Deskripsi Pegawai" rows="4"><?= set_value('deskripsi', $pegawai['deskripsi'] ?? '') ?></textarea>
                <div class="invalid-feedback"><?= $validation->getError('deskripsi') ?></div>
            </div>


            <div class="input-style-1">
                <label for="file">File</label>
                <input type="file" class="form-control<?= ($validation->hasError('file')) ? ' is-invalid' : '' ?>"
                    name="file" placeholder="No Handphone" value="<?= set_value('file', $pegawai['file'] ?? '') ?>" />
                <div class="invalid-feedback"><?= $validation->getError('file') ?></div>
            </div>

            <button type="submit" class="btn btn-primary">Ajukan</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>