<?= $this->extend('admin/layouts.php') ?>
<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-start gap-3 align-items-center mb-4">
        <button class="btn btn-primary" onclick="kirim()" id="btn-kirim">
            <i class="fas fa-bell"></i> Kirim Notifikasi
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center" id="datatables">
            <thead class="table-primary">
                <tr>
                    <th style="width: 1%; white-space: nowrap;">No</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $index = 1;
                foreach ($data_log as $dl) :
                ?>
                    <tr>
                        <td><?= $index; ?></td>
                        <td><?= Carbon\Carbon::createFromDate($dl['tanggal'])->locale('id')->translatedFormat('l, j F Y H:i') ?></td>
                        <td><?= $dl['status'] ?></td>
                    </tr>
                <?php
                    $index++;
                endforeach;
                ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    function kirim() {
        var btn = document.getElementById('btn-kirim');
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Mengirim';
        btn.setAttribute('disabled', true);
        $.ajax({
            type: 'POST',
            url: '<?= url_to('admin.notifikasi.kirimNotifikasiRekapPresensi') ?>',
            success: function(data) {
                console.log(data);
                setTimeout(() => {
                    btn.innerHTML = '<i class="fa fa-check"></i> Dikirim'
                    setTimeout(() => {
                        btn.innerHTML = '<i class="fas fa-bell"></i> Kirim Notifikasi';
                        btn.removeAttribute('disabled');
                    }, 2000);
                }, 2000);
            },
            error: function(error) {
                console.log(error);
                setTimeout(() => {
                    btn.innerHTML = 'Gagal mengirim.';
                    setTimeout(() => {
                        btn.innerHTML = '<i class="fas fa-bell"></i> Kirim Notifikasi';
                        btn.removeAttribute('disabled');
                    }, 2000);
                }, 2000);
            },
        });
    }
</script>
<?= $this->endSection() ?>