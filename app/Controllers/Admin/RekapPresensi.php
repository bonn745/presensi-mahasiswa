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
                $activeWorksheet->setCellValue('B4', 'Nama Pegawai');
                $activeWorksheet->setCellValue('C4', 'Tanggal Masuk');
                $activeWorksheet->setCellValue('D4', 'Jam Masuk');
                $activeWorksheet->setCellValue('E4', 'Tanggal Keluar');
                $activeWorksheet->setCellValue('F4', 'Jam Keluar');
                $activeWorksheet->setCellValue('G4', 'Total Jam Kerja');
                $activeWorksheet->setCellValue('H4', 'Total Terlambat');

                $activeWorksheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $activeWorksheet->getStyle('A4:H4')->getFont()->setBold(true);

                $rows = 5;
                $no = 1;

                foreach ($rekap_harian as $rekap) {
                    $timestamp_jam_masuk = strtotime($rekap['jam_masuk']);
                    $timestamp_jam_keluar = strtotime($rekap['jam_keluar']);
                    $selisih = $timestamp_jam_keluar - $timestamp_jam_masuk;
                    $jam = floor($selisih / 3600);
                    $menit = floor(($selisih % 3600) / 60);

                    $jam_masuk_real = strtotime($rekap['jam_masuk']);
                    $jam_masuk_kantor = isset($rekap['jam_masuk_kantor']) ? strtotime($rekap['jam_masuk_kantor']) : $jam_masuk_real;
                    $selisih_terlambat = $jam_masuk_real - $jam_masuk_kantor;
                    $jam_terlambat = floor($selisih_terlambat / 3600);
                    $menit_terlambat = floor(($selisih_terlambat % 3600) / 60);

                    $jam_keluar_real = strtotime($rekap['jam_keluar']);
                    $jam_pulang_kantor = strtotime($rekap['jam_pulang_kantor']);
                    $selisih_cepat_pulang = $jam_pulang_kantor - $jam_keluar_real;
                    $jam_cepat_pulang = 0;
                    $menit_cepat_pulang = 0;
                    if ($selisih_cepat_pulang > 0) {
                        $jam_cepat_pulang = floor($selisih_cepat_pulang / 3600);
                        $menit_cepat_pulang = floor(($selisih_cepat_pulang % 3600) / 60);
                    }

                    $activeWorksheet->setCellValue('A' . $rows, $no++);
                    $activeWorksheet->setCellValue('B' . $rows, $rekap['nama']);
                    $activeWorksheet->setCellValue('C' . $rows, $rekap['tanggal_masuk']);
                    $activeWorksheet->setCellValue('D' . $rows, $rekap['jam_masuk']);
                    $activeWorksheet->setCellValue('E' . $rows, $rekap['tanggal_keluar']);
                    $activeWorksheet->setCellValue('F' . $rows, $rekap['jam_keluar']);
                    $activeWorksheet->setCellValue('G' . $rows, $jam . ' jam ' . $menit . ' menit');
                    $activeWorksheet->setCellValue('H' . $rows, $jam_terlambat . ' jam ' . $menit_terlambat . ' menit');

                    $rows++;
                }

                $lastRow = $rows - 1;

                // Auto-size all columns
                foreach (range('A', 'H') as $col) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
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
                $spreadsheet->getActiveSheet()->getStyle('A4:H' . $lastRow)->applyFromArray($styleArray);

                // Bold header
                $spreadsheet->getActiveSheet()->getStyle('A4:H4')->getFont()->setBold(true);

                // Output to browser
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="rekap_presensi_harian.xlsx"');
                header('Cache-Control: max-age=0');

                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $writer->save('php://output');
                exit; // penting agar tidak melanjutkan render view
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
                    // Total jam kerja
                    $masuk = strtotime($rekap['jam_masuk']);
                    $keluar = strtotime($rekap['jam_keluar']);
                    $selisih = $keluar - $masuk;
                    $rekap['total_jam'] = floor($selisih / 3600) . ' jam ' . floor(($selisih % 3600) / 60) . ' menit';

                    // Terlambat
                    $jam_masuk_kantor = isset($rekap['jam_masuk_kantor']) ? strtotime($rekap['jam_masuk_kantor']) : $masuk;
                    $terlambat = $masuk - $jam_masuk_kantor;
                    $rekap['terlambat'] = ($terlambat > 0)
                        ? floor($terlambat / 3600) . ' jam ' . floor(($terlambat % 3600) / 60) . ' menit'
                        : '-';

                    // Cepat pulang
                    $jam_pulang_kantor = strtotime($rekap['jam_pulang_kantor']);
                    $cepat_pulang = $jam_pulang_kantor - $keluar;
                    $rekap['cepat_pulang'] = ($cepat_pulang > 0)
                        ? floor($cepat_pulang / 3600) . ' jam ' . floor(($cepat_pulang % 3600) / 60) . ' menit'
                        : '-';
                }

                // HTML View
                $data = [
                    'rekap_bulanan' => $rekap_bulanan,
                    'bulan' => $filter_bulan,
                    'tahun' => $filter_tahun,
                ];
                $html = view('admin/rekap_presensi/pdf_rekap_bulanan', $data);

                // Setup PDF
                $options = new Options();
                $options->set('defaultFont', 'Arial');
                $dompdf = new Dompdf($options);
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
            'rekap_bulanan' =>  $rekap_bulanan,
            'tanggal' => $filter_tanggal
        ];

        return view('admin/rekap_presensi/rekap_bulanan', $data);
    }
}
