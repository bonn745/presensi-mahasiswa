<?= $this->extend('pegawai/layouts.php') ?>

<?= $this->section('content') ?>
<style>
    .parent-clock {
        display: grid;
        grid-template-columns: repeat(5, auto);
        font-size: 35px;
        font-weight: bold;
        justify-content: center;
    }

    .card {
        height: 100%;
    }

    #map {
        height: 480px;
        width: 66%;
        margin: auto;
        z-index: 0;
    }
</style>

<div class="row mb-3 d-flex justify-content-center">
    <!-- PRESENSI MASUK -->
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-header"><i class="fas fa-sign-in-alt"></i> Presensi Masuk</div>
            <div class="card-body">
                <div class="fw-bold"><?= date('d F Y') ?></div>
                <div class="parent-clock">
                    <div id="jam-Masuk">00</div>
                    <div>:</div>
                    <div id="menit-Masuk">00</div>
                    <div>:</div>
                    <div id="detik-Masuk">00</div>
                </div>

                <?php if ($cek_presensi < 1) : ?>
                    <form method="POST" action="<?= base_url('pegawai/presensi_masuk') ?>">
                        <?php date_default_timezone_set($lokasi_presensi['zona_waktu'] == 'WIB' ? 'Asia/Jakarta' : ($lokasi_presensi['zona_waktu'] == 'WITA' ? 'Asia/Makassar' : 'Asia/Jayapura')); ?>

                        <input type="hidden" name="latitude_kantor" value="<?= $lokasi_presensi['latitude'] ?>">
                        <input type="hidden" name="longitude_kantor" value="<?= $lokasi_presensi['longitude'] ?>">
                        <input type="hidden" name="radius" value="<?= $lokasi_presensi['radius'] ?>">
                        <input type="hidden" name="latitude_pegawai" id="latitude_pegawai">
                        <input type="hidden" name="longitude_pegawai" id="longitude_pegawai">
                        <input type="hidden" name="tanggal_masuk" value="<?= date('Y-m-d') ?>">
                        <input type="hidden" name="jam_masuk" value="<?= date('H:i:s') ?>">
                        <input type="hidden" name="id_pegawai" value="<?= session()->get('id_pegawai') ?>">

                        <button type="submit" class="btn btn-primary mt-3"><i class="fas fa-sign-in-alt"></i> Masuk</button>
                    </form>
                <?php else : ?>
                    <h5 class="text-success"><i class="fas fa-check-circle"></i> Anda telah melakukan presensi masuk</h5>
                    <p><i class="fas fa-clock"></i> Jam Masuk: <strong><?= $ambil_presensi_masuk['jam_masuk'] ?></strong></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- PRESENSI KELUAR -->
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-header"><i class="fas fa-sign-out-alt"></i> Presensi Keluar</div>
            <div class="card-body">
                <div class="fw-bold"><?= date('d F Y') ?></div>
                <div class="parent-clock">
                    <div id="jam-Keluar">00</div>
                    <div>:</div>
                    <div id="menit-Keluar">00</div>
                    <div>:</div>
                    <div id="detik-Keluar">00</div>
                </div>

                <?php if ($cek_presensi < 1) : ?>
                    <h5 class="text-center text-danger">Anda belum melakukan presensi masuk!</h5>

                <?php elseif ($ambil_presensi_keluar && $ambil_presensi_keluar['jam_keluar'] != '00:00:00') : ?>
                    <h5 class="text-success"><i class="fas fa-check-circle"></i> Anda telah melakukan presensi keluar</h5>
                    <p><i class="fas fa-clock"></i> Jam Keluar: <strong><?= $ambil_presensi_keluar['jam_keluar'] ?></strong></p>

                <?php else : ?>
                    <form method="POST" action="<?= base_url('pegawai/presensi_keluar/' . $ambil_presensi_masuk['id']) ?>" enctype="multipart/form-data">
                        <?php date_default_timezone_set($lokasi_presensi['zona_waktu'] == 'WIB' ? 'Asia/Jakarta' : ($lokasi_presensi['zona_waktu'] == 'WITA' ? 'Asia/Makassar' : 'Asia/Jayapura')); ?>

                        <input type="hidden" name="latitude_kantor" value="<?= $lokasi_presensi['latitude'] ?>">
                        <input type="hidden" name="longitude_kantor" value="<?= $lokasi_presensi['longitude'] ?>">
                        <input type="hidden" name="radius" value="<?= $lokasi_presensi['radius'] ?>">
                        <input type="hidden" name="latitude_pegawai" id="latitude_pegawai">
                        <input type="hidden" name="longitude_pegawai" id="longitude_pegawai">
                        <input type="hidden" name="tanggal_keluar" value="<?= date('Y-m-d') ?>">
                        <input type="hidden" name="jam_keluar" value="<?= date('H:i:s') ?>">

                        <button type="submit" class="btn btn-danger mt-3">Keluar</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div id="map"></div>


<script>
    // Jalankan fungsi update waktu setiap detik
    setInterval(updateWaktu, 1000);

    function updateWaktu() {
        const waktu = new Date();
        const jam = formatWaktu(waktu.getHours());
        const menit = formatWaktu(waktu.getMinutes());
        const detik = formatWaktu(waktu.getSeconds());

        // Pastikan elemen ada sebelum diperbarui untuk menghindari error
        if (document.getElementById("jam-Masuk")) {
            document.getElementById("jam-Masuk").innerHTML = jam;
            document.getElementById("menit-Masuk").innerHTML = menit;
            document.getElementById("detik-Masuk").innerHTML = detik;
        }

        if (document.getElementById("jam-Keluar")) {
            document.getElementById("jam-Keluar").innerHTML = jam;
            document.getElementById("menit-Keluar").innerHTML = menit;
            document.getElementById("detik-Keluar").innerHTML = detik;
        }
    }

    function formatWaktu(waktu) {
        return waktu < 10 ? "0" + waktu : waktu;
    }

    // Panggil getLocation saat halaman dimuat
    window.onload = getLocation;

    function getLocation() {
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            alert("Browser Anda tidak mendukung geolocation.");
        }
    }

    function showPosition(position) {

        var latitude_pegawai = position.coords.latitude;
        var longitude_pegawai = position.coords.longitude;
        let latitudeField = document.getElementById("latitude_pegawai");
        let longitudeField = document.getElementById("longitude_pegawai");

        if (latitudeField && longitudeField) {
            latitudeField.value = latitude_pegawai;
            longitudeField.value = longitude_pegawai;
        } else {
            console.error("Elemen input latitude/longitude tidak ditemukan.");
        }

        initMap(latitude_pegawai, longitude_pegawai)
    }

    function initMap(latitude_pegawai, longitude_pegawai) {
        var map = L.map('map').setView([<?= $lokasi_presensi['latitude'] ?>, <?= $lokasi_presensi['longitude'] ?>], 15);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Marker kantor
        var kantorMarker = L.marker([<?= $lokasi_presensi['latitude'] ?>, <?= $lokasi_presensi['longitude'] ?>])
            .addTo(map)
            .bindPopup("Lokasi Kantor")
            .openPopup();

        // Lingkaran radius kantor
        L.circle([<?= $lokasi_presensi['latitude'] ?>, <?= $lokasi_presensi['longitude'] ?>], {
            color: 'blue',
            fillColor: '#30f',
            fillOpacity: 0.1,
            radius: <?= $lokasi_presensi['radius'] ?>
        }).addTo(map);

        // Marker lokasi pegawai
        var pegawaiMarker = L.marker([latitude_pegawai, longitude_pegawai])
            .addTo(map)
            .bindPopup("Lokasi Anda");

        // Lingkaran lokasi pegawai
        L.circle([latitude_pegawai, longitude_pegawai], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.4,
            radius: 10
        }).addTo(map);

        // Garis penghubung antara pegawai dan kantor
        var polyline = L.polyline([
            [<?= $lokasi_presensi['latitude'] ?>, <?= $lokasi_presensi['longitude'] ?>],
            [latitude_pegawai, longitude_pegawai]
        ], {
            color: 'orange'
        }).addTo(map);

        let jarak = map.distance(
            [<?= $lokasi_presensi['latitude'] ?>, <?= $lokasi_presensi['longitude'] ?>],
            [latitude_pegawai, longitude_pegawai]
        ).toFixed(2);

        L.popup()
            .setLatLng([latitude_pegawai, longitude_pegawai])
            .setContent("Jarak Anda ke kantor: " + jarak + " meter")
            .openOn(map);


        // Fit map agar semua elemen terlihat
        map.fitBounds(polyline.getBounds());
    }

    function showError(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                alert("Izin lokasi ditolak oleh pengguna. Aktifkan izin lokasi untuk melanjutkan.");
                break;
            case error.POSITION_UNAVAILABLE:
                alert("Informasi lokasi tidak tersedia. Pastikan GPS aktif.");
                break;
            case error.TIMEOUT:
                alert("Permintaan lokasi melebihi batas waktu. Coba lagi.");
                break;
            case error.UNKNOWN_ERROR:
                alert("Terjadi kesalahan yang tidak diketahui.");
                break;
        }
    }
</script>

<?= $this->endSection() ?>