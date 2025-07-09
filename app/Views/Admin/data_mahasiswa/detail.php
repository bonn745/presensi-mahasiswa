<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>
<div class="card col-md-8 mx-auto mt-4">
    <div class="card-body">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td colspan="3" align="center">
                        <strong>Mahasiswa</strong>
                    </td>
                </tr>
                <tr>
                    <td><strong>Foto</strong></td>
                    <td>:</td>
                    <td>
                        <?php if (!empty($mahasiswa['foto'])) : ?>
                            <img width="200px" src="<?= base_url('profile/' . $mahasiswa['foto']) ?>" alt="Foto Mahasiswa" class="img-thumbnail">
                        <?php else : ?>
                            Tidak ada foto.
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>NPM</strong></td>
                    <td>:</td>
                    <td><?= esc($mahasiswa['npm']) ?></td>
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
                    <td><strong>Role</strong></td>
                    <td>:</td>
                    <td><?= esc($mahasiswa['role']) ?></td>
                </tr>
                <tr>
                    <td colspan="3" align="center">
                        <hr>
                        <strong>Orang Tua</strong>
                    </td>
                </tr>
                <tr>
                    <td><strong>Nama</strong></td>
                    <td>:</td>
                    <td><?= esc($mahasiswa['nama_ortu']) ?></td>
                </tr>
                <tr>
                    <td><strong>Jenis Kelamin</strong></td>
                    <td>:</td>
                    <td><?= esc($mahasiswa['jk_ortu']) ?></td>
                </tr>
                <tr>
                    <td style="width: 1%; white-space:nowrap; padding-right: 10px"><strong>No Handphone</strong></td>
                    <td>:</td>
                    <td><?= esc($mahasiswa['nohp_ortu']) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<a href="<?= base_url('admin/data_mahasiswa') ?>" class="btn btn-secondary mt-3">Kembali</a>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    td {
        border: none;
    }
</style>
<?= $this->endSection() ?>