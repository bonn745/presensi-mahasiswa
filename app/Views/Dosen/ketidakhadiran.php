<?= $this->extend('dosen/layouts.php') ?>

<?= $this->section('content') ?>

<!-- Statistik Cards -->
<div class="row g-3 bg-white h-screen">
    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center" id="datatables">
            <thead class="table-primary">
                <tr>
                    <th style="width: 1%; white-space:nowrap;">No</th>
                    <th>Mata Kuliah</th>
                    <th style="text-align: center;">Nama</th>
                    <th style="text-align: center;">Tanggal</th>
                    <th style="text-align: center;">Keterangan</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($ketidakhadiran as $x) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td style="width: 1%; white-space:nowrap;" align="left"><?= $x['matkul'] ?></td>
                        <td><?= $x['nama'] ?></td>
                        <td><?= Carbon\Carbon::createFromFormat('Y-m-d', $x['tanggal'])->locale('id')->translatedFormat('l, j F Y') ?></td>
                        <td>
                            <?= $x['keterangan'] . ' - ' . $x['deskripsi'] ?>
                        </td>
                        <td>
                            <?php if (!empty($x['file'])) : ?>
                                <img src="<?= base_url('file_ketidakhadiran/' . $x['file']) ?>"
                                    alt="Foto Mahasiswa"
                                    style="width: 60px; height: 60px; object-fit: cover;">
                            <?php else : ?>
                                <span class="text-muted">Tidak Ada Foto</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($x['status_pengajuan'] == 'Pending'): ?>
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-success btn-terima me-3" data-id="<?= $x['id'] ?>">
                                        Terima
                                    </button>
                                    <button class="btn btn-danger btn-tolak" data-id="<?= $x['id'] ?>">
                                        Tolak
                                    </button>
                                </div>
                            <?php else : ?>
                                <?= $x['status_pengajuan'] == 'Accept' ? '<i class="fa fa-check text-success"></i> Diterima' : '<i class="fa fa-xmark text-danger"></i> Ditolak' ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="application/javascript">
    document.querySelectorAll('.btn-terima').forEach(button => {
        button.addEventListener('click', function() {
            let permohonanId = this.getAttribute('data-id');

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Permohonan akan diterima!",
                icon: "success",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, terima!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= base_url('dosen/ketidakhadiran/terima/') ?>" + permohonanId;
                }
            });
        });
    });
    document.querySelectorAll('.btn-tolak').forEach(button => {
        button.addEventListener('click', function() {
            let permohonanId = this.getAttribute('data-id');

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Permohonan akan ditolak!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, tolak!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= base_url('dosen/ketidakhadiran/tolak/') ?>" + permohonanId;
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>