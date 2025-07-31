<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\JadwalPembayaran;
use App\Models\KetidakhadiranModel;
use App\Models\LogNotifikasiPresensiModel;
use App\Models\MahasiswaModel;
use App\Models\PresensiModel;
use App\Models\UangKuliah;
use Carbon\Carbon;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\CURLRequest;
use DateInterval;
use DateTime;

class NotifikasiController extends BaseController
{
    private $url = 'https://graph.facebook.com/v22.0/614146718458585/messages';

    public function kirimNotifikasiPembayaran()
    {
        $id_jadwal = $this->request->getPost('id');
        $tahap = $this->request->getPost('tahap');
        $jadwalPembayaranModel = new JadwalPembayaran();
        $mahasiswaModel = new MahasiswaModel();
        $uangKuliahModel = new UangKuliah();
        $dataJadwal = $jadwalPembayaranModel->find($id_jadwal);
        $dataPembayaranBertahap = array_column($uangKuliahModel->select('npm')->where('jenis_pembayaran', 'bertahap')->findAll(), 'npm');
        $dataMahasiswa = $mahasiswaModel->select('nohp_ortu, nama, nama_ortu, jk_ortu')->whereIn('npm', $dataPembayaranBertahap)->findAll();
        if ($tahap == 1) {
            $tanggal_pembayaran = Carbon::createFromDate($dataJadwal['tanggal_pembayaran_tahap_1'])->locale('id')->translatedFormat('l, j F Y');
        } else if ($tahap == 2) {
            $tanggal_pembayaran = Carbon::createFromDate($dataJadwal['tanggal_pembayaran_tahap_2'])->locale('id')->translatedFormat('l, j F Y');
        } else if ($tahap == 3) {
            $tanggal_pembayaran = Carbon::createFromDate($dataJadwal['tanggal_pembayaran_tahap_3'])->locale('id')->translatedFormat('l, j F Y');
        }

        $data = null;

        foreach ($dataMahasiswa as $dm) {
            $data = array(
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => (string)$dm['nohp_ortu'],
                "type" => "template",
                "template" => array(
                    "name" => "notifikasi_pembayaran",
                    "language" => array(
                        "code" => "id"
                    ),
                    "components" => [
                        array(
                            "type" => "body",
                            "parameters" => [
                                array(
                                    "type" => "text",
                                    "parameter_name" => "ortu",
                                    "text" => $dm['jk_ortu'] == 'Laki-laki' ? "Bapak " . (string)$dm['nama_ortu'] : "Ibu " . (string)$dm['nama_ortu']
                                ),
                                array(
                                    "type" => "text",
                                    "parameter_name" => "tahap",
                                    "text" => "Tahap " . (string)$tahap
                                ),
                                array(
                                    "type" => "text",
                                    "parameter_name" => "mahasiswa",
                                    "text" => (string)$dm['nama']
                                ),
                                array(
                                    "type" => "text",
                                    "parameter_name" => "tanggal",
                                    "text" => (string)$tanggal_pembayaran
                                ),
                            ]
                        )
                    ]
                ),
            );
            $client = \Config\Services::curlrequest();
            try {
                $response = $client->post($this->url, [
                    'json' => $data,
                    'headers' => [
                        'Authorization' => 'Bearer EAAPISuPUC9kBO8ujEdoTqL3wf5pomztI8Vry2enzGaW3gKUk8vO7nRWGKq899CO4egILaY0yoTYzX6hXIvwEKPgaf4qfhysFSeWKBAIFZCcrurae5hAbDXgAAMdrnv41g9CBTFgXre5ibTDL3PEbcGpRfj4ft2dJ6YUYvpu0KsuK8foYYNwokv7jCTXlM0xNnyaTGygpctEF8hpgcWXlD37bB19tO5RR7e9FO',
                        'Content-Type' => 'application/json'
                    ]
                ]);

                if ($response->getStatusCode() == 200) {
                    if ($tahap == 1) {
                        $update = array(
                            "status_notifikasi_tahap_1" => "Dikirim"
                        );
                    } else if ($tahap == 2) {
                        $update = array(
                            "status_notifikasi_tahap_2" => "Dikirim"
                        );
                    } else if ($tahap == 3) {
                        $update = array(
                            "status_notifikasi_tahap_3" => "Dikirim"
                        );
                    }
                    $jadwalPembayaranModel->update($id_jadwal, $update);
                }
            } catch (\Exception $e) {
                return response()->setStatusCode(500)->setJSON([
                    'error' => $e
                ]);
            }
        }

        return response()->setStatusCode(200)->setJSON([
            'message' => 'Success'
        ]);
    }

    public function kirimNotifikasiRekapPresensi()
    {
        $mahasiswaModel = new MahasiswaModel();
        $logNotifikasiModel = new LogNotifikasiPresensiModel();
        $ketidakhadiranModel = new KetidakhadiranModel();
        $presensiModel = new PresensiModel();

        $bulan = date('m');
        if ($bulan == 1) {
            $bulan = 12;
        } else {
            $bulan = $bulan - 1;
        }
        $dataMahasiswa = $mahasiswaModel->select('id, nohp_ortu, nama, nama_ortu, jk_ortu')->findAll();
        $namaBulan = Carbon::createFromDate(date('Y') . "-" . $bulan . "-" . date('1'))->locale('id')->translatedFormat('F');

        $dataTanggal = $this->getWeekdaysInMonth(date('Y'), $bulan);

        foreach ($dataMahasiswa as $dm) {

            $totalIzin = count($ketidakhadiranModel->where('id_mahasiswa', $dm['id'])->where('status_pengajuan', 'Accept')->where('MONTH(tanggal)', $bulan + 1)->findAll());
            $totalHadir = count($presensiModel->select('tanggal')->where('id_mahasiswa', $dm['id'])->where('MONTH(tanggal)', $bulan + 1)->groupBy('tanggal')->findAll());

            $data = array(
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => (string)$dm['nohp_ortu'],
                "type" => "template",
                "template" => array(
                    "name" => "laporan_kehadiran",
                    "language" => array(
                        "code" => "id"
                    ),
                    "components" => [
                        array(
                            "type" => "body",
                            "parameters" => [
                                array(
                                    "type" => "text",
                                    "parameter_name" => "ortu",
                                    "text" => $dm['jk_ortu'] == 'Laki-laki' ? "Bapak " . (string)$dm['nama_ortu'] : "Ibu " . (string)$dm['nama_ortu']
                                ),
                                array(
                                    "type" => "text",
                                    "parameter_name" => "nama",
                                    "text" => (string)$dm['nama']
                                ),
                                array(
                                    "type" => "text",
                                    "parameter_name" => "bulan",
                                    "text" => (string)$namaBulan
                                ),
                                array(
                                    "type" => "text",
                                    "parameter_name" => "hadir",
                                    "text" => (string)$totalHadir
                                ),
                                array(
                                    "type" => "text",
                                    "parameter_name" => "izin",
                                    "text" => (string)$totalIzin
                                ),
                                array(
                                    "type" => "text",
                                    "parameter_name" => "absen",
                                    "text" => (string)(count($dataTanggal) - $totalHadir - $totalIzin)
                                ),
                            ]
                        )
                    ]
                ),
            );
            $client = \Config\Services::curlrequest();
            try {
                $client->post($this->url, [
                    'json' => $data,
                    'headers' => [
                        'Authorization' => 'Bearer EAAPISuPUC9kBO8ujEdoTqL3wf5pomztI8Vry2enzGaW3gKUk8vO7nRWGKq899CO4egILaY0yoTYzX6hXIvwEKPgaf4qfhysFSeWKBAIFZCcrurae5hAbDXgAAMdrnv41g9CBTFgXre5ibTDL3PEbcGpRfj4ft2dJ6YUYvpu0KsuK8foYYNwokv7jCTXlM0xNnyaTGygpctEF8hpgcWXlD37bB19tO5RR7e9FO',
                        'Content-Type' => 'application/json'
                    ]
                ]);
            } catch (\Exception $e) {
                return response()->setStatusCode(500)->setJSON([
                    'error' => $e
                ]);
            }
        }

        $logNotifikasiModel->insert([
            'tanggal' => Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), 'Asia/Jakarta')->format('Y-m-d H:i:s')
        ]);

        return response()->setStatusCode(200)->setJSON([
            'message' => $data
        ]);
    }

    private function getWeekdaysInMonth($year, $month)
    {
        $weekdays = [];
        $startDate = new DateTime("$year-$month-01");
        $endDate = new DateTime($startDate->format('Y-m-t'));

        while ($startDate <= $endDate) {
            $dayOfWeek = $startDate->format('N');
            if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                $weekdays[] = $startDate->format('Y-m-d');
            }
            $startDate->add(new DateInterval('P1D'));
        }
        return $weekdays;
    }
}
