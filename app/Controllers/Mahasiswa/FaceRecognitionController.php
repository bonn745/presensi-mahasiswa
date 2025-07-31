<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;
use App\Models\MahasiswaFaceEncoding;
use Exception;

class FaceRecognitionController extends BaseController
{

    // Call Python face recognition service
    public function call_face_recognition_service($action, $id_mahasiswa, $image_path)
    {
        try {

            $tempDir = WRITEPATH . 'python/';
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0777, true);
            }

            $pos = strpos($image_path, '.');

            if ($pos !== false) {
                $image_path = substr_replace($image_path, '', $pos, 1);
            }

            $pos = strpos($image_path, '/');

            if ($pos !== false) {
                $image_path = substr_replace($image_path, '', $pos, 1);
            }

            $image_path = $image_path;

            $tempFile = tempnam($tempDir, 'input_') . '.jpg';

            file_put_contents($tempFile, file_get_contents(FCPATH . $image_path));

            // Path ke Python script
            $python_script = FCPATH . '../../face-detect/face-detect.py';

            // Prepare command
            $command = "/opt/homebrew/bin/python3.9 $python_script $action $id_mahasiswa 1 " . $tempFile;
            // $command = "python $python_script $action $id_mahasiswa 1 " . $tempFile;

            log_message('info','Command: '.$command);

            // Execute command
            $output = shell_exec($command . ' 2>&1');

            if ($output === null) {
                return ['success' => false, 'message' => 'Gagal menjalankan service face recognition'];
            }

            $trimmedOutput = trim($output);

            // Parse JSON output
            $result = json_decode($trimmedOutput);

            if (json_last_error() !== JSON_ERROR_NONE) {
                log_message('error', $output);
                return ['success' => false, 'message' => 'Gagal mengenali wajah. Pastikan Wajah berada di tengah dan tidak terlihat lebih dari satu wajah.'];
            }

            return $result;
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error sistem face recognition'];
        }
    }

    public function register_face($id_mahasiswa, $base64_data)
    {

        $file_path = $this->save_registration_image($base64_data, $id_mahasiswa);

        if (!$file_path) return false;

        $image_data = str_replace('data:image/jpeg;base64,', '', $base64_data);
        $image_data = str_replace(' ', '+', $image_data);
        $image_binary = base64_decode($image_data);

        $data = array(
            'id_mahasiswa' => $id_mahasiswa,
            'face_encoding' => $base64_data,
            'foto_path' => $file_path,
        );

        $mahasiswaFaceEncodingModel = new MahasiswaFaceEncoding();
        $result = $mahasiswaFaceEncodingModel->insert($data);
        if ($result) {
            return true;
        }

        return false;
    }

    // Simpan gambar registrasi
    private function save_registration_image($base64_data, $id_mahasiswa)
    {
        try {
            $image_data = str_replace('data:image/jpeg;base64,', '', $base64_data);
            $image_data = str_replace(' ', '+', $image_data);
            $image_binary = base64_decode($image_data);

            if ($image_binary === false) {
                return false;
            }

            $upload_path = './uploads/face_registration/' . date('Y/m/');
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0755, true);
            }

            $filename = 'face_reg_' . $id_mahasiswa . '_' . date('Ymd_His') . '_' . uniqid() . '.jpg';
            $file_path = $upload_path . $filename;

            if (file_put_contents($file_path, $image_binary)) {
                return $file_path;
            }

            return false;
        } catch (Exception $e) {
            log_message('error', 'Error saving registration image: ' . $e->getMessage());
            return false;
        }
    }

    // Simpan gambar presensi (existing method - updated)
    public function save_attendance_image($base64_data, $id_mahasiswa)
    {
        try {
            $image_data = str_replace('data:image/jpeg;base64,', '', $base64_data);
            $image_data = str_replace(' ', '+', $image_data);
            $image_binary = base64_decode($image_data);

            if ($image_binary === false) {
                return false;
            }

            $upload_path = './uploads/presensi/' . date('Y/m/');
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0755, true);
            }

            $filename = 'face_' . $id_mahasiswa . '_' . date('Ymd_His') . '_' . uniqid() . '.jpg';
            $file_path = $upload_path . $filename;

            if (file_put_contents($file_path, $image_binary)) {
                return $file_path;
            }

            return false;
        } catch (Exception $e) {
            log_message('error', 'Error saving attendance image: ' . $e->getMessage());
            return false;
        }
    }

    // API untuk cek status registrasi wajah
    public function check_face_registration_status()
    {
        $id_mahasiswa = session()->get('id_mahasiswa');

        $mahasiswaFaceEncodingModel = new MahasiswaFaceEncoding();

        $has_face_data = count($mahasiswaFaceEncodingModel->where('id_mahasiswa', $id_mahasiswa)->findAll()) == 0 ? false : true;

        return $has_face_data;
    }
}
