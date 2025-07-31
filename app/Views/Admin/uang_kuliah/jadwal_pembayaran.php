<?= $this->extend('admin/layouts.php') ?>
<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-start gap-3 align-items-center mb-4">
        <a href="<?= url_to('admin.uangkuliah.createjadwal') ?>" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Tambah Data
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center" id="datatables">
            <thead class="table-primary">
                <tr>
                    <th style="width: 1%; white-space: nowrap;">No</th>
                    <th>Pembayaran Tahap 1</th>
                    <th>Pembayaran Tahap 2</th>
                    <th>Pembayaran Tahap 3</th>
                    <th>Notifikasi Tahap 1</th>
                    <th>Notifikasi Tahap 2</th>
                    <th>Notifikasi Tahap 3</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $index = 1;
                foreach ($jadwal as $j) :
                ?>
                    <tr>
                        <td style="vertical-align: top;"><?= $index; ?></td>
                        <td style="vertical-align: top;"><?= Carbon\Carbon::createFromDate($j['tanggal_pembayaran_tahap_1'])->locale('id')->translatedFormat('l, j F Y') ?></td>
                        <td style="vertical-align: top;"><?= Carbon\Carbon::createFromDate($j['tanggal_pembayaran_tahap_2'])->locale('id')->translatedFormat('l, j F Y') ?></td>
                        <td style="vertical-align: top;"><?= Carbon\Carbon::createFromDate($j['tanggal_pembayaran_tahap_3'])->locale('id')->translatedFormat('l, j F Y') ?></td>
                        <td align="left" style="vertical-align: top;">
                            <?= Carbon\Carbon::createFromDate($j['tanggal_notifikasi_tahap_1'])->locale('id')->translatedFormat('l, j F Y') ?>
                            <br>
                            <strong>Status: <?= $j['status_jadwal'] == 'Aktif' ? $j['status_notifikasi_tahap_1'] : 'Nonaktif' ?></strong>
                            <?php if ($j['status_jadwal'] == 'Aktif' && $j['status_notifikasi_tahap_1'] == 'Pending') : ?>
                                <button type="button" class="btn btn-primary mb-3" onclick="kirim(<?= $j['id'] ?>,1)" id="<?= $j['id'] . '-1' ?>">Kirim</button>
                            <?php endif; ?>
                        </td>
                        <td align="left" style="vertical-align: top;">
                            <?= Carbon\Carbon::createFromDate($j['tanggal_notifikasi_tahap_2'])->locale('id')->translatedFormat('l, j F Y') ?>
                            <br>
                            <strong>Status: <?= $j['status_jadwal'] == 'Aktif' ? $j['status_notifikasi_tahap_2'] : 'Nonaktif' ?></strong>
                            <?php if ($j['status_jadwal'] == 'Aktif' && $j['status_notifikasi_tahap_2'] == 'Pending') : ?>
                                <button type="button" class="btn btn-primary mb-3" onclick="kirim(<?= $j['id'] ?>,2)" id="<?= $j['id'] . '-2' ?>">Kirim</button>
                            <?php endif; ?>
                        </td>
                        <td align="left" style="vertical-align: top;">
                            <?= Carbon\Carbon::createFromDate($j['tanggal_notifikasi_tahap_3'])->locale('id')->translatedFormat('l, j F Y') ?>
                            <br>
                            <strong>Status: <?= $j['status_jadwal'] == 'Aktif' ? $j['status_notifikasi_tahap_3'] : 'Nonaktif' ?></strong>
                            <?php if ($j['status_jadwal'] == 'Aktif' && $j['status_notifikasi_tahap_3'] == 'Pending') : ?>
                                <button type="button" class="btn btn-primary mb-3" onclick="kirim(<?= $j['id'] ?>,3)" id="<?= $j['id'] . '-3' ?>">Kirim</button>
                            <?php endif; ?>
                        </td>
                        <td style="vertical-align: top;"><?= $j['status_jadwal'] ?></td>
                    </tr>
                <?php
                    $index++;
                endforeach;
                ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Notifikasi saat data berhasil ditambahkan atau dihapus
    <?php if (session()->getFlashdata('message')) : ?>
        Swal.fire({
            icon: "success",
            title: "Berhasil!",
            text: "<?= session()->getFlashdata('message') ?>",
            showConfirmButton: false,
            timer: 2000
        });
    <?php endif; ?>

    function kirim(id, tahap) {
        var btn = document.getElementById(id + '-' + tahap);
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Mengirim';
        btn.setAttribute('disabled', true);
        $.ajax({
            type: 'POST',
            url: '<?= url_to('admin.notifikasi.kirimNotifikasiPembayaran') ?>',
            data: {
                id: id,
                tahap: tahap
            },
            success: function(data) {
                console.log(data);
                setTimeout(() => {
                    btn.innerHTML = '<i class="fa fa-check"></i> Dikirim'
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }, 2000);
            },
            error: function(error) {
                console.log(error);
                setTimeout(() => {
                    btn.innerHTML = 'Kirim';
                    btn.setAttribute('disabled', false);
                }, 2000);
            },
        });
    }
</script>
<?= $this->endSection() ?>