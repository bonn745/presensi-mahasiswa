<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="<?= base_url('admin/data_dosen/create') ?>" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Tambah Data
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center" id="datatables">
            <thead class="table-primary">
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 8%;">NIDN</th>
                    <th style="width: 15%;">Nama Dosen</th>
                    <th style="width: 10%;">Jenis Kelamin</th>
                    <th style="width: 10%;">No HP</th>
                    <th style="width: 18%;">Alamat</th>
                    <th style="width: 10%;">Jadwal</th>
                    <th style="width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($dosen as $dsn) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $dsn['nidn'] ?></td>
                        <td id="nama-<?= $dsn['id'] ?>"><?= $dsn['nama_dosen'] ?></td>
                        <td><?= $dsn['jenis_kelamin'] ?></td>
                        <td><?= $dsn['no_hp'] ?></td>
                        <td><?= $dsn['alamat'] ?></td>
                        <td>
                            <button class="btn btn-primary" type="button" id="dosen-<?= $dsn['id'] ?>" data-id="<?= $dsn['id'] ?>" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="refreshJadwal(<?= $dsn['id'] ?>,'<?= url_to('admin.dosen.jadwal', $dsn['id']) ?>')">
                                Lihat
                            </button>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="<?= base_url('admin/data_dosen/detail/' . $dsn['id']) ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                                <a href="<?= base_url('admin/data_dosen/edit/' . $dsn['id']) ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-danger btn-delete" data-id="<?= $dsn['id'] ?>">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="display-jadwal" class="table-responsive mt-5 d-none">
                        <div id="jadwal-dosen" class="fs-5 text-dark text-center text-uppercase fw-bold">
                            Jadwal
                        </div>
                        <table width="100%" class="table table-striped table-bordered text-center" id="jadwal-datatable">
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
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Notifikasi saat data berhasil ditambahkan atau dihapus
    <?php if (session()->getFlashdata('success_add')) : ?>
        Swal.fire({
            icon: "success",
            title: "Berhasil!",
            text: "<?= session()->getFlashdata('success_add') ?>",
            showConfirmButton: false,
            timer: 2000
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('success_delete')) : ?>
        Swal.fire({
            icon: "success",
            title: "Terhapus!",
            text: "<?= session()->getFlashdata('success_delete') ?>",
            showConfirmButton: false,
            timer: 2000
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        Swal.fire({
            icon: "error",
            title: "Import Gagal!",
            text: "<?= session()->getFlashdata('error') ?>",
            showConfirmButton: false,
            timer: 2000
        });
    <?php endif; ?>

    // Konfirmasi sebelum menghapus data
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            let pegawaiId = this.getAttribute('data-id');

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= base_url('admin/data_dosen/delete/') ?>" + pegawaiId;
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/dosen.js') ?>"></script>
<?= $this->endSection() ?>