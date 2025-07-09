<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>

<div class="card col-md-6">
    <div class="card-body">

        <form action="<?= base_url('admin/kelas/store') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <?php $errors = session()->getFlashdata('errors'); ?>

            <div class="mb-3">
                <label for="ruangan" class="form-label">Ruangan</label>
                <select id="ruangan" name="ruangan" class="form-control">
                    <option value="" selected disabled>-- Pilih Ruangan --</option>
                    <?php foreach ($ruangan as $r): ?>
                        <option value="<?= $r['id'] ?>"><?= $r['nama_ruangan'].' - '.$r['tipe_lokasi'].' - '.$r['alamat_lokasi'] ?></option>
                    <?php endforeach; ?>
                </select>
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

            <div class="mb-3">
                <label for="matkul" class="form-label">Mata Kuliah</label>
                <select id="matkul" name="matkul" class="form-control">
                    <option value="" selected disabled>-- Pilih Mata Kuliah --</option>
                    <?php foreach ($matkul as $m): ?>
                        <option value="<?= esc($m['id']) ?>"><?= esc($m['matkul']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="jenis_kelas" class="form-label">Jenis Kelas</label>
                <select id="jenis_kelas" name="jenis_kelas" class="form-control">
                    <option value="" selected disabled>-- Pilih Jenis Kelas --</option>
                        <option value="Daring">Daring</option>
                        <option value="Luring">Luring</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Simpan Jadwal Kelas</button>
        </form>
    </div>
</div>
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