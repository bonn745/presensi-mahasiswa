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
    <input type="hidden" id="id_presensi" name="id_presensi" value="<?= isset($id_presensi) ? $id_presensi : '' ?>">
    <input type="hidden" id="tanggal_keluar" name="tanggal_keluar" value="<?= date('Y-m-d') ?>">
    <input type="hidden" id="jam_keluar" name="jam_keluar" value="<?= date('H:i:s') ?>">
    <input type="hidden" id="id_lokasi_presensi" name="id_lokasi_presensi" value="<?= isset($id_lokasi_presensi) ? $id_lokasi_presensi : '' ?>">
    <input type="hidden" id="id_matkul" name="id_matkul" value="<?= isset($id_matkul) ? $id_matkul : '' ?>">

    <div id="my_camera"></div>
    <div style="display: none;" id="my_result"></div>
    <button class="btn btn-primary" id="ambil-foto">Ambil Foto</button>

    <!-- Debug info -->
    <div class="mt-3">
        <small class="text-muted">Debug Info:</small>
        <pre id="debug-info" class="small"></pre>
    </div>
</div>

<script>
    // Fungsi untuk menampilkan debug info
    function showDebugInfo() {
        const debugInfo = {
            id_presensi: document.getElementById('id_presensi').value,
            tanggal_keluar: document.getElementById('tanggal_keluar').value,
            jam_keluar: document.getElementById('jam_keluar').value,
            id_lokasi_presensi: document.getElementById('id_lokasi_presensi').value,
            id_matkul: document.getElementById('id_matkul').value
        };
        document.getElementById('debug-info').textContent = JSON.stringify(debugInfo, null, 2);
    }

    // Ambil tanggal & waktu lokal dari browser
    function updateWaktu() {
        let now = new Date();
        document.getElementById('tanggal_keluar').value = now.toISOString().split('T')[0]; // Format YYYY-MM-DD
        document.getElementById('jam_keluar').value = now.toTimeString().split(' ')[0]; // Format HH:MM:SS
        showDebugInfo(); // Update debug info setelah update waktu
    }

    // Pastikan data waktu diperbarui saat halaman dimuat
    document.addEventListener("DOMContentLoaded", function() {
        updateWaktu();
        showDebugInfo();

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
        Webcam.snap(function(data_uri) {
            document.getElementById('my_result').innerHTML = '<img src="' + data_uri + '"/>';

            // Convert to blob
            fetch(data_uri)
                .then(res => res.blob())
                .then(blob => {
                    const formData = new FormData();
                    formData.append('foto_keluar', blob, 'webcam.jpg');
                    formData.append('tanggal_keluar', document.getElementById('tanggal_keluar').value);
                    formData.append('jam_keluar', document.getElementById('jam_keluar').value);
                    formData.append('id_lokasi_presensi', document.getElementById('id_lokasi_presensi').value);
                    formData.append('id_matkul', document.getElementById('id_matkul').value);

                    // Debug log sebelum kirim
                    console.log('Data yang akan dikirim:', {
                        tanggal_keluar: document.getElementById('tanggal_keluar').value,
                        jam_keluar: document.getElementById('jam_keluar').value,
                        id_lokasi_presensi: document.getElementById('id_lokasi_presensi').value,
                        id_matkul: document.getElementById('id_matkul').value
                    });

                    fetch('<?= base_url('mahasiswa/presensi_keluar_aksi/' . $id_presensi) ?>', {
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