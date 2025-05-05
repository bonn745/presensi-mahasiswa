<?= $this->extend('pegawai/layouts.php') ?>

<?= $this->section('content') ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>

<style>
    .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    #my_camera,
    #my_result {
        border: 2px solid #ddd;
        border-radius: 10px;
        padding: 10px;
        margin-bottom: 10px;
    }

    #ambil-foto {
        margin-top: 10px;
    }
</style>

<div class="container">
    <input type="hidden" id="id_pegawai" name="id_pegawai" value="<?= isset($id_pegawai) ? $id_pegawai : '' ?>">
    <input type="hidden" id="tanggal_masuk" name="tanggal_masuk" value="<?= date('Y-m-d') ?>">
    <input type="hidden" id="jam_masuk" name="jam_masuk" value="<?= date('H:i:s') ?>">

    <div id="my_camera"></div>
    <div style="display: none;" id="my_result"></div>
    <button class="btn btn-primary" id="ambil-foto">Ambil Foto</button>
</div>

<script>
    // Ambil tanggal & waktu lokal dari browser
    function updateWaktu() {
        let now = new Date();
        document.getElementById('tanggal_masuk').value = now.toISOString().split('T')[0]; // Format YYYY-MM-DD
        document.getElementById('jam_masuk').value = now.toTimeString().split(' ')[0]; // Format HH:MM:SS
    }

    // Pastikan data waktu diperbarui saat halaman dimuat
    document.addEventListener("DOMContentLoaded", function() {
        updateWaktu();

        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90
        });

        Webcam.attach('#my_camera');
    });

    // Event Listener saat tombol ditekan
    document.getElementById('ambil-foto').addEventListener('click', function() {
        updateWaktu(); // Update waktu sebelum mengambil foto

        let id = document.getElementById("id_pegawai").value.trim();
        let tanggal_masuk = document.getElementById("tanggal_masuk").value.trim();
        let jam_masuk = document.getElementById("jam_masuk").value.trim();

        if (!id || !tanggal_masuk || !jam_masuk) {
            alert("Data presensi tidak lengkap!");
            return;
        }

        Webcam.snap(function(data_uri) {
            document.getElementById("my_result").innerHTML = '<img src="' + data_uri + '" class="img-thumbnail"/>';

            let formData = new FormData();
            formData.append("id_pegawai", id);
            formData.append("tanggal_masuk", tanggal_masuk);
            formData.append("jam_masuk", jam_masuk);

            // Ubah Base64 ke Blob (file)
            fetch(data_uri)
                .then(res => res.blob())
                .then(blob => {
                    formData.append("foto_masuk", blob, "absensi_" + id + "_" + Date.now() + ".jpg");

                    // Kirim ke backend
                    fetch("<?= base_url('pegawai/presensi_masuk_aksi') ?>", {
                            method: "POST",
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert("Presensi berhasil!");
                                window.location.href = "<?= base_url('pegawai/home') ?>";
                            } else {
                                alert("Gagal menyimpan presensi!");
                            }
                        })
                        .catch(error => console.error("Error:", error));
                });
        });
    });
</script>
<?= $this->endSection() ?>