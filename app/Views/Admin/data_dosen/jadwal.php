<?= $this->extend('admin/layouts.php') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Jadwal Mengajar: <?= $jadwal[0]['nama_dosen'] ?? 'Tidak ditemukan' ?></h4>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center" id="datatables">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Hari</th>
                    <th>Mata Kuliah</th>
                    <th>Ruangan</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($jadwal)) : ?>
                    <?php $no = 1;
                    foreach ($jadwal as $row) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['hari'] ?></td>
                            <td><?= $row['nama_matkul'] ?></td>
                            <td><?= $row['ruangan'] ?></td>
                            <td><?= $row['jam_masuk'] ?></td>
                            <td><?= $row['jam_pulang'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="text-center">Jadwal tidak ditemukan</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="<?= base_url('admin/data_dosen') ?>" class="btn btn-secondary mt-3">Kembali</a>
    </div>
</div>

<?= $this->endSection() ?>