<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index');
$routes->get('logout', 'Login::logout');
$routes->post('login', 'Login::login_action');


$routes->get('admin/home', 'Admin\Home::index', ['filter' => 'adminFilter']);
$routes->get('admin/jabatan', 'Admin\Jabatan::index', ['filter' => 'adminFilter']);
$routes->get('admin/jabatan/create', 'Admin\Jabatan::create', ['filter' => 'adminFilter']);
$routes->post('admin/jabatan/store', 'Admin\Jabatan::store', ['filter' => 'adminFilter']);
$routes->get('admin/jabatan/edit/(:segment)', 'Admin\Jabatan::edit/$1', ['filter' => 'adminFilter']);
$routes->post('admin/jabatan/update/(:segment)', 'Admin\Jabatan::update/$1', ['filter' => 'adminFilter']);
$routes->get('admin/jabatan/delete/(:segment)', 'Admin\Jabatan::delete/$1', ['filter' => 'adminFilter']);

$routes->get('admin/lokasi_presensi', 'Admin\LokasiPresensi::index', ['filter' => 'adminFilter']);
$routes->get('admin/lokasi_presensi/create', 'Admin\LokasiPresensi::create', ['filter' => 'adminFilter']);
$routes->post('admin/lokasi_presensi/store', 'Admin\LokasiPresensi::store', ['filter' => 'adminFilter']);
$routes->get('admin/lokasi_presensi/edit/(:segment)', 'Admin\LokasiPresensi::edit/$1', ['filter' => 'adminFilter']);
$routes->post('admin/lokasi_presensi/update/(:segment)', 'Admin\LokasiPresensi::update/$1', ['filter' => 'adminFilter']);
$routes->get('admin/lokasi_presensi/delete/(:segment)', 'Admin\LokasiPresensi::delete/$1', ['filter' => 'adminFilter']);
$routes->get('admin/lokasi_presensi/detail/(:segment)', 'Admin\LokasiPresensi::detail/$1', ['filter' => 'adminFilter']);

$routes->get('admin/data_pegawai', 'Admin\DataPegawai::index', ['filter' => 'adminFilter']);
$routes->get('admin/data_pegawai/create', 'Admin\DataPegawai::create', ['filter' => 'adminFilter']);
$routes->post('admin/data_pegawai/store', 'Admin\DataPegawai::store', ['filter' => 'adminFilter']);
$routes->get('admin/data_pegawai/edit/(:segment)', 'Admin\DataPegawai::edit/$1', ['filter' => 'adminFilter']);
$routes->post('admin/data_pegawai/update/(:segment)', 'Admin\DataPegawai::update/$1', ['filter' => 'adminFilter']);
$routes->get('admin/data_pegawai/delete/(:segment)', 'Admin\DataPegawai::delete/$1', ['filter' => 'adminFilter']);
$routes->get('admin/data_pegawai/detail/(:segment)', 'Admin\DataPegawai::detail/$1', ['filter' => 'adminFilter']);

$routes->get('admin/rekap_harian', 'Admin\RekapPresensi::rekap_harian', ['filter' => 'adminFilter']);
$routes->get('admin/rekap_bulanan', 'Admin\RekapPresensi::rekap_bulanan', ['filter' => 'adminFilter']);
$routes->get('admin/rekap_bulanan_pdf', 'Admin\RekapPresensi::rekap_bulanan_pdf', ['filter' => 'adminFilter']);

$routes->get('admin/kehadiran', 'Admin\Kehadiran::index', ['filter' => 'adminFilter']);
$routes->get('admin/approved_kehadiran/(:segment)', 'Admin\Kehadiran::approved/$1', ['filter' => 'adminFilter']);
$routes->get('admin/rejected_kehadiran/(:segment)', 'Admin\Kehadiran::rejected/$1', ['filter' => 'adminFilter']);
$routes->get('admin/delete_kehadiran/(:segment)', 'Admin\Kehadiran::delete/$1', ['filter' => 'adminFilter']);




$routes->get('pegawai/home', 'Pegawai\Home::index', ['filter' => 'pegawaiFilter']);
$routes->post('pegawai/presensi_masuk', 'Pegawai\Home::presensi_masuk', ['filter' => 'pegawaiFilter']);
$routes->post('pegawai/presensi_masuk_aksi', 'Pegawai\Home::presensi_masuk_aksi', ['filter' => 'pegawaiFilter']);

$routes->post('pegawai/presensi_keluar/(:segment)', 'Pegawai\Home::presensi_keluar/$1', ['filter' => 'pegawaiFilter']);
$routes->post('pegawai/presensi_keluar_aksi/(:segment)', 'Pegawai\Home::presensi_keluar_aksi/$1', ['filter' => 'pegawaiFilter']);

$routes->get('pegawai/rekap_presensi', 'Pegawai\RekapPresensi::index', ['filter' => 'pegawaiFilter']);

$routes->get('pegawai/kehadiran', 'Pegawai\Kehadiran::index', ['filter' => 'pegawaiFilter']);
$routes->get('pegawai/kehadiran/create', 'Pegawai\Kehadiran::create', ['filter' => 'pegawaiFilter']);
$routes->post('pegawai/kehadiran/store', 'Pegawai\Kehadiran::store', ['filter' => 'pegawaiFilter']);
$routes->get('pegawai/kehadiran/edit/(:segment)', 'Pegawai\Kehadiran::edit/$1', ['filter' => 'pegawaiFilter']);
$routes->post('pegawai/kehadiran/update/(:segment)', 'Pegawai\Kehadiran::update/$1', ['filter' => 'pegawaiFilter']);
$routes->get('pegawai/kehadiran/delete/(:segment)', 'Pegawai\Kehadiran::delete/$1', ['filter' => 'pegawaiFilter']);
$routes->get('pegawai/kehadiran/detail/(:segment)', 'Pegawai\Kehadiran::detail/$1', ['filter' => 'pegawaiFilter']);
