<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MatkulModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PresensiModel;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;


class RekapPresensi extends BaseController
{
    public function rekap_harian()
    {
        $rules = [
            'filter_tanggal' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pilih tanggal.'
                ],
            ]
        ];

        $presensi_model = new PresensiModel();
        $filter_tanggal = $this->request->getVar('filter_tanggal');
        $filter_matkul = $this->request->getVar('filter_matkul');
        $nama_matkul = null;

        if (!is_null($filter_tanggal) || (!is_null($filter_tanggal) && !is_null($filter_matkul))) {
            if (isset($_GET['excel'])) {
                if (!$this->validate($rules)) return redirect()->back()->with('error', array_values($this->validator->getErrors())[0]);
                $rekap_harian = $presensi_model->rekap_harian_filter($filter_tanggal, $filter_matkul);
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $activeWorksheet = $spreadsheet->getActiveSheet();

                // Merge cells for title
                $spreadsheet->getActiveSheet()->mergeCells('A1:I1');
                $spreadsheet->getActiveSheet()->mergeCells('A3:B3');
                $spreadsheet->getActiveSheet()->mergeCells('C3:E3');
                if (!is_null($filter_matkul)) {
                    $spreadsheet->getActiveSheet()->mergeCells('A4:B4');
                    $spreadsheet->getActiveSheet()->mergeCells('C4:E4');
                }
                // Set title and headers
                $headNo = 4;
                $rows = 5;
                $activeWorksheet->setCellValue('A1', 'REKAP PRESENSI HARIAN');
                $activeWorksheet->setCellValue('A3', 'TANGGAL');
                $activeWorksheet->setCellValue('C3', Carbon::createFromFormat('Y-m-d',$filter_tanggal)->locale('id')->translatedFormat('j F Y'));
                if (!is_null($filter_matkul)) {
                    $matkul = new MatkulModel();
                    $matkul_result = $matkul->select('matkul.id, matkul.matkul, dosen.nama_dosen')
                        ->join('dosen', 'dosen.id = matkul.dosen_pengampu')
                        ->find($filter_matkul);
                    $nama_matkul = $matkul_result['matkul'] . ' - ' . $matkul_result['nama_dosen'];
                    $activeWorksheet->setCellValue('A' . $headNo, 'Mata Kuliah');
                    $activeWorksheet->setCellValue('C' . $headNo, $nama_matkul);
                    $headNo++;
                    $rows++;
                }
                $activeWorksheet->setCellValue('A' . $headNo, 'NO');
                $activeWorksheet->setCellValue('B' . $headNo, 'Nama Mahasiswa');
                $activeWorksheet->setCellValue('C' . $headNo, 'Tanggal Masuk');
                $activeWorksheet->setCellValue('D' . $headNo, 'Jam Masuk');
                $activeWorksheet->setCellValue('E' . $headNo, 'Tanggal Keluar');
                $activeWorksheet->setCellValue('F' . $headNo, 'Jam Keluar');
                $activeWorksheet->setCellValue('G' . $headNo, 'Total Jam Kuliah');
                $activeWorksheet->setCellValue('H' . $headNo, 'Total Terlambat');
                $activeWorksheet->setCellValue('I' . $headNo, 'Total Cepat Pulang');

                $activeWorksheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $activeWorksheet->getStyle('A' . $headNo . ':I' . $headNo)->getFont()->setBold(true);

                $no = 1;

                foreach ($rekap_harian as $rekap) {
                    // Hitung total jam kuliah
                    $jam_masuk = strtotime($rekap['tanggal'] . ' ' . $rekap['jam_masuk']);
                    $jam_keluar = strtotime($rekap['tanggal'] . ' ' . $rekap['jam_keluar']);
                    $total_waktu = $jam_keluar - $jam_masuk;
                    $jam = floor($total_waktu / 3600);
                    $menit = floor(($total_waktu % 3600) / 60);

                    // Hitung keterlambatan hanya jika ada presensi keluar
                    $keterlambatan = 0;
                    $jam_terlambat = 0;
                    $menit_terlambat = 0;

                    if ($rekap['jam_keluar'] != '00:00:00') {
                        $jam_masuk_seharusnya = strtotime($rekap['tanggal'] . ' ' . $rekap['jam_masuk_kampus']);
                        if ($jam_masuk > $jam_masuk_seharusnya) {
                            $keterlambatan = $jam_masuk - $jam_masuk_seharusnya;
                            $jam_terlambat = floor($keterlambatan / 3600);
                            $menit_terlambat = floor(($keterlambatan % 3600) / 60);
                        }
                    }

                    // Hitung pulang cepat hanya jika ada presensi keluar
                    $pulang_cepat = 0;
                    $jam_cepat_pulang = 0;
                    $menit_cepat_pulang = 0;

                    if ($rekap['jam_keluar'] != '00:00:00') {
                        $jam_pulang_seharusnya = strtotime($rekap['tanggal'] . ' ' . $rekap['jam_pulang_kampus']);
                        if ($jam_keluar < $jam_pulang_seharusnya) {
                            $pulang_cepat = $jam_pulang_seharusnya - $jam_keluar;
                            $jam_cepat_pulang = floor($pulang_cepat / 3600);
                            $menit_cepat_pulang = floor(($pulang_cepat % 3600) / 60);
                        }
                    }

                    $activeWorksheet->setCellValue('A' . $rows, $no++);
                    $activeWorksheet->setCellValue('B' . $rows, $rekap['nama']);
                    $activeWorksheet->setCellValue('C' . $rows, Carbon::createFromFormat('Y-m-d',$rekap['tanggal'])->locale('id')->translatedFormat('j F Y'));
                    $activeWorksheet->setCellValue('D' . $rows, Carbon::createFromFormat('H:i:s',$rekap['jam_masuk'])->format('H:i'));
                    $activeWorksheet->setCellValue('E' . $rows, Carbon::createFromFormat('Y-m-d',$rekap['tanggal'])->locale('id')->translatedFormat('j F Y'));
                    $activeWorksheet->setCellValue('F' . $rows, Carbon::createFromFormat('H:i:s',$rekap['jam_keluar'])->format('H:i'));
                    $activeWorksheet->setCellValue('G' . $rows, sprintf('%02d jam %02d menit', $jam, $menit));
                    $activeWorksheet->setCellValue('H' . $rows, $keterlambatan > 0 ? sprintf('%02d jam %02d menit', $jam_terlambat, $menit_terlambat) : '-');
                    $activeWorksheet->setCellValue('I' . $rows, $pulang_cepat > 0 ? sprintf('%02d jam %02d menit', $jam_cepat_pulang, $menit_cepat_pulang) : '-');

                    $rows++;
                }

                // Auto-size columns
                foreach (range('A', 'I') as $col) {
                    $activeWorksheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Border styling
                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ];
                $activeWorksheet->getStyle('A' . $headNo . ':I' . $headNo)->applyFromArray($styleArray);
                $activeWorksheet->getStyle('A' . $rows-1 . ':I' . $rows-1)->applyFromArray($styleArray);

                // Output Excel file
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="rekap_presensi_harian.xlsx"');
                header('Cache-Control: max-age=0');

                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $writer->save('php://output');
                exit;
            } else {
                if (!$this->validate($rules)) return redirect()->back()->with('error', array_values($this->validator->getErrors())[0]);
                $rekap_harian = $presensi_model->rekap_harian_filter($filter_tanggal, $filter_matkul);
                $matkul = new MatkulModel();
                if (!is_null($filter_matkul)) {
                    $matkul_result = $matkul->select('matkul.id, matkul.matkul, dosen.nama_dosen')
                        ->join('dosen', 'dosen.id = matkul.dosen_pengampu')
                        ->find($filter_matkul);
                    $nama_matkul = $matkul_result['matkul'] . ' - ' . $matkul_result['nama_dosen'];
                }
            }
        } else {
            $rekap_harian = $presensi_model->rekap_harian();
        }

        $matkulModel = new MatkulModel();

        $data = [
            'title' => 'Rekap Harian',
            'tanggal' => $filter_tanggal,
            'nama_matkul' => $nama_matkul,
            'rekap_harian' => $rekap_harian,
            'matkul' => $matkulModel->select('matkul.id, matkul.matkul, dosen.nama_dosen')
                ->join('dosen', 'dosen.id = matkul.dosen_pengampu')
                ->findAll() // Mengambil semua data Matkul
        ];


        return view('admin/rekap_presensi/rekap_harian', $data);
    }

    public function rekap_bulanan()
    {
        $presensi_model = new PresensiModel();
        $filter_tanggal = $this->request->getVar('filter_tanggal');
        $filter_bulan = $this->request->getVar('filter_bulan');
        $filter_tahun = $this->request->getVar('filter_tahun');

        if ($filter_bulan) {
            if (isset($_GET['pdf'])) {
                $rekap_bulanan = $presensi_model->rekap_bulanan_filter($filter_bulan, $filter_tahun);

                foreach ($rekap_bulanan as &$rekap) {
                    // Hitung total jam kuliah
                    $jam_masuk = strtotime($rekap['tanggal'] . ' ' . $rekap['jam_masuk']);
                    $jam_keluar = strtotime($rekap['tanggal'] . ' ' . $rekap['jam_keluar']);
                    $total_waktu = $jam_keluar - $jam_masuk;

                    $jam = floor($total_waktu / 3600);
                    $menit = floor(($total_waktu % 3600) / 60);
                    $rekap['total_jam'] = sprintf('%02d jam %02d menit', $jam, $menit);

                    // Hitung keterlambatan hanya jika ada presensi keluar
                    $keterlambatan = 0;
                    $jam_terlambat = 0;
                    $menit_terlambat = 0;

                    if ($rekap['jam_keluar'] != '00:00:00') {
                        $jam_masuk_seharusnya = strtotime($rekap['tanggal'] . ' ' . $rekap['jam_masuk_kampus']);
                        if ($jam_masuk > $jam_masuk_seharusnya) {
                            $keterlambatan = $jam_masuk - $jam_masuk_seharusnya;
                            $jam_terlambat = floor($keterlambatan / 3600);
                            $menit_terlambat = floor(($keterlambatan % 3600) / 60);
                        }
                    }

                    // Hitung pulang cepat hanya jika ada presensi keluar
                    $pulang_cepat = 0;
                    $jam_cepat_pulang = 0;
                    $menit_cepat_pulang = 0;

                    if ($rekap['jam_keluar'] != '00:00:00') {
                        $jam_pulang_seharusnya = strtotime($rekap['tanggal'] . ' ' . $rekap['jam_pulang_kampus']);
                        if ($jam_keluar < $jam_pulang_seharusnya) {
                            $pulang_cepat = $jam_pulang_seharusnya - $jam_keluar;
                            $jam_cepat_pulang = floor($pulang_cepat / 3600);
                            $menit_cepat_pulang = floor(($pulang_cepat % 3600) / 60);
                        }
                    }
                }

                // Setup PDF
                $options = new Options();
                $options->set('defaultFont', 'Arial');
                $dompdf = new Dompdf($options);

                $data = [
                    'rekap_bulanan' => $rekap_bulanan,
                    'bulan' => $filter_bulan,
                    'tahun' => $filter_tahun,
                ];

                $html = view('admin/rekap_presensi/pdf_rekap_bulanan', $data);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'landscape');
                $dompdf->render();
                $dompdf->stream('rekap_presensi_bulanan.pdf', ['Attachment' => false]);
                exit;
            } else {
                $rekap_bulanan = $presensi_model->rekap_bulanan_filter($filter_bulan, $filter_tahun);
            }
        } else {
            $rekap_bulanan = $presensi_model->rekap_bulanan();
        }

        if (!$filter_tanggal && $filter_bulan && $filter_tahun) {
            $filter_tanggal = $filter_tahun . '-' . $filter_bulan . '-01';
        }

        $data = [
            'title' => 'Rekap Bulanan',
            'bulan' => $filter_bulan,
            'tahun' => $filter_tahun,
            'rekap_bulanan' => $rekap_bulanan,
            'tanggal' => $filter_tanggal
        ];

        return view('admin/rekap_presensi/rekap_bulanan', $data);
    }
}
