<?= $this->extend('mahasiswa/layouts.php') ?>
<?= $this->section('content') ?>

<div class="card col-md-6">
    <div class="card-body">
        <form action="<?= base_url('mahasiswa/ketidakhadiran/update/' . $ketidakhadiran['id']) ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id_mahasiswa" value="<?= session()->get('id_mahasiswa') ?>">

            <!-- Keterangan -->
            <div class="input-style-1">
                <label for="keterangan">Keterangan</label>
                <select id="keterangan" class="form-control<?= ($validation->hasError('keterangan')) ? ' is-invalid' : '' ?>" name="keterangan">
                    <option value="">--Pilih Keterangan--</option>
                    <option value="izin" <?= set_select('keterangan', 'izin', $ketidakhadiran['keterangan'] == 'izin') ?>>Izin</option>
                    <option value="sakit" <?= set_select('keterangan', 'sakit', $ketidakhadiran['keterangan'] == 'sakit') ?>>Sakit</option>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('keterangan') ?></div>
            </div>

            <!-- Periode Ketidakhadiran -->
            <div class="row">
                <div class="col-md-6">
                    <div class="input-style-1">
                        <label for="tanggal_mulai">Tanggal Mulai</label>
                        <input type="date" class="form-control<?= ($validation->hasError('tanggal_mulai')) ? ' is-invalid' : '' ?>"
                            name="tanggal_mulai" value="<?= set_value('tanggal_mulai', $ketidakhadiran['tanggal_mulai']) ?>" />
                        <div class="invalid-feedback"><?= $validation->getError('tanggal_mulai') ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-style-1">
                        <label for="tanggal_selesai">Tanggal Selesai</label>
                        <input type="date" class="form-control<?= ($validation->hasError('tanggal_selesai')) ? ' is-invalid' : '' ?>"
                            name="tanggal_selesai" value="<?= set_value('tanggal_selesai', $ketidakhadiran['tanggal_selesai']) ?>" />
                        <div class="invalid-feedback"><?= $validation->getError('tanggal_selesai') ?></div>
                    </div>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="input-style-1">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" class="form-control<?= ($validation->hasError('deskripsi')) ? ' is-invalid' : '' ?>"
                    name="deskripsi" rows="4"><?= set_value('deskripsi', $ketidakhadiran['deskripsi']) ?></textarea>
                <div class="invalid-feedback"><?= $validation->getError('deskripsi') ?></div>
            </div>

            <!-- File Upload -->
            <div class="input-style-1">
                <label for="file">File (Opsional)</label>
                <input type="file" class="form-control<?= ($validation->hasError('file')) ? ' is-invalid' : '' ?>" name="file" />
                <div class="invalid-feedback"><?= $validation->getError('file') ?></div>

                <?php if (!empty($ketidakhadiran['file'])) : ?>
                    <small class="form-text text-muted mt-1">
                        File saat ini:
                        <a href="<?= base_url('file_ketidakhadiran/' . $ketidakhadiran['file']) ?>" target="_blank">Lihat File</a>
                    </small>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Update</button>
            <a href="<?= base_url('mahasiswa/ketidakhadiran') ?>" class="btn btn-secondary mt-3 ml-2">Kembali</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>