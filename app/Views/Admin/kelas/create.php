<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>

<div class="card col-md-6">
    <div class="card-body">

        <form action="<?= base_url('admin/kelas/store') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <?php $errors = session()->getFlashdata('errors'); ?>

            <div class="mb-3">
                <label for="ruangan" class="form-label">Ruangan</label>
                <input type="text" class="form-control" id="ruangan" name="ruangan" value="<?= old('ruangan') ?>" required>
                <?php if (isset($errors['ruangan'])): ?>
                    <div class="text-danger"><?= $errors['ruangan'] ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="hari" class="form-label">Hari</label>
                <select class="form-select" id="hari" name="hari" required>
                    <option value="">Pilih Hari</option>
                    <?php
                    $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                    foreach ($hariList as $h): ?>
                        <option value="<?= $h ?>" <?= old('hari') == $h ? 'selected' : '' ?>><?= $h ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['hari'])): ?>
                    <div class="text-danger"><?= $errors['hari'] ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="jam_masuk" class="form-label">Jam Masuk</label>
                <input type="time" class="form-control" id="jam_masuk" name="jam_masuk" value="<?= old('jam_masuk') ?>" required>
                <?php if (isset($errors['jam_masuk'])): ?>
                    <div class="text-danger"><?= $errors['jam_masuk'] ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="jam_pulang" class="form-label">Jam Pulang</label>
                <input type="time" class="form-control" id="jam_pulang" name="jam_pulang" value="<?= old('jam_pulang') ?>" required>
                <?php if (isset($errors['jam_pulang'])): ?>
                    <div class="text-danger"><?= $errors['jam_pulang'] ?></div>
                <?php endif; ?>
            </div>

            <select name="matkul" class="form-control">
                <option value="">-- Pilih Mata Kuliah --</option>
                <?php foreach ($matkul as $m): ?>
                    <option value="<?= esc($m['matkul']) ?>"><?= esc($m['matkul']) ?></option>
                <?php endforeach; ?>
            </select>


            <button type="submit" class="btn btn-primary">Simpan Jadwal Kelas</button>
        </form>

    </div>
</div>

<?= $this->endSection() ?>