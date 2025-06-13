<?= $this->extend('admin/layouts') ?>

<?= $this->section('content') ?>

<div class="card col-md-6">
    <div class="card-body">
        <form action="<?= base_url('admin/kelas/update/' . $kelas['id']) ?>" method="POST">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="ruangan" class="form-label">Ruangan</label>
                <input type="text" class="form-control" id="ruangan" name="ruangan" value="<?= old('ruangan', $kelas['ruangan']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="hari" class="form-label">Hari</label>
                <select class="form-select" id="hari" name="hari" required>
                    <option value="Senin" <?= old('hari', $kelas['hari']) == 'Senin' ? 'selected' : '' ?>>Senin</option>
                    <option value="Selasa" <?= old('hari', $kelas['hari']) == 'Selasa' ? 'selected' : '' ?>>Selasa</option>
                    <option value="Rabu" <?= old('hari', $kelas['hari']) == 'Rabu' ? 'selected' : '' ?>>Rabu</option>
                    <option value="Kamis" <?= old('hari', $kelas['hari']) == 'Kamis' ? 'selected' : '' ?>>Kamis</option>
                    <option value="Jumat" <?= old('hari', $kelas['hari']) == 'Jumat' ? 'selected' : '' ?>>Jumat</option>
                    <option value="Sabtu" <?= old('hari', $kelas['hari']) == 'Sabtu' ? 'selected' : '' ?>>Sabtu</option>
                    <option value="Minggu" <?= old('hari', $kelas['hari']) == 'Minggu' ? 'selected' : '' ?>>Minggu</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="jam_masuk" class="form-label">Jam Masuk</label>
                <input type="time" class="form-control" id="jam_masuk" name="jam_masuk" value="<?= old('jam_masuk', $kelas['jam_masuk']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="jam_pulang" class="form-label">Jam Pulang</label>
                <input type="time" class="form-control" id="jam_pulang" name="jam_pulang" value="<?= old('jam_pulang', $kelas['jam_pulang']) ?>" required>
            </div>

            <div class="mb-3">
                <select name="matkul" class="form-control">
                    <option value="" disabled <?= empty($kelas['matkul']) ? 'selected' : '' ?>>-- Pilih Mata Kuliah --</option>
                    <?php foreach ($matkul as $m): ?>
                        <option value="<?= esc($m['matkul']) ?>" <?= (isset($kelas['matkul']) && $kelas['matkul'] === $m['matkul']) ? 'selected' : '' ?>>
                            <?= esc($m['matkul']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>