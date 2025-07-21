<?= $this->extend('mahasiswa/layouts.php') ?>

<?= $this->section('styles') ?>
<style>
    /* body {
        font-family: Arial, sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
    } */

    .container {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        /* max-width: 600px; */
        width: 100%;
    }

    .camera-container {
        position: relative;
        margin: 20px 0;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    #video {
        width: 100%;
        height: 100%;
        display: block;
    }

    #overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }

    .status {
        text-align: center;
        margin: 20px 0;
        padding: 15px;
        border-radius: 8px;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .status.loading {
        background: #fff3cd;
        color: #856404;
        border: 2px solid #ffeaa7;
    }

    .status.detecting {
        background: #d4edda;
        color: #155724;
        border: 2px solid #74b9ff;
    }

    .status.success {
        background: #d1ecf1;
        color: #0c5460;
        border: 2px solid #00b894;
    }

    .status.error {
        background: #f8d7da;
        color: #721c24;
        border: 2px solid #e74c3c;
    }

    .progress-bar {
        width: 100%;
        height: 20px;
        background: #ecf0f1;
        border-radius: 10px;
        overflow: hidden;
        margin: 10px 0;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #00b894, #74b9ff);
        width: 0%;
        transition: width 0.3s ease;
        border-radius: 10px;
    }

    .btn {
        background: linear-gradient(45deg, #667eea, #764ba2);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        transition: all 0.3s ease;
        width: 100%;
        margin: 10px 0;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .instructions {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin: 15px 0;
        border-left: 4px solid #667eea;
    }

    .smile-indicator {
        text-align: center;
        font-size: 48px;
        margin: 15px 0;
        transition: all 0.3s ease;
    }

    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #667eea;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 10px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>
<?= $this->endSection() ?>
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

    #captureBtn {
        margin-top: 10px;
    }
</style>

<div class="container justify-content-start">
    <input type="hidden" id="id_mahasiswa" name="id_mahasiswa" value="<?= isset($id_mahasiswa) ? $id_mahasiswa : '' ?>">
    <input type="hidden" id="tanggal_masuk" name="tanggal_masuk" value="<?= date('Y-m-d') ?>">
    <input type="hidden" id="jam_masuk" name="jam_masuk" value="<?= date('H:i:s') ?>">
    <input type="hidden" id="id_lokasi_presensi" name="id_lokasi_presensi" value="<?= isset($id_lokasi_presensi) ? $id_lokasi_presensi : '' ?>">
    <input type="hidden" id="id_matkul" name="id_matkul" value="<?= isset($id_matkul) ? $id_matkul : '' ?>">

    <!-- <div id="my_camera">
    <canvas id="overlay"></canvas>
    </div> -->

    <div class="row col-md-12">
        <div class="col-md-6">
            <div class="camera-container">
                <video id="video" autoplay muted playsinline></video>
                <canvas id="overlay"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div style="display: none;" id="my_result"></div>
            <div id="status" class="status loading">
                <div class="loading-spinner"></div>
                Memuat sistem deteksi wajah...
            </div>

            <div class="progress-bar">
                <div id="progressFill" class="progress-fill"></div>
            </div>

            <div class="instructions">
                <strong>Instruksi:</strong>
                <br>1. Posisikan wajah Anda di tengah kamera
                <br>2. Pastikan pencahayaan cukup
                <br>3. Tunggu hingga wajah terdeteksi
                <br>4. Tersenyum untuk memverifikasi liveness
            </div>

            <div class="smile-indicator" id="smileIndicator">😐</div>
            <button class="btn btn-primary mt-5" id="captureBtn">Ambil Foto</button>
        </div>
    </div>

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

        // Webcam.set({
        //     image_format: 'jpeg',
        //     jpeg_quality: 90
        // });

        // Webcam.attach('#my_camera');
        startDetection();
    });

    // Event Listener saat tombol ditekan
    document.getElementById('captureBtn').addEventListener('click', function() {
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
<script src="<?= base_url('assets/dist/face-api.min.js') ?>"></script>
<script>
    let video, canvas, ctx;
    let faceDetectionStarted = false;
    let isSmiling = false;
    let smileConfidence = 0;
    let faceDetected = false;

    // Inisialisasi komponen
    async function initializeComponents() {
        video = document.getElementById('video');
        canvas = document.getElementById('overlay');
        ctx = canvas.getContext('2d');

        // Load models face-api.js
        updateStatus('loading', 'Memuat model AI...');
        await loadModels();

        // Setup kamera
        await setupCamera();

        // Setup canvas overlay
        setupCanvas();

        updateStatus('detecting', 'Sistem siap! Klik "Mulai Deteksi Wajah"');
        detectFace();
    }

    // Load models yang diperlukan
    async function loadModels() {
        const MODEL_URL = 'https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js@master/weights';

        try {
            await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
            await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
            await faceapi.nets.faceExpressionNet.loadFromUri(MODEL_URL);
            console.log('Models loaded successfully');
        } catch (error) {
            console.error('Error loading models:', error);
            updateStatus('error', 'Gagal memuat model AI. Periksa koneksi internet.');
        }
    }

    // Setup kamera
    async function setupCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: 640,
                    height: 480,
                    facingMode: 'user'
                }
            });
            video.srcObject = stream;

            return new Promise((resolve) => {
                video.onloadedmetadata = () => {
                    resolve(video);
                };
            });
        } catch (error) {
            console.error('Error accessing camera:', error);
            updateStatus('error', 'Tidak dapat mengakses kamera. Pastikan izin kamera diberikan.');
        }
    }

    // Setup canvas overlay
    function setupCanvas() {
        video.addEventListener('loadeddata', () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
        });
    }

    // Mulai deteksi wajah
    async function startDetection() {
        if (faceDetectionStarted) return;

        faceDetectionStarted = true;
        // document.getElementById('startBtn').disabled = true;
        updateStatus('detecting', 'Mendeteksi wajah... Posisikan wajah Anda di kamera');

        detectFace();
    }

    // Fungsi utama deteksi wajah
    async function detectFace() {
        if (!faceDetectionStarted) return;

        const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
            .withFaceLandmarks()
            .withFaceExpressions();

        // Clear canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        if (detections.length > 0) {
            const detection = detections[0];
            faceDetected = true;

            // Gambar kotak deteksi wajah
            drawFaceBox(detection);

            // Cek ekspresi tersenyum
            checkSmileExpression(detection.expressions);

            // Update progress
            updateProgress();

        } else {
            faceDetected = false;
            isSmiling = false;
            smileConfidence = 0;
            updateSmileIndicator();
            updateStatus('detecting', 'Wajah tidak terdeteksi. Posisikan wajah Anda di kamera');
            updateProgressBar(0);
        }

        // Continue detection
        requestAnimationFrame(detectFace);
    }

    // Gambar kotak deteksi wajah
    function drawFaceBox(detection) {
        const {
            x,
            y,
            width,
            height
        } = detection.detection.box;

        ctx.strokeStyle = faceDetected && isSmiling ? '#00b894' : '#74b9ff';
        ctx.lineWidth = 3;
        ctx.strokeRect(x, y, width, height);

        // Label
        ctx.fillStyle = ctx.strokeStyle;
        ctx.fillRect(x, y - 25, width, 25);
        ctx.fillStyle = 'white';
        ctx.font = '16px Arial';
        ctx.fillText(`Wajah Terdeteksi (${Math.round(detection.detection.score * 100)}%)`, x + 5, y - 7);
    }

    // Cek ekspresi tersenyum
    function checkSmileExpression(expressions) {
        const happiness = expressions.happy;
        smileConfidence = happiness;

        // Threshold untuk mendeteksi senyuman
        const smileThreshold = 0.6;
        isSmiling = happiness > smileThreshold;

        updateSmileIndicator();

        if (faceDetected && isSmiling) {
            updateStatus('success', `Tersenyum terdeteksi! (${Math.round(happiness * 100)}%) - Siap untuk capture!`);
            document.getElementById('captureBtn').disabled = false;
        } else if (faceDetected) {
            updateStatus('detecting', `Wajah terdeteksi. Silakan tersenyum untuk liveness detection (${Math.round(happiness * 100)}%)`);
            document.getElementById('captureBtn').disabled = true;
        }
    }

    // Update indikator senyuman
    function updateSmileIndicator() {
        const indicator = document.getElementById('smileIndicator');
        if (!faceDetected) {
            indicator.textContent = '😐';
            indicator.style.color = '#95a5a6';
        } else if (isSmiling) {
            indicator.textContent = '😊';
            indicator.style.color = '#00b894';
            indicator.style.transform = 'scale(1.2)';
        } else {
            indicator.textContent = '😐';
            indicator.style.color = '#f39c12';
            indicator.style.transform = 'scale(1)';
        }
    }

    // Update progress
    function updateProgress() {
        let progress = 0;

        if (faceDetected) progress += 50;
        if (isSmiling) progress += 50;

        updateProgressBar(progress);
    }

    // Update progress bar
    function updateProgressBar(percentage) {
        document.getElementById('progressFill').style.width = percentage + '%';
    }

    // Update status
    function updateStatus(type, message) {
        const statusEl = document.getElementById('status');
        statusEl.className = `status ${type}`;
        statusEl.innerHTML = type === 'loading' ?
            '<div class="loading-spinner"></div>' + message :
            message;
    }

    // Capture foto dan kirim ke backend
    async function captureAndSend() {
        if (!faceDetected || !isSmiling) {
            alert('Pastikan wajah terdeteksi dan Anda tersenyum sebelum capture!');
            return;
        }

        updateStatus('loading', 'Mengambil foto...');

        // Capture foto dari video
        const captureCanvas = document.createElement('canvas');
        const captureCtx = captureCanvas.getContext('2d');
        captureCanvas.width = video.videoWidth;
        captureCanvas.height = video.videoHeight;
        captureCtx.drawImage(video, 0, 0);

        // Convert ke base64
        const imageData = captureCanvas.toDataURL('image/jpeg', 0.8);

        // Kirim ke backend via AJAX
        try {
            const response = await sendToBackend(imageData);
            handleBackendResponse(response);
        } catch (error) {
            console.error('Error sending to backend:', error);
            updateStatus('error', 'Gagal mengirim data ke server. Coba lagi.');
        }
    }

    // Kirim data ke backend CodeIgniter
    async function sendToBackend(imageData) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '<?php echo base_url("presensi/process_face_attendance"); ?>', // Sesuaikan dengan route CI Anda
                type: 'POST',
                data: {
                    image: imageData,
                    user_id: '<?= session()->get("id_mahasiswa"); ?>', // Ambil dari session
                    timestamp: new Date().toISOString(),
                    face_confidence: Math.round(smileConfidence * 100),
                    csrf_token: '' // CSRF protection
                },
                dataType: 'json',
                success: function(response) {
                    resolve(response);
                },
                error: function(xhr, status, error) {
                    reject(error);
                }
            });
        });
    }

    // Handle response dari backend
    function handleBackendResponse(response) {
        if (response.success) {
            updateStatus('success', '✅ Presensi berhasil dicatat!');
            updateProgressBar(100);

            // Redirect atau refresh setelah berhasil
            setTimeout(() => {
                window.location.href = '<?php echo base_url("dashboard"); ?>'; // Redirect ke dashboard
            }, 2000);
        } else {
            updateStatus('error', '❌ ' + (response.message || 'Presensi gagal. Coba lagi.'));
        }
    }

    // Inisialisasi saat halaman dimuat
    window.addEventListener('DOMContentLoaded', initializeComponents);

    // Cleanup saat halaman ditutup
    window.addEventListener('beforeunload', () => {
        if (video && video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
        }
    });
</script>
<?= $this->endSection() ?>