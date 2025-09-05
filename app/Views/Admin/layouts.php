<?php

use App\Database\Migrations\Jabatan;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.svg') ?>" type="image/x-icon" />
    <title><?= $title ?></title>

    <!-- ========== All CSS files linkup ========= -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>" />
    <link rel="stylesheet" href=" <?= base_url('assets/css/lineicons.css') ?>" />
    <link rel="stylesheet" href=" <?= base_url('assets/css/materialdesignicons.min.css') ?>" />
    <link rel="stylesheet" href=" <?= base_url('assets/css/fullcalendar.css') ?>" />
    <link rel="stylesheet" href=" <?= base_url('assets/css/main.css') ?>" />
    <?= $this->renderSection('styles') ?>


    <!-- ========== Tabler Icon ========= -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.28.1/tabler-icons.min.css" integrity="sha512-UuL1Le1IzormILxFr3ki91VGuPYjsKQkRFUvSrEuwdVCvYt6a1X73cJ8sWb/1E726+rfDRexUn528XRdqrSAOw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- ========== Datatables ========= -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <!-- ========== leaflet CSS ========= -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" />

    <!-- ========== leaflet js ========= -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

</head>

<body class="bg-white">
    <!-- ======== Preloader =========== -->
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <!-- ======== Preloader =========== -->

    <!-- ======== sidebar-nav start =========== -->
    <aside class="sidebar-nav-wrapper">
        <div class="navbar-logo">
            <img style="width: 90%;" src="https://bunghatta.ac.id/v.5/images/logo/logo3.png" alt="logo" />
            </a>
        </div>

        <nav class="sidebar-nav">
            <ul>

                <!-- Dashboard -->
                <li class="nav-item mb-2">
                    <a href="<?= base_url('admin/home') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                            <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                        </svg>
                        <span class="text">Dashboard</span>
                    </a>
                </li>

                <!-- Data Admin -->
                <li class="nav-item mb-2">
                    <a href="<?= base_url('admin/data_dosen') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M3 7l1 -2h16l1 2" />
                            <path d="M4 7v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-11" />
                            <path d="M12 12l0 .01" />
                        </svg>
                        <span class="text">Data Dosen</span>
                    </a>
                </li>


                <!-- Data Pegawai -->
                <li class="nav-item mb-2">
                    <a href="<?= base_url('admin/data_mahasiswa') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                            <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                        </svg>
                        <span class="text">Data Mahasiswa</span>
                    </a>
                </li>

                <!-- Master Data -->
                <li class="nav-item nav-item-has-children mb-2">
                    <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#master-data"
                        aria-controls="master-data" aria-expanded="false" aria-label="Toggle navigation">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 6m-8 0a8 3 0 1 0 16 0a8 3 0 1 0 -16 0" />
                            <path d="M4 6v6a8 3 0 0 0 16 0v-6" />
                            <path d="M4 12v6a8 3 0 0 0 16 0v-6" />
                        </svg>
                        <span class="text">Master Data</span>
                    </a>
                    <ul id="master-data" class="collapse dropdown-nav">
                        <li><a href="<?= base_url('admin/prodi') ?>">Data Prodi</a></li>
                        <li><a href="<?= base_url('admin/matkul') ?>">Data Matkul</a></li>
                        <li><a href="<?= base_url('admin/lokasi_presensi') ?>">Lokasi Presensi</a></li>
                        <li><a href="<?= base_url('admin/kelas') ?>">Data Kelas</a></li>
                    </ul>
                </li>

                <!-- Rekap Presensi -->
                <li class="nav-item nav-item-has-children mb-2">
                    <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#ddmenu_1"
                        aria-controls="ddmenu_1" aria-expanded="false" aria-label="Toggle navigation">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                            <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                            <path d="M9 17v-4" />
                            <path d="M12 17v-1" />
                            <path d="M15 17v-2" />
                            <path d="M12 17v-1" />
                        </svg>
                        <span class="text">Rekap Presensi</span>
                    </a>
                    <ul id="ddmenu_1" class="collapse dropdown-nav">
                        <li><a href="<?= base_url('admin/rekap_harian') ?>">Rekap Harian</a></li>
                        <li><a href="<?= base_url('admin/rekap_bulanan') ?>">Rekap Bulanan</a></li>
                        <li><a href="<?= url_to('admin.notifikasi.index') ?>">Notifikasi</a></li>
                    </ul>
                </li>

                <!-- Cicilan Uang Kuliah -->
                <li class="nav-item nav-item-has-children mb-2">
                    <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#ddmenu_cicilan"
                        aria-controls="ddmenu_cicilan" aria-expanded="false" aria-label="Toggle navigation">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 1v22M5 6h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2H5a2 2 0 0 1 -2 -2V8a2 2 0 0 1 2 -2z" />
                        </svg>
                        <span class="text">Cicilan Uang Kuliah</span>
                    </a>
                    <ul id="ddmenu_cicilan" class="collapse dropdown-nav">
                        <li>
                            <a href="<?= url_to('admin.uangkuliah.data') ?>">
                                <i class="bi bi-cash-stack me-2"></i>Data Pembayaran
                            </a>
                        </li>
                        <li>
                            <a href="<?= url_to('admin.uangkuliah.jadwal') ?>">
                                <i class="bi bi-cash-stack me-2"></i>Jadwal Pembayaran
                            </a>
                        </li>
                    </ul>
                </li>


                <!-- Kehadiran -->
                <li class="nav-item mb-2">
                    <a href="<?= base_url('admin/ketidakhadiran') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                            <path d="M15 19l2 2l4 -4" />
                        </svg>
                        <span class="text">Ketidakhadiran</span>
                    </a>
                </li>

            </ul>
        </nav>
    </aside>
    <div class="overlay"></div>
    <!-- ======== sidebar-nav end =========== -->

    <!-- ======== main-wrapper start =========== -->
    <main class="main-wrapper">
        <!-- ========== header start ========== -->
        <header class="header border-bottom">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-6">
                        <div class="header-left d-flex align-items-center">
                            <div class="menu-toggle-btn mr-15">
                                <button id="menu-toggle" class="main-btn primary-btn btn-hover">
                                    <i class="lni lni-chevron-left me-2"></i> Menu
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-7 col-6">
                        <div class="header-right">
                            <!-- Profile Start -->
                            <div class="profile-box">
                                <?php
                                $role     = session()->get('role_id');
                                $username = session()->get('username');
                                $email    = session()->get('email');
                                $initial  = strtoupper(substr($username, 0, 1));
                                ?>

                                <div class="dropdown">
                                    <button class="dropdown-toggle d-flex align-items-center bg-transparent border-0" type="button"
                                        id="profileMenu" data-bs-toggle="dropdown" aria-expanded="false">

                                        <!-- Inisial -->
                                        <div class="initial-circle me-2 text-white d-flex justify-content-center align-items-center"
                                            style="width: 40px; height: 40px; border-radius: 50%; background-color: #3b82f6; font-weight: bold; font-size: 1.125rem;">
                                            <?= $initial ?>
                                        </div>

                                        <!-- Nama dan role -->
                                        <div class="profile-details text-start">
                                            <h6 class="mb-0 fw-semibold"><?= $username ?></h6>
                                            <small class="text-muted"><?= ucfirst($role) ?></small>
                                        </div>

                                        <i class="fas fa-chevron-down ms-2 text-muted"></i>
                                    </button>

                                    <!-- Dropdown menu -->
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileMenu">
                                        <li class="dropdown-header p-3 d-flex align-items-center">
                                            <div class="initial-circle me-3 text-white d-flex justify-content-center align-items-center"
                                                style="width: 40px; height: 40px; border-radius: 50%; background-color: #3b82f6; font-weight: bold; font-size: 1.125rem;">
                                                <?= $initial ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold"><?= $username ?></h6>
                                                <small class="text-muted"><?= $email ?></small>
                                            </div>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">
                                                <i class="fas fa-sign-out-alt me-2"></i> Keluar
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Profile End -->
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- ========== header end ========== -->

        <!-- ========== section start ========== -->
        <section class="section">
            <div class="container-fluid">
                <!-- ========== title-wrapper start ========== -->
                <div class="title-wrapper pt-30">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="title">
                                <h2><?= $title ?></h2>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
                </div>
                <!-- ========== title-wrapper end ========== -->
                <?= $this->renderSection('content') ?>
            </div>
            <!-- end container -->
        </section>
        <!-- ========== section end ========== -->

        <!-- ========== footer start =========== -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 order-last order-md-first">
                        <div class="copyright text-center text-md-start">
                            <p class="text-sm">
                                Designed and Developed by
                                <a href="https://plainadmin.com" rel="nofollow" target="_blank">
                                    PlainAdmin
                                </a>
                            </p>
                        </div>
                    </div>
                    <!-- end col-->
                    <div class="col-md-6">
                        <div class="terms d-flex justify-content-center justify-content-md-end">
                            <a href="#0" class="text-sm">Term & Conditions</a>
                            <a href="#0" class="text-sm ml-15">Privacy & Policy</a>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </footer>
        <!-- ========== footer end =========== -->
    </main>
    <!-- ======== main-wrapper end =========== -->

    <!-- ========= All Javascript files linkup ======== -->
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/jvectormap.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/polyfill.js') ?>"></script>
    <script src="<?= base_url('assets/js/main.js') ?>"></script>

    <!-- Font Awesome (Versi Terbaru) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        //datatables
        $(document).ready(function() {
            $('#datatables').DataTable();
        });

        //sweetalert berhasil
        $(function() {
            <?php if (session()->has('success')) { ?>
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "success",
                    title: "<?= $_SESSION['success'] ?>"
                });
            <?php } ?>
        })

        //sweetalert konfirmasi hapus
        document.addEventListener("DOMContentLoaded", function() {
            // Menangkap semua tombol delete
            const deleteButtons = document.querySelectorAll(".delete-btn");

            deleteButtons.forEach(button => {
                button.addEventListener("click", function(event) {
                    event.preventDefault(); // Mencegah aksi default link

                    const id = this.getAttribute("data-id"); // Mengambil ID dari data-id

                    Swal.fire({
                        title: "Apakah Anda yakin?",
                        text: "Data akan dihapus secara permanen!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, hapus!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "<?= base_url('admin/jabatan/delete/') ?>" + id;
                        }
                    });
                });
            });
        });
    </script>
    <?= $this->renderSection('scripts') ?>


</body>

</html>