<?= $this->extend('mahasiswa/layouts.php') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="<?= url_to('mahasiswa.matkul.create') ?>" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Tambah Mata Kuliah
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center" id="datatables">
            <thead class="table-primary">
                <tr>
                    <th rowspan="2" align="center">No</th>
                    <th rowspan="2" align="center">Mata Kuliah</th>
                    <th rowspan="2" align="center">Dosen Pengampu</th>
                    <th rowspan="2" align="center">Ruangan</th>
                    <th colspan="3" align="center">Jadwal</th>
                </tr>
                <tr>
                    <th align="center">Hari</th>
                    <th align="center">Jam Masuk</th>
                    <th align="center">Jam Keluar</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach ($mata_kuliah as $matkul): ?>
                    <tr>
                        <td style="width: 1%; white-space:nowrap"><?= $no++ ?></td>
                        <td align="left">
                            <?= $matkul['matkul'] ?>
                            <strong>
                                <?php if (!empty($matkul['jenis_kelas'])) : ?>
                                    (<?= $matkul['jenis_kelas'] ?>)
                                <?php endif; ?>

                            </strong>
                        </td>
                        <td>
                            <?= $matkul['nama_dosen'] ?>
                        </td>
                        <td align="left">
                            <?php if (!empty($matkul['nama_ruangan'])) : ?>
                                <?php if ( $matkul['jenis_kelas'] == "Luring") : ?>
                                    <?= 'Ruangan ' . $matkul['nama_ruangan'] ?><br>
                                    <?= $matkul['tipe_lokasi'] ?><br>
                                    <?= $matkul['alamat_lokasi'] ?>
                                <?php else : ?>
                                    -
                                <?php endif; ?>
                            <?php else : ?>
                                Belum ada kelas.
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($matkul['hari'])) : ?>
                                <?= $matkul['hari'] ?>
                            <?php else : ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($matkul['jam_masuk'])) : ?>
                                <?= date('H:i', strtotime($matkul['jam_masuk'])) ?>
                            <?php else : ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($matkul['jam_masuk'])) : ?>
                                <?= date('H:i', strtotime($matkul['jam_pulang'])) ?>
                            <?php else : ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?= $this->endSection() ?>