<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>
<div class="card col-md-8 mx-auto mt-4">
    <div class="card-body">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td><strong>Foto</strong></td>
                    <td>:</td>
                    <td>
                        <img width="200px" src="<?= base_url('profile/' . $mahasiswa['foto']) ?>" alt="Foto Mahasiswa" class="img-thumbnail">
                    </td>
                </tr>
                <tr>
                    <td><strong>NIM</strong></td>
                    <td>:</td>
                    <td><?= esc($mahasiswa['nim']) ?></td>
                </tr>
                <tr>
                    <td><strong>Nama Lengkap</strong></td>
                    <td>:</td>
                    <td><?= esc($mahasiswa['nama']) ?></td>
                </tr>
                <tr>
                    <td><strong>Username</strong></td>
                    <td>:</td>
                    <td><?= esc($mahasiswa['username']) ?></td>
                </tr>
                <tr>
                    <td><strong>Jenis Kelamin</strong></td>
                    <td>:</td>
                    <td><?= esc($mahasiswa['jenis_kelamin']) ?></td>
                </tr>
                <tr>
                    <td><strong>Alamat</strong></td>
                    <td>:</td>
                    <td><?= esc($mahasiswa['alamat']) ?></td>
                </tr>
                <tr>
                    <td><strong>No Handphone</strong></td>
                    <td>:</td>
                    <td><?= esc($mahasiswa['no_handphone']) ?></td>
                </tr>
                <tr>
                    <td><strong>Lokasi Presensi</strong></td>
                    <td>:</td>
                    <td><?= esc($mahasiswa['lokasi_presensi']) ?></td>
                </tr>
                <tr>
                    <td><strong>Role</strong></td>
                    <td>:</td>
                    <td><?= esc($mahasiswa['role']) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<a href="<?= base_url('admin/data_mahasiswa') ?>" class="btn btn-secondary mt-3">Kembali</a>

<?= $this->endSection() ?>