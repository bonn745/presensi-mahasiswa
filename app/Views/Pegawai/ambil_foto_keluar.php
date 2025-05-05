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
    <input type="hidden" id="tanggal_keluar" name="tanggal_keluar" value="<?= date('Y-m-d') ?>">
    <input type="hidden" id="jam_keluar" name="jam_keluar" value="<?= date('H:i:s') ?>">

    <div id="my_camera"></div>
    <div style="display: none;" id="my_result"></div>
    <button class="btn btn-danger" id="ambil-foto-keluar">Ambil Foto</button>
</div>

<script>
    function updateWaktu() {
        let now = new Date();
        document.getElementById('tanggal_keluar').value = now.toISOString().split('T')[0];
        document.getElementById('jam_keluar').value = now.toTimeString().split(' ')[0];
    }

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

    document.getElementById('ambil-foto-keluar').addEventListener('click', function() {
        updateWaktu();

        let tanggal_keluar = document.getElementById("tanggal_keluar").value.trim();
        let jam_keluar = document.getElementById("jam_keluar").value.trim();
        let id_presensi = "<?= $id_presensi ?? '' ?>"; // Pastikan ID presensi dikirim

        if (!id_presensi || !tanggal_keluar || !jam_keluar) {
            alert("Data presensi tidak lengkap!");
            return;
        }

        Webcam.snap(function(data_uri) {
            let formData = new FormData();
            formData.append("tanggal_keluar", tanggal_keluar);
            formData.append("jam_keluar", jam_keluar);

            fetch(data_uri)
                .then(res => res.blob())
                .then(blob => {
                    formData.append("foto_keluar", blob, "absensi_keluar_" + id_presensi + "_" + Date.now() + ".jpg");

                    fetch("<?= base_url('pegawai/presensi_keluar_aksi/') ?>" + id_presensi, {
                            method: "POST",
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert("Presensi keluar berhasil!");
                                window.location.href = "<?= base_url('pegawai/home') ?>";
                            } else {
                                alert("Gagal menyimpan presensi: " + data.message);
                            }
                        })
                        .catch(error => console.error("Error:", error));
                });
        });
    });
</script>

<?= $this->endSection() ?>