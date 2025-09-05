<?= $this->extend('dosen/layouts.php') ?>

<?= $this->section('content') ?>

<!-- Statistik Cards -->
<div class="row g-3 bg-white h-screen">
    <div class="col-md-4">
        <select name="filter_bulan" class="form-select" id="select-matkul" onchange="cekPertemuan()">
            <option value="" selected disabled>- Pilih Mata Kuliah -</option>
            <?php foreach ($mata_kuliah as $mk) : ?>
                <option value="<?= $mk['id'] ?>"><?= $mk['nama_matkul'] ?></option>
            <?php endforeach; ?>
        </select>
        <?= csrf_field() ?>
    </div>
    <div class="col-md-4 d-none" id="loading-indicator">
        <i class="fa fa-spinner fa-spin align-bottom"></i>
    </div>
    <div class="col-md-4 d-none" id="select-pertemuan">
        <select class="form-select" name="pertemuan" id="pertemuan" onchange="updatePertemuan()">
        </select>
    </div>
    <div class="col-md-4 d-none" id="unduh-rekap">
        <a type="button" href="#" target="_blank" class="btn btn-primary" id="unduh-ref"><i class="fa fa-download"></i> Unduh PDF</a>
    </div>
    <hr>
    <div class="container mt-4">
        Presensi mahasiswa hari ini: <strong><?= Carbon\Carbon::now('Asia/Jakarta')->locale('id')->translatedFormat('l, j F Y') ?></strong>
        <div class="table-responsive">
            <table class="table" id="">
                <thead class="table-primary">
                    <tr>
                        <th style="padding: 10px;">No</th>
                        <th>NPM</th>
                        <th>Nama</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($presensi_hari_ini)) : ?>
                        <?php foreach ($presensi_hari_ini as $phi) : ?>
                            <?php $index = 1; ?>
                            <tr>
                                <th scope="row" colspan="5">
                                    <?= $phi['nama_matkul'] ?>
                                </th>
                            </tr>
                            <?php foreach ($phi['kehadiran'] as $kehadiran) : ?>
                                <tr>
                                    <td align="center" style="width: 1%; white-space: nowrap;"><?= $index ?></td>
                                    <td><?= $kehadiran['npm_mahasiswa'] ?></td>
                                    <td><?= $kehadiran['nama_mahasiswa'] ?></td>
                                    <?php if ($kehadiran['izin']) : ?>
                                        <td colspan="2"><?= $kehadiran['keterangan'] ?></td>
                                    <?php else : ?>
                                        <td><?= $kehadiran['jam_masuk'] == '-' ? '-' : date('H:i', strtotime($kehadiran['jam_masuk'])) ?></td>
                                        <td><?= $kehadiran['jam_keluar'] == '00:00:00' ? '-' : ($kehadiran['jam_keluar'] == '-' ? '-' : date('H:i', strtotime($kehadiran['jam_keluar']))) ?></td>
                                    <?php endif; ?>

                                    <td>
                                        <?php if ($kehadiran['jam_masuk'] != '-' && $kehadiran['jam_keluar'] != '-' && $kehadiran['jam_masuk'] != '00:00:00' && $kehadiran['jam_keluar'] != '00:00:00') : ?>
                                            <i class="fa fa-check text-success"></i>
                                        <?php elseif ($kehadiran['jam_masuk'] == '-' && $kehadiran['jam_keluar'] == '-') : ?>
                                            <i class="fa fa-close text-danger"></i>
                                        <?php else : ?>
                                            <i class="fa fa-exclamation text-warning"></i>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php $index++;
                            endforeach; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" align="center">
                                Tidak ada kelas hari ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="application/javascript">
    function cekPertemuan() {
        $('#loading-indicator').removeClass('d-none');
        $('#select-pertemuan').addClass('d-none');
        $('#unduh-rekap').addClass('d-none');
        var id = $('#select-matkul').val();
        var pertemuan = $('#pertemuan').val() == null ? '' : $('#pertemuan').val();
        var csrf = $('input[name="csrf_test_name"]').val();
        $.ajax({
            url: '<?= url_to("dosen.cekPertemuan") ?>',
            type: 'POST',
            data: {
                csrf_test_name: csrf,
                id_matkul: id
            },
            success: function(message, status, jqXHR) {
                $('#loading-indicator').addClass('d-none');
                var data = message.data;
                var option = '<option selected disabled>- Pilih Pertemuan -</option>';
                data.forEach(function(value, index) {
                    option += '<option value="' + value.value + '">' + value.key + '</option>';
                });
                $('#pertemuan').html(option);
                $('#select-pertemuan').removeClass('d-none');
                $('#unduh-rekap').removeClass('d-none');
                $('#unduh-ref').attr('href', '<?= url_to("dosen.unduh") ?>?matkul=' + id + '&pertemuan=' + pertemuan);
            },
            error: function(error) {
                $('#loading-indicator').addClass('d-none');
                Swal.fire({
                    icon: "error",
                    title: "Belum Ada Kelas!",
                    text: "Tidak ada kelas!",
                    showConfirmButton: false,
                    timer: 2000
                });
                console.log(error);
            }
        });
    }

    function updatePertemuan() {
        var id = $('#select-matkul').val();
        var tanggal = $('#pertemuan').val();
        var text = $("#pertemuan option:selected").text();
        $('#unduh-ref').attr('href', '<?= url_to("dosen.unduh") ?>?matkul=' + id + '&pertemuan=' + tanggal + '&text=' + text);
    }
</script>
<?= $this->endSection() ?>