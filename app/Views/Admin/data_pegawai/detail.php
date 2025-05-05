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
                        <img width="200px" src="<?= base_url('profile/' . $pegawai['foto']) ?>" alt="Foto Pegawai" class="img-thumbnail">
                    </td>
                </tr>
                <tr>
                    <td><strong>NIP</strong></td>
                    <td>:</td>
                    <td><?= esc($pegawai['nip']) ?></td>
                </tr>
                <tr>
                    <td><strong>Nama</strong></td>
                    <td>:</td>
                    <td><?= esc($pegawai['nama']) ?></td>
                </tr>
                <tr>
                    <td><strong>Username</strong></td>
                    <td>:</td>
                    <td><?= esc($pegawai['username']) ?></td>
                </tr>
                <tr>
                    <td><strong>Jenis Kelamin</strong></td>
                    <td>:</td>
                    <td><?= esc($pegawai['jenis_kelamin']) ?></td>
                </tr>
                <tr>
                    <td><strong>Alamat</strong></td>
                    <td>:</td>
                    <td><?= esc($pegawai['alamat']) ?></td>
                </tr>
                <tr>
                    <td><strong>No Handphone</strong></td>
                    <td>:</td>
                    <td><?= esc($pegawai['no_handphone']) ?></td>
                </tr>
                <tr>
                    <td><strong>Jabatan</strong></td>
                    <td>:</td>
                    <td><?= esc($pegawai['jabatan']) ?></td>
                </tr>
                <tr>
                    <td><strong>Lokasi Presensi</strong></td>
                    <td>:</td>
                    <td><?= esc($pegawai['lokasi_presensi']) ?></td>
                </tr>
                <tr>
                    <td><strong>Role</strong></td>
                    <td>:</td>
                    <td><?= esc($pegawai['role']) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<a href="<?= base_url('admin/data_pegawai') ?>" class="btn btn-secondary mt-3">Kembali</a>

<?= $this->endSection() ?>