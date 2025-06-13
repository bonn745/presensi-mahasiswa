<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
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
        $presensi_model = new PresensiModel();
        $filter_tanggal = $this->request->getVar('filter_tanggal');

        if ($filter_tanggal) {
            if (isset($_GET['excel'])) {
                $rekap_harian = $presensi_model->rekap_harian_filter($filter_tanggal);
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $activeWorksheet = $spreadsheet->getActiveSheet();

                // Merge cells for title
                $spreadsheet->getActiveSheet()->mergeCells('A1:C1');
                $spreadsheet->getActiveSheet()->mergeCells('A3:B3');
                $spreadsheet->getActiveSheet()->mergeCells('C3:E3');

                // Set title and headers
                $activeWorksheet->setCellValue('A1', 'REKAP PRESENSI HARIAN');
                $activeWorksheet->setCellValue('A3', 'TANGGAL');
                $activeWorksheet->setCellValue('C3', $filter_tanggal);
                $activeWorksheet->setCellValue('A4', 'NO');
                $activeWorksheet->setCellValue('B4', 'Nama Mahasiswa');
                $activeWorksheet->setCellValue('C4', 'Tanggal Masuk');
                $activeWorksheet->setCellValue('D4', 'Jam Masuk');
                $activeWorksheet->setCellValue('E4', 'Tanggal Keluar');
                $activeWorksheet->setCellValue('F4', 'Jam Keluar');
                $activeWorksheet->setCellValue('G4', 'Total Jam Kuliah');
                $activeWorksheet->setCellValue('H4', 'Total Terlambat');
                $activeWorksheet->setCellValue('I4', 'Total Cepat Pulang');

                $activeWorksheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $activeWorksheet->getStyle('A4:I4')->getFont()->setBold(true);

                $rows = 5;
                $no = 1;

                foreach ($rekap_harian as $rekap) {
                    // Hitung total jam kuliah
                    $jam_masuk = strtotime($rekap['tanggal_masuk'] . ' ' . $rekap['jam_masuk']);
                    $jam_keluar = strtotime($rekap['tanggal_keluar'] . ' ' . $rekap['jam_keluar']);
                    $total_waktu = $jam_keluar - $jam_masuk;
                    $jam = floor($total_waktu / 3600);
                    $menit = floor(($total_waktu % 3600) / 60);

                    // Hitung keterlambatan hanya jika ada presensi keluar
                    $keterlambatan = 0;
                    $jam_terlambat = 0;
                    $menit_terlambat = 0;

                    if ($rekap['jam_keluar'] != '00:00:00' && $rekap['tanggal_keluar'] != null) {
                        $jam_masuk_seharusnya = strtotime($rekap['tanggal_masuk'] . ' ' . $rekap['jam_masuk_kampus']);
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

                    if ($rekap['jam_keluar'] != '00:00:00' && $rekap['tanggal_keluar'] != null) {
                        $jam_pulang_seharusnya = strtotime($rekap['tanggal_keluar'] . ' ' . $rekap['jam_pulang_kampus']);
                        if ($jam_keluar < $jam_pulang_seharusnya) {
                            $pulang_cepat = $jam_pulang_seharusnya - $jam_keluar;
                            $jam_cepat_pulang = floor($pulang_cepat / 3600);
                            $menit_cepat_pulang = floor(($pulang_cepat % 3600) / 60);
                        }
                    }

                    $activeWorksheet->setCellValue('A' . $rows, $no++);
                    $activeWorksheet->setCellValue('B' . $rows, $rekap['nama']);
                    $activeWorksheet->setCellValue('C' . $rows, $rekap['tanggal_masuk']);
                    $activeWorksheet->setCellValue('D' . $rows, $rekap['jam_masuk']);
                    $activeWorksheet->setCellValue('E' . $rows, $rekap['tanggal_keluar']);
                    $activeWorksheet->setCellValue('F' . $rows, $rekap['jam_keluar']);
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
                $activeWorksheet->getStyle('A4:I' . ($rows - 1))->applyFromArray($styleArray);

                // Output Excel file
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="rekap_presensi_harian.xlsx"');
                header('Cache-Control: max-age=0');

                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $writer->save('php://output');
                exit;
            } else {
                $rekap_harian = $presensi_model->rekap_harian_filter($filter_tanggal);
            }
        } else {
            $rekap_harian = $presensi_model->rekap_harian();
        }

        $data = [
            'title' => 'Rekap Harian',
            'tanggal' => $filter_tanggal,
            'rekap_harian' => $rekap_harian
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
                    $jam_masuk = strtotime($rekap['tanggal_masuk'] . ' ' . $rekap['jam_masuk']);
                    $jam_keluar = strtotime($rekap['tanggal_keluar'] . ' ' . $rekap['jam_keluar']);
                    $total_waktu = $jam_keluar - $jam_masuk;

                    $jam = floor($total_waktu / 3600);
                    $menit = floor(($total_waktu % 3600) / 60);
                    $rekap['total_jam'] = sprintf('%02d jam %02d menit', $jam, $menit);

                    // Hitung keterlambatan hanya jika ada presensi keluar
                    $keterlambatan = 0;
                    $jam_terlambat = 0;
                    $menit_terlambat = 0;

                    if ($rekap['jam_keluar'] != '00:00:00' && $rekap['tanggal_keluar'] != null) {
                        $jam_masuk_seharusnya = strtotime($rekap['tanggal_masuk'] . ' ' . $rekap['jam_masuk_kampus']);
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

                    if ($rekap['jam_keluar'] != '00:00:00' && $rekap['tanggal_keluar'] != null) {
                        $jam_pulang_seharusnya = strtotime($rekap['tanggal_keluar'] . ' ' . $rekap['jam_pulang_kampus']);
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
