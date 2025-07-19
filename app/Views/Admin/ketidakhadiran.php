<?= $this->extend('admin/layouts.php') ?>
<?= $this->section('content') ?>

<?php

use Carbon\Carbon;

Carbon::setLocale('id');
?>

<div class="container mt-4">
    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="datatables">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Nim</th>
                    <th>Nama</th>
                    <th>Keterangan</th>
                    <th>Mata Kuliah</th>
                    <th>Periode</th>
                    <th>Deskripsi</th>
                    <th>File</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($ketidakhadiran) : ?>
                    <?php $no = 1;
                    foreach ($ketidakhadiran as $row) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($row['npm']); ?></td>
                            <td><?= esc($row['nama']); ?></td>
                            <td><?= ucfirst($row['keterangan']) ?></td>
                            <td><?= ucfirst($row['mata_kuliah']) ?></td>
                            <td>
                                <?= Carbon::parse($row['tanggal'])->translatedFormat('d F Y') ?>
                            </td>
                            <td><?= $row['deskripsi'] ?></td>
                            <td class="text-center">
                                <?php if (!empty($row['file'])) : ?>
                                    <a class="badge bg-info text-white" href="<?= base_url('file_ketidakhadiran/' . $row['file']) ?>" target="_blank">
                                        Lihat File
                                    </a>
                                <?php else : ?>
                                    <span class="text-muted">Tidak ada file</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($row['status_pengajuan'] === 'Approved') : ?>
                                    <span class="badge bg-success text-white">
                                        <i class="fas fa-check-circle"></i> <?= $row['status_pengajuan'] ?>
                                    </span>
                                <?php elseif ($row['status_pengajuan'] === 'Pending') : ?>
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-hourglass-half"></i> <?= $row['status_pengajuan'] ?>
                                    </span>
                                <?php elseif ($row['status_pengajuan'] === 'Rejected') : ?>
                                    <span class="badge bg-danger text-white">
                                        <i class="fas fa-times-circle"></i> <?= $row['status_pengajuan'] ?>
                                    </span>
                                <?php else : ?>
                                    <span class="badge bg-secondary"><?= $row['status_pengajuan'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($row['status_pengajuan'] === 'Pending') : ?>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a class="badge bg-success text-decoration-none" href="<?= base_url('admin/approved_ketidakhadiran/' . $row['id']) ?>" title="Setujui Pengajuan">
                                            <i class="fas fa-check-circle"></i> Approved
                                        </a>
                                        <a class="badge bg-danger text-decoration-none" href="<?= base_url('admin/rejected_ketidakhadiran/' . $row['id']) ?>" title="Tolak Pengajuan">
                                            <i class="fas fa-times-circle"></i> Rejected
                                        </a>
                                    </div>
                                <?php else : ?>
                                    <a class="badge bg-danger text-decoration-none" href="<?= base_url('admin/delete_ketidakhadiran/' . $row['id']) ?>" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')" title="Hapus Data">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="9" class="text-center">Data Masih Kosong</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Inisialisasi DataTables
    $(document).ready(function() {
        $('#datatables').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            columnDefs: [{
                    orderable: false,
                    targets: [6, 8]
                }, // Kolom file dan aksi tidak bisa diurutkan
                {
                    searchable: false,
                    targets: [0, 6, 8]
                } // Kolom nomor, file, dan aksi tidak bisa dicari
            ]
        });
    });

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

    // Konfirmasi sebelum menghapus data
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            let id = this.getAttribute('data-id');

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
                    window.location.href = "<?= base_url('admin/delete_ketidakhadiran/') ?>" + id;
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>