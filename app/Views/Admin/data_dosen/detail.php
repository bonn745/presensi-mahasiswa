<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>
<div class="card col-md-8 mx-auto mt-4">
    <div class="card-body">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td><strong>NIDN</strong></td>
                    <td>:</td>
                    <td><?= esc($dosen['nidn']) ?></td>
                </tr>
                <tr>
                    <td><strong>Nama Dosen</strong></td>
                    <td>:</td>
                    <td><?= esc($dosen['nama_dosen']) ?></td>
                </tr>
                <tr>
                    <td><strong>Username</strong></td>
                    <td>:</td>
                    <td><?= esc($dosen['username']) ?></td>
                </tr>
                <tr>
                    <td><strong>Jenis Kelamin</strong></td>
                    <td>:</td>
                    <td><?= esc($dosen['jenis_kelamin']) ?></td>
                </tr>
                <tr>
                    <td><strong>No Handphone</strong></td>
                    <td>:</td>
                    <td><?= esc($dosen['no_hp']) ?></td>
                </tr>
                <tr>
                    <td><strong>Alamat</strong></td>
                    <td>:</td>
                    <td><?= esc($dosen['alamat']) ?></td>
                </tr>
                <tr>
                    <td><strong>Jadwal Ngajar</strong></td>
                    <td>:</td>
                    <td>
                        <?php if (!empty($jadwal_ngajar)) : ?>
                            <ul class="mb-0">
                                <?php foreach ($jadwal_ngajar as $item) : ?>
                                    <li>
                                        <?= esc($item['hari']) ?> - <?= esc($item['nama_matkul']) ?>
                                        (<?= esc($item['jam_masuk']) ?> - <?= esc($item['jam_pulang']) ?>)
                                        di Ruangan <?= esc($item['ruangan']) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <em>Tidak ada jadwal mengajar</em>
                        <?php endif; ?>
                    </td>
                </tr>


                <tr>
                    <td><strong>Role</strong></td>
                    <td>:</td>
                    <td><?= esc($dosen['role']) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<a href="<?= base_url('admin/data_dosen') ?>" class="btn btn-secondary mt-3">Kembali</a>

<?= $this->endSection() ?>