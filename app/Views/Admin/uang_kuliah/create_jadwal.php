<?= $this->extend('admin/layouts.php') ?>
<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="card-body">
        <form action="<?= url_to('admin.uangkuliah.storeJadwal') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="row col-md-12">
                <div class="input-style-1 col-md-4">
                    <div class="mb-3">
                        <label for="tanggal_pembayaran_tahap_1">Tanggal Pembayaran Tahap 1</label>
                        <input type="date" class="form-control <?= isset(session()->get('errors')['tanggal_pembayaran_tahap_1']) ? ' is-invalid' : '' ?>" name="tanggal_pembayaran_tahap_1" id="tanggal_pembayaran_tahap_1">
                        <div class="invalid-feedback"><?= isset(session()->get('errors')['tanggal_pembayaran_tahap_1']) ? session()->get('errors')['tanggal_pembayaran_tahap_1'] : '' ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_notifikasi_tahap_1">Tanggal Notifikasi Tahap 1</label>
                        <input type="date" class="form-control <?= isset(session()->get('errors')['tanggal_notifikasi_tahap_1']) ? ' is-invalid' : '' ?>" name="tanggal_notifikasi_tahap_1" id="tanggal_notifikasi_tahap_1">
                        <div class="invalid-feedback"><?= isset(session()->get('errors')['tanggal_notifikasi_tahap_1']) ? session()->get('errors')['tanggal_notifikasi_tahap_1'] : '' ?></div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="jam_notifikasi_tahap_1" class="form-label">Jam Notifikasi Tahap 1</label>
                        <input type="time" class="form-control" id="jam_notifikasi_tahap_1" name="jam_notifikasi_tahap_1" value="<?= old('jam_notifikasi_tahap_1') ?>" required>
                        <?php if (isset(session()->get('errors')['jam_notifikasi_tahap_1'])): ?>
                            <div class="text-danger"><?= session()->get('errors')['jam_notifikasi_tahap_1'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="input-style-1 col-md-4">
                    <div class="mb-3">
                        <label for="tanggal_pembayaran_tahap_2">Tanggal Pembayaran Tahap 2</label>
                        <input type="date" class="form-control <?= isset(session()->get('errors')['tanggal_pembayaran_tahap_2']) ? ' is-invalid' : '' ?>" name="tanggal_pembayaran_tahap_2" id="tanggal_pembayaran_tahap_2">
                        <div class="invalid-feedback"><?= isset(session()->get('errors')['tanggal_pembayaran_tahap_2']) ? session()->get('errors')['tanggal_pembayaran_tahap_2'] : '' ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_notifikasi_tahap_2">Tanggal Notifikasi Tahap 2</label>
                        <input type="date" class="form-control <?= isset(session()->get('errors')['tanggal_notifikasi_tahap_2']) ? ' is-invalid' : '' ?>" name="tanggal_notifikasi_tahap_2" id="tanggal_notifikasi_tahap_2">
                        <div class="invalid-feedback"><?= isset(session()->get('errors')['tanggal_notifikasi_tahap_2']) ? session()->get('errors')['tanggal_notifikasi_tahap_2'] : '' ?></div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="jam_notifikasi_tahap_2" class="form-label">Jam Tahap 2</label>
                        <input type="time" class="form-control" id="jam_notifikasi_tahap_2" name="jam_notifikasi_tahap_2" value="<?= old('jam_notifikasi_tahap_2') ?>" required>
                        <?php if (isset(session()->get('errors')['jam_notifikasi_tahap_2'])): ?>
                            <div class="text-danger"><?= session()->get('errors')['jam_notifikasi_tahap_2'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="input-style-1 col-md-4">
                    <div class="mb-3">
                        <label for="tanggal_pembayaran_tahap_3">Tanggal Pembayaran Tahap 3</label>
                        <input type="date" class="form-control <?= isset(session()->get('errors')['tanggal_pembayaran_tahap_3']) ? ' is-invalid' : '' ?>" name="tanggal_pembayaran_tahap_3" id="tanggal_pembayaran_tahap_3">
                        <div class="invalid-feedback"><?= isset(session()->get('errors')['tanggal_pembayaran_tahap_3']) ? session()->get('errors')['tanggal_pembayaran_tahap_3'] : '' ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_notifikasi_tahap_3">Tanggal Notifikasi Tahap 3</label>
                        <input type="date" class="form-control <?= isset(session()->get('errors')['tanggal_notifikasi_tahap_3']) ? ' is-invalid' : '' ?>" name="tanggal_notifikasi_tahap_3" id="tanggal_notifikasi_tahap_3">
                        <div class="invalid-feedback"><?= isset(session()->get('errors')['tanggal_notifikasi_tahap_3']) ? session()->get('errors')['tanggal_notifikasi_tahap_3'] : '' ?></div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="jam_notifikasi_tahap_3" class="form-label">Jam Tahap 3</label>
                        <input type="time" class="form-control" id="jam_notifikasi_tahap_3" name="jam_notifikasi_tahap_3" value="<?= old('jam_notifikasi_tahap_3') ?>" required>
                        <?php if (isset(session()->get('errors')['jam_notifikasi_tahap_3'])): ?>
                            <div class="text-danger"><?= session()->get('errors')['jam_notifikasi_tahap_3'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>