<?= $this->extend('mahasiswa/layouts.php') ?>
<?= $this->section('content') ?>

<div class="card shadow-sm p-4 mb-4">
    <div class="card-body">
        <form class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="input-style-1">
                    <select id="matkul" class="form-control" name="matkul" onchange="cekPertemuan()">
                        <option value="" disabled selected>-- Pilih Mata Kuliah--</option>
                        <?php foreach ($data_matkul as $mtkl) : ?>
                            <option value="<?= $mtkl['id'] ?>"><?= $mtkl['matkul'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 d-none" id="loading-indicator">
                <i class="fa fa-spinner fa-spin align-bottom"></i>
            </div>
            <div class="col-md-4 d-none" id="select-pertemuan">
                <select class="form-select" name="pertemuan" id="pertemuan" onchange="updatePertemuan()">
                </select>
            </div>
            <div class="col-md-auto" id="tampilkan-btn">
                <button type="button" class="btn btn-primary" onclick="updateTable()" id="btn-tampilkan">Tampilkan</button>
            </div>
            <div class="col-md-auto d-none" id="unduh-rekap">
                <a type="button" href="#" target="_blank" class="btn btn-primary" id="unduh-ref"><i class="fa fa-download"></i> Unduh PDF</a>
            </div>
            <input type="hidden" name="table-data" id="table-data" value="">
        </form>
        <hr>
        <?php if (!empty($nama_matkul)) : ?>
            <p><strong>Menampilkan data:</strong>
                <?= $nama_matkul ?>
            </p>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover text-center align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Pertemuan</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody id="body-data">
                    <tr>
                        <td colspan="6">Silakan pilih Mata Kuliah dan Klik "Tampilkan"</td>
                    </tr>
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
        $('#tampilkan-btn').addClass('d-none');
        var id = $('#matkul').val();
        var pertemuan = $('#pertemuan').val() == null ? '' : $('#pertemuan').val();
        var csrf = $('input[name="csrf_test_name"]').val();
        $.ajax({
            url: '<?= url_to("mahasiswa.cekPertemuan") ?>',
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
                $('#unduh-ref').attr('href', '<?= url_to("mahasiswa.unduh") ?>?matkul=' + id + '&pertemuan=' + pertemuan);
                $('#table-data').val('<?= url_to("mahasiswa.tableData") ?>?matkul=' + id + '&pertemuan=' + pertemuan);
                $('#tampilkan-btn').removeClass('d-none');

            },
            error: function(error) {
                $('#loading-indicator').addClass('d-none');
                $('#tampilkan-btn').removeClass('d-none');
            }
        });
    }

    function updatePertemuan() {
        var id = $('#matkul').val();
        var tanggal = $('#pertemuan').val();
        var text = $("#pertemuan option:selected").text();
        $('#unduh-ref').attr('href', '<?= url_to("mahasiswa.unduh") ?>?matkul=' + id + '&pertemuan=' + tanggal + '&text=' + text);
        $('#table-data').val('<?= url_to("mahasiswa.tableData") ?>?matkul=' + id + '&pertemuan=' + tanggal + '&text=' + text);
    }

    function updateTable() {
        $('#btn-tampilkan').html('<i class="fa fa-spinner fa-spin"></i>');
        $('#btn-tampilkan').attr('disabled', true);
        var url = $('#table-data').val();
        var csrf = $('input[name="csrf_test_name"]').val();
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                csrf_test_name: csrf,
            },
            success: function(message, status, jqXHR) {
                $('#btn-tampilkan').html('Tampilkan');
                $('#btn-tampilkan').removeAttr('disabled');
                var data = '';
                var index = 1;
                message.presensi.forEach(function(val){
                    data += '<tr>' +
                    '<td>'+ index +
                    '</td>'+
                    '<td>'+ val.tanggal +
                    '</td>'+
                    '<td>'+ val.pertemuan +
                    '</td>'+
                    '<td>'+ val.jam_masuk +
                    '</td>'+
                    '<td>'+ val.jam_keluar +
                    '</td>'+
                    '<td>'+ val.keterangan +
                    '</td>'+
                    '</tr>';

                    index++;
                });

                $('#body-data').html(data);
            },
            error: function(error) {
                $('#btn-tampilkan').html('Tampilkan');
                $('#btn-tampilkan').removeAttr('disabled');
                $('#body-data').html('<tr><td style="color:red" colspan="6" align="center">Silakan pilih Mata Kuliah.</td></tr>');
            }
        });
    }
</script>
<?= $this->endSection() ?>