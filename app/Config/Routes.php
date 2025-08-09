<?php

use App\Controllers\Login;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('login', 'Login::login');
$routes->get('/', function () {
    return redirect()->to('/login');
});
$routes->get('logout', 'Login::logout');
$routes->post('login', 'Login::login_action');


$routes->get('admin/home', 'Admin\Home::index', ['filter' => 'adminFilter']);

$routes->get('admin/uang-kuliah/data', 'Admin\UangKuliah::data', ['filter' => 'adminFilter','as' => 'admin.uangkuliah.data']);
$routes->get('admin/uang-kuliah/jadwal', 'Admin\UangKuliah::jadwal', ['filter' => 'adminFilter','as' => 'admin.uangkuliah.jadwal']);
$routes->get('admin/uang-kuliah/create', 'Admin\UangKuliah::create', ['filter' => 'adminFilter','as' => 'admin.uangkuliah.create']);
$routes->get('admin/uang-kuliah/create-jadwal', 'Admin\UangKuliah::createJadwal', ['filter' => 'adminFilter','as' => 'admin.uangkuliah.createjadwal']);
$routes->post('admin/uang-kuliah/store', 'Admin\UangKuliah::store', ['filter' => 'adminFilter','as' => 'admin.uangkuliah.store']);
$routes->post('admin/uang-kuliah/store-jadwal', 'Admin\UangKuliah::storeJadwal', ['filter' => 'adminFilter','as' => 'admin.uangkuliah.storeJadwal']);
$routes->post('admin/uang-kuliah/import', 'Admin\UangKuliah::import', ['filter' => 'adminFilter','as' => 'admin.uangkuliah.import']);

$routes->get('admin/notifikasi', 'Admin\LogNotifikasiController::index', ['filter' => 'adminFilter','as' => 'admin.notifikasi.index']);
$routes->post('admin/notifikasi/kirim-notifikasi-pembayaran', 'Admin\NotifikasiController::kirimNotifikasiPembayaran', ['filter' => 'adminFilter','as' => 'admin.notifikasi.kirimNotifikasiPembayaran']);
$routes->post('admin/notifikasi/kirim-notifikasi-rekap-presensi', 'Admin\NotifikasiController::kirimNotifikasiRekapPresensi', ['filter' => 'adminFilter','as' => 'admin.notifikasi.kirimNotifikasiRekapPresensi']);

$routes->get('admin/matkul', 'Admin\Matkul::index', ['filter' => 'adminFilter']);
$routes->get('admin/matkul/create', 'Admin\Matkul::create', ['filter' => 'adminFilter']);
$routes->post('admin/matkul/store', 'Admin\Matkul::store', ['filter' => 'adminFilter']);
$routes->get('admin/matkul/edit/(:segment)', 'Admin\Matkul::edit/$1', ['filter' => 'adminFilter']);
$routes->post('admin/matkul/update/(:segment)', 'Admin\Matkul::update/$1', ['filter' => 'adminFilter']);
$routes->get('admin/matkul/delete/(:segment)', 'Admin\Matkul::delete/$1', ['filter' => 'adminFilter']);

$routes->get('admin/prodi', 'Admin\ProdiController::index', ['filter' => 'adminFilter']);
$routes->get('admin/prodi/create', 'Admin\ProdiController::create', ['filter' => 'adminFilter']);
$routes->post('admin/prodi/store', 'Admin\ProdiController::store', ['filter' => 'adminFilter']);
$routes->get('admin/prodi/edit/(:segment)', 'Admin\ProdiController::edit/$1', ['filter' => 'adminFilter']);
$routes->post('admin/prodi/update/(:segment)', 'Admin\ProdiController::update/$1', ['filter' => 'adminFilter']);
$routes->get('admin/prodi/delete/(:segment)', 'Admin\ProdiController::delete/$1', ['filter' => 'adminFilter']);

$routes->get('admin/lokasi_presensi', 'Admin\LokasiPresensi::index', ['filter' => 'adminFilter']);
$routes->get('admin/lokasi_presensi/create', 'Admin\LokasiPresensi::create', ['filter' => 'adminFilter']);
$routes->post('admin/lokasi_presensi/store', 'Admin\LokasiPresensi::store', ['filter' => 'adminFilter']);
$routes->get('admin/lokasi_presensi/edit/(:segment)', 'Admin\LokasiPresensi::edit/$1', ['filter' => 'adminFilter']);
$routes->post('admin/lokasi_presensi/update/(:segment)', 'Admin\LokasiPresensi::update/$1', ['filter' => 'adminFilter']);
$routes->get('admin/lokasi_presensi/delete/(:segment)', 'Admin\LokasiPresensi::delete/$1', ['filter' => 'adminFilter']);
$routes->get('admin/lokasi_presensi/detail/(:segment)', 'Admin\LokasiPresensi::detail/$1', ['filter' => 'adminFilter']);

$routes->get('admin/data_dosen', 'Admin\DataDosen::index', ['filter' => 'adminFilter']);
$routes->get('admin/data_dosen/create', 'Admin\DataDosen::create', ['filter' => 'adminFilter']);
$routes->post('admin/data_dosen/store', 'Admin\DataDosen::store', ['filter' => 'adminFilter']);
$routes->get('admin/data_dosen/edit/(:segment)', 'Admin\DataDosen::edit/$1', ['filter' => 'adminFilter']);
$routes->post('admin/data_dosen/update/(:segment)', 'Admin\DataDosen::update/$1', ['filter' => 'adminFilter']);
$routes->get('admin/data_dosen/delete/(:segment)', 'Admin\DataDosen::delete/$1', ['filter' => 'adminFilter']);
$routes->get('admin/data_dosen/detail/(:segment)', 'Admin\DataDosen::detail/$1', ['filter' => 'adminFilter']);
$routes->get('admin/data_dosen/jadwal/(:segment)', 'Admin\DataDosen::get_jadwal/$1', ['filter' => 'adminFilter', 'as' => 'admin.dosen.jadwal']);

$routes->get('admin/data_mahasiswa', 'Admin\DataMahasiswa::index', ['filter' => 'adminFilter']);
$routes->get('admin/data_mahasiswa/create', 'Admin\DataMahasiswa::create', ['filter' => 'adminFilter']);
$routes->post('admin/data_mahasiswa/store', 'Admin\DataMahasiswa::store', ['filter' => 'adminFilter']);
$routes->get('admin/data_mahasiswa/edit/(:segment)', 'Admin\DataMahasiswa::edit/$1', ['filter' => 'adminFilter']);
$routes->post('admin/data_mahasiswa/update/(:segment)', 'Admin\DataMahasiswa::update/$1', ['filter' => 'adminFilter']);
$routes->get('admin/data_mahasiswa/delete/(:segment)', 'Admin\DataMahasiswa::delete/$1', ['filter' => 'adminFilter']);
$routes->get('admin/data_mahasiswa/detail/(:segment)', 'Admin\DataMahasiswa::detail/$1', ['filter' => 'adminFilter']);
$routes->post('admin/data_mahasiswa/import', 'Admin\DataMahasiswa::import', ['filter' => 'adminFilter', 'as' => 'admin.mahasiswa.import']);

$routes->get('admin/rekap_harian', 'Admin\RekapPresensi::rekap_harian', ['filter' => 'adminFilter']);
$routes->get('admin/rekap_bulanan', 'Admin\RekapPresensi::rekap_bulanan', ['filter' => 'adminFilter']);
$routes->get('admin/rekap_bulanan_pdf', 'Admin\RekapPresensi::rekap_bulanan_pdf', ['filter' => 'adminFilter']);

$routes->get('admin/ketidakhadiran', 'Admin\Ketidakhadiran::index', ['filter' => 'adminFilter']);
$routes->get('admin/approved_ketidakhadiran/(:segment)', 'Admin\Ketidakhadiran::approved/$1', ['filter' => 'adminFilter']);
$routes->get('admin/rejected_ketidakhadiran/(:segment)', 'Admin\Ketidakhadiran::rejected/$1', ['filter' => 'adminFilter']);
$routes->get('admin/delete_ketidakhadiran/(:segment)', 'Admin\Ketidakhadiran::delete/$1', ['filter' => 'adminFilter']);

$routes->get('admin/kelas', 'Admin\Kelas::index', ['filter' => 'adminFilter']);
$routes->get('admin/kelas/create', 'Admin\Kelas::create', ['filter' => 'adminFilter']);
$routes->post('admin/kelas/store', 'Admin\Kelas::store', ['filter' => 'adminFilter']);
$routes->get('admin/kelas/edit/(:segment)', 'Admin\Kelas::edit/$1', ['filter' => 'adminFilter']);
$routes->post('admin/kelas/update/(:segment)', 'Admin\Kelas::update/$1', ['filter' => 'adminFilter']);
$routes->get('admin/kelas/delete/(:segment)', 'Admin\Kelas::delete/$1', ['filter' => 'adminFilter']);


$routes->get('dosen/home', 'Dosen\Home::index', ['filter' => 'dosenFilter']);
$routes->get('dosen/rekap-presensi', 'Dosen\RekapPresensi::index', ['filter' => 'dosenFilter', 'as' => 'dosen.rekapPresensi']);
$routes->post('dosen/rekap-presensi/cek-pertemuan', 'Dosen\RekapPresensi::cekPertemuan', ['filter' => 'dosenFilter', 'as' => 'dosen.cekPertemuan']);
$routes->get('dosen/rekap-presensi/unduh', 'Dosen\RekapPresensi::unduh', ['filter' => 'dosenFilter', 'as' => 'dosen.unduh']);
$routes->get('dosen/ketidakhadiran', 'Dosen\Home::ketidakhadiran', ['filter' => 'dosenFilter', 'as' => 'dosen.ketidakhadiran']);
$routes->get('dosen/ketidakhadiran/terima/(:segment)', 'Dosen\Home::terimaKetidakhadiran/$1', ['filter' => 'dosenFilter', 'as' => 'dosen.terimaKetidakhadiran']);
$routes->get('dosen/ketidakhadiran/tolak/(:segment)', 'Dosen\Home::tolakKetidakhadiran/$1', ['filter' => 'dosenFilter', 'as' => 'dosen.tolakKetidakhadiran']);


$routes->get('mahasiswa/home', 'Mahasiswa\Home::index', ['filter' => 'mahasiswaFilter', 'as' => 'mahasiswa.home']);
$routes->post('mahasiswa/presensi_masuk', 'Mahasiswa\Home::presensi_masuk', ['filter' => 'mahasiswaFilter']);
$routes->post('mahasiswa/presensi_masuk_aksi', 'Mahasiswa\Home::presensi_masuk_aksi', ['filter' => 'mahasiswaFilter']);

$routes->post('mahasiswa/presensi_keluar/(:segment)', 'Mahasiswa\Home::presensi_keluar/$1', ['filter' => 'mahasiswaFilter']);
$routes->post('mahasiswa/presensi_keluar_aksi/(:segment)', 'Mahasiswa\Home::presensi_keluar_aksi/$1', ['filter' => 'mahasiswaFilter']);

$routes->get('mahasiswa/rekap_presensi', 'Mahasiswa\RekapPresensi::index', ['filter' => 'mahasiswaFilter']);

$routes->get('mahasiswa/ketidakhadiran', 'Mahasiswa\Ketidakhadiran::index', ['filter' => 'mahasiswaFilter', 'as' => 'mahasiswa.ketidakhadiran.index']);
$routes->get('mahasiswa/ketidakhadiran/create', 'Mahasiswa\Ketidakhadiran::create', ['filter' => 'mahasiswaFilter']);
$routes->post('mahasiswa/ketidakhadiran/matkul', 'Mahasiswa\Ketidakhadiran::getMatkul', ['filter' => 'mahasiswaFilter']);
$routes->post('mahasiswa/ketidakhadiran/store', 'Mahasiswa\Ketidakhadiran::store', ['filter' => 'mahasiswaFilter']);
$routes->get('mahasiswa/ketidakhadiran/edit/(:segment)', 'Mahasiswa\Ketidakhadiran::edit/$1', ['filter' => 'mahasiswaFilter']);
$routes->post('mahasiswa/ketidakhadiran/update/(:segment)', 'Mahasiswa\Ketidakhadiran::update/$1', ['filter' => 'mahasiswaFilter']);
$routes->get('mahasiswa/ketidakhadiran/delete/(:segment)', 'Mahasiswa\Ketidakhadiran::delete/$1', ['filter' => 'mahasiswaFilter']);
$routes->get('mahasiswa/ketidakhadiran/detail/(:segment)', 'Mahasiswa\Ketidakhadiran::detail/$1', ['filter' => 'mahasiswaFilter']);
$routes->get('mahasiswa/lengkapi/data/(:segment)', 'Mahasiswa\Data::index/$1', ['filter' => 'mahasiswaFilter', 'as' => 'mahasiswa.lengkapi.data']);
$routes->post('mahasiswa/lengkapi/store', 'Mahasiswa\Data::store', ['filter' => 'mahasiswaFilter', 'as' => 'mahasiswa.lengkapi.store']);
$routes->post('mahasiswa/matkul/store', 'Mahasiswa\MataKuliahController::store', ['filter' => 'mahasiswaFilter', 'as' => 'mahasiswa.matkul.store']);
$routes->get('mahasiswa/matkul/create', 'Mahasiswa\MataKuliahController::create', ['filter' => 'mahasiswaFilter', 'as' => 'mahasiswa.matkul.create']);
$routes->get('mahasiswa/matkul', 'Mahasiswa\MataKuliahController::index', ['filter' => 'mahasiswaFilter', 'as' => 'mahasiswa.matkul']);
