<?= $this->extend('admin/layouts.php') ?>

<?= $this->section('content') ?>
<style>
    #map {
        height: 480px;
    }
</style>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <td><strong>Nama Lokasi</strong></td>
                            <td>:</td>
                            <td><?= esc($lokasi_presensi['nama_ruangan']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Alamat Lokasi</strong></td>
                            <td>:</td>
                            <td><?= esc($lokasi_presensi['alamat_lokasi']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tipe Lokasi</strong></td>
                            <td>:</td>
                            <td><?= esc($lokasi_presensi['tipe_lokasi']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Latitude</strong></td>
                            <td>:</td>
                            <td><?= esc($lokasi_presensi['latitude']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Longitude</strong></td>
                            <td>:</td>
                            <td><?= esc($lokasi_presensi['longitude']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Radius</strong></td>
                            <td>:</td>
                            <td><?= esc($lokasi_presensi['radius']) ?> meter</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div id="map"></div>
    </div>
</div>
<a href="<?= base_url('admin/lokasi_presensi') ?>" class="btn btn-secondary mt-3">Kembali</a>

<script>
    var map = L.map('map').setView([<?= $lokasi_presensi['latitude'] ?>, <?= $lokasi_presensi['longitude'] ?>], 13);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    var marker = L.marker([<?= $lokasi_presensi['latitude'] ?>, <?= $lokasi_presensi['longitude'] ?>]).addTo(map);
</script>

<?= $this->endSection() ?>