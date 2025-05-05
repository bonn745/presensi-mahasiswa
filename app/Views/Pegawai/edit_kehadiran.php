<?= $this->extend('pegawai/layouts.php') ?>
<?= $this->section('content') ?>

<div class="card col-md-6">
    <div class="card-body">
        <form action="<?= base_url('pegawai/kehadiran/update/' . $kehadiran['id']) ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id_pegawai" value="<?= session()->get('id_pegawai') ?>">

            <!-- Keterangan -->
            <div class="input-style-1">
                <label for="keterangan">Keterangan</label>
                <select id="keterangan" class="form-control<?= ($validation->hasError('keterangan')) ? ' is-invalid' : '' ?>" name="keterangan">
                    <option value="">--Pilih Keterangan--</option>
                    <option value="izin" <?= set_select('keterangan', 'izin', $kehadiran['keterangan'] == 'izin') ?>>Izin</option>
                    <option value="sakit" <?= set_select('keterangan', 'sakit', $kehadiran['keterangan'] == 'sakit') ?>>Sakit</option>
                </select>
                <div class="invalid-feedback"><?= $validation->getError('keterangan') ?></div>
            </div>

            <!-- Tanggal -->
            <div class="input-style-1">
                <label for="tanggal">Tanggal</label>
                <input type="date" class="form-control<?= ($validation->hasError('tanggal')) ? ' is-invalid' : '' ?>"
                    name="tanggal" value="<?= set_value('tanggal', $kehadiran['tanggal']) ?>" />
                <div class="invalid-feedback"><?= $validation->getError('tanggal') ?></div>
            </div>

            <!-- Deskripsi -->
            <div class="input-style-1">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" class="form-control<?= ($validation->hasError('deskripsi')) ? ' is-invalid' : '' ?>"
                    name="deskripsi" rows="4"><?= set_value('deskripsi', $kehadiran['deskripsi']) ?></textarea>
                <div class="invalid-feedback"><?= $validation->getError('deskripsi') ?></div>
            </div>

            <!-- File Upload -->
            <div class="input-style-1">
                <label for="file">File (Opsional)</label>
                <input type="file" class="form-control<?= ($validation->hasError('file')) ? ' is-invalid' : '' ?>" name="file" />
                <div class="invalid-feedback"><?= $validation->getError('file') ?></div>

                <?php if (!empty($kehadiran['file'])) : ?>
                    <small class="form-text text-muted mt-1">
                        File saat ini:
                        <a href="<?= base_url('file_kehadiran/' . $kehadiran['file']) ?>" target="_blank">Lihat File</a>
                    </small>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Update</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>