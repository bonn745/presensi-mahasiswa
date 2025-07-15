<?= $this->extend('mahasiswa/layouts.php') ?>

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
        width: 640px;
        height: 480px;
    }

    #ambil-foto {
        margin-top: 10px;
    }
</style>

<div class="container justify-content-start">
    <input type="hidden" id="id_mahasiswa" name="id_mahasiswa" value="<?= isset($id_mahasiswa) ? $id_mahasiswa : '' ?>">
    <input type="hidden" id="tanggal_masuk" name="tanggal_masuk" value="<?= date('Y-m-d') ?>">
    <input type="hidden" id="jam_masuk" name="jam_masuk" value="<?= date('H:i:s') ?>">
    <input type="hidden" id="id_lokasi_presensi" name="id_lokasi_presensi" value="<?= isset($id_lokasi_presensi) ? $id_lokasi_presensi : '' ?>">
    <input type="hidden" id="id_matkul" name="id_matkul" value="<?= isset($id_matkul) ? $id_matkul : '' ?>">

    <div id="my_camera"></div>
    <div style="display: none;" id="my_result"></div>
    <button class="btn btn-primary mt-5" id="ambil-foto">Ambil Foto</button>

    <!-- Debug info -->
    <!-- <div class="mt-3">
        <small class="text-muted">Debug Info:</small>
        <pre id="debug-info" class="small"></pre>
    </div> -->
</div>

<script>
    // Fungsi untuk menampilkan debug info
    function showDebugInfo() {
        const debugInfo = {
            id_mahasiswa: document.getElementById('id_mahasiswa').value,
            tanggal_masuk: document.getElementById('tanggal_masuk').value,
            jam_masuk: document.getElementById('jam_masuk').value,
            id_lokasi_presensi: document.getElementById('id_lokasi_presensi').value,
            id_matkul: document.getElementById('id_matkul').value
        };
        // document.getElementById('debug-info').textContent = JSON.stringify(debugInfo, null, 2);
    }

    // Ambil tanggal & waktu lokal dari browser
    function updateWaktu() {
        let now = new Date();
        document.getElementById('tanggal_masuk').value = now.toISOString().split('T')[0]; // Format YYYY-MM-DD
        document.getElementById('jam_masuk').value = now.toTimeString().split(' ')[0]; // Format HH:MM:SS
        showDebugInfo(); // Update debug info setelah update waktu
    }

    // Pastikan data waktu diperbarui saat halaman dimuat
    document.addEventListener("DOMContentLoaded", function() {
        updateWaktu();
        showDebugInfo();

        Webcam.set({
            image_format: 'jpeg',
            jpeg_quality: 90
        });

        Webcam.attach('#my_camera');
    });

    // Event Listener saat tombol ditekan
    document.getElementById('ambil-foto').addEventListener('click', function() {
        Webcam.snap(function(data_uri) {
            document.getElementById('my_result').innerHTML = '<img src="' + data_uri + '"/>';

            // Convert to blob
            fetch(data_uri)
                .then(res => res.blob())
                .then(blob => {
                    const formData = new FormData();
                    formData.append('foto_masuk', blob, 'webcam.jpg');
                    formData.append('id_mahasiswa', document.getElementById('id_mahasiswa').value);
                    formData.append('tanggal_masuk', document.getElementById('tanggal_masuk').value);
                    formData.append('jam_masuk', document.getElementById('jam_masuk').value);
                    formData.append('id_lokasi_presensi', document.getElementById('id_lokasi_presensi').value);
                    formData.append('id_matkul', document.getElementById('id_matkul').value);

                    // Debug log sebelum kirim
                    console.log('Data yang akan dikirim:', {
                        id_mahasiswa: document.getElementById('id_mahasiswa').value,
                        tanggal_masuk: document.getElementById('tanggal_masuk').value,
                        jam_masuk: document.getElementById('jam_masuk').value,
                        id_lokasi_presensi: document.getElementById('id_lokasi_presensi').value,
                        id_matkul: document.getElementById('id_matkul').value
                    });

                    fetch('<?= base_url('mahasiswa/presensi_masuk_aksi') ?>', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.href = '<?= base_url('mahasiswa/home') ?>';
                            } else {
                                alert('Gagal menyimpan presensi: ' + (data.message || 'Terjadi kesalahan'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat mengirim data');
                        });
                });
        });
    });
</script>
<?= $this->endSection() ?>