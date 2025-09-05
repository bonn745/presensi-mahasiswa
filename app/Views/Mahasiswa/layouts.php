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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- ========== Datatables ========= -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <!-- ========== Tabler Icon ========= -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.28.1/tabler-icons.min.css" integrity="sha512-UuL1Le1IzormILxFr3ki91VGuPYjsKQkRFUvSrEuwdVCvYt6a1X73cJ8sWb/1E726+rfDRexUn528XRdqrSAOw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- ========== leaflet CSS ========= -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" />

    <?= $this->renderSection('styles') ?>

    <!-- ========== leaflet js ========= -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
</head>

<body>
    <!-- ======== Preloader =========== -->
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <!-- ======== Preloader =========== -->

    <!-- ======== sidebar-nav start =========== -->
    <aside class="sidebar-nav-wrapper">
        <div class="navbar-logo">
            <a href="home">
                <img style="width: 90%;" src="https://bunghatta.ac.id/v.5/images/logo/logo3.png" alt="logo" />
            </a>
        </div>
        <nav class="sidebar-nav ">
            <ul>
                <li class="nav-item mb-2">
                    <a href="<?= base_url('mahasiswa/home') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-home">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                            <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                        </svg>
                        <span class="text">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="<?= base_url('mahasiswa/rekap_presensi') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clipboard-data">
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
                </li>
                <li class="nav-item mb-2">
                    <a href="<?= base_url('mahasiswa/ketidakhadiran') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-check">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                            <path d="M15 19l2 2l4 -4" />
                        </svg>
                        <span class="text">Permohonan Izin</span>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="<?= url_to('mahasiswa.matkul') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-check">
                            <path d="M2.49999 3.33333C2.03976 3.33333 1.66666 3.70643 1.66666 4.16666V7.49999C1.66666 7.96023 2.03976 8.33333 2.49999 8.33333H5.83332C6.29356 8.33333 6.66666 7.96023 6.66666 7.49999V4.16666C6.66666 3.70643 6.29356 3.33333 5.83332 3.33333H2.49999Z"></path>
                            <path d="M2.49999 11.6667C2.03976 11.6667 1.66666 12.0398 1.66666 12.5V15.8333C1.66666 16.2936 2.03976 16.6667 2.49999 16.6667H5.83332C6.29356 16.6667 6.66666 16.2936 6.66666 15.8333V12.5C6.66666 12.0398 6.29356 11.6667 5.83332 11.6667H2.49999Z"></path>
                            <path d="M8.33334 4.16667C8.33334 3.8215 8.61318 3.54167 8.95834 3.54167H17.7083C18.0535 3.54167 18.3333 3.8215 18.3333 4.16667C18.3333 4.51185 18.0535 4.79167 17.7083 4.79167H8.95834C8.61318 4.79167 8.33334 4.51185 8.33334 4.16667Z"></path>
                            <path d="M8.33334 7.5C8.33334 7.15483 8.61318 6.875 8.95834 6.875H14.7917C15.1368 6.875 15.4167 7.15483 15.4167 7.5C15.4167 7.84517 15.1368 8.125 14.7917 8.125H8.95834C8.61318 8.125 8.33334 7.84517 8.33334 7.5Z"></path>
                            <path d="M8.95834 11.875C8.61318 11.875 8.33334 12.1548 8.33334 12.5C8.33334 12.8452 8.61318 13.125 8.95834 13.125H17.7083C18.0535 13.125 18.3333 12.8452 18.3333 12.5C18.3333 12.1548 18.0535 11.875 17.7083 11.875H8.95834Z"></path>
                            <path d="M8.95834 15.2083C8.61318 15.2083 8.33334 15.4882 8.33334 15.8333C8.33334 16.1785 8.61318 16.4583 8.95834 16.4583H14.7917C15.1368 16.4583 15.4167 16.1785 15.4167 15.8333C15.4167 15.4882 15.1368 15.2083 14.7917 15.2083H8.95834Z"></path>
                        </svg>
                        <span class="text">Mata Kuliah</span>
                    </a>
                </li>
    </aside>
    <div class="overlay"></div>
    <!-- ======== sidebar-nav end =========== -->

    <!-- ======== main-wrapper start =========== -->
    <main class="main-wrapper">
        <!-- ========== header start ========== -->
        <header class="header">
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
                                $username = session()->get('nama_mahasiswa');
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


                            <style>
                                /* Profile Section */
                                .profile-box {
                                    position: relative;
                                }

                                .dropdown-toggle {
                                    cursor: pointer;
                                    transition: all 0.3s ease;
                                }

                                .initial-circle {
                                    width: 40px;
                                    height: 40px;
                                    border-radius: 50%;
                                    background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
                                    color: white;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    font-weight: bold;
                                    font-size: 1.125rem;
                                    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                                }

                                .profile-details h6 {
                                    font-weight: 600;
                                    margin-bottom: 2px;
                                }

                                .profile-details p {
                                    font-size: 0.8rem;
                                    color: #64748b;
                                }

                                .dropdown-menu {
                                    border: none;
                                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                                    border-radius: 12px;
                                    overflow: hidden;
                                    padding: 0;
                                }

                                .dropdown-menu li a {
                                    padding: 0.75rem 1.5rem;
                                    transition: all 0.2s ease;
                                    color: #334155;
                                }

                                .dropdown-menu li a:hover {
                                    background: #f1f5f9;
                                    padding-left: 1.75rem;
                                }

                                .dropdown-menu li a i {
                                    width: 20px;
                                    text-align: center;
                                    margin-right: 10px;
                                }
                            </style>

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

    <!-- Jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?= $this->renderSection('scripts') ?>

    <script>
        //datatables
        $(document).ready(function() {
            $('#datatables').DataTable();
        });
    </script>

    <?php if (session()->has('gagal')) : ?>
        <script>
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "<?= session('gagal') ?>"
            });
        </script>
    <?php endif; ?>

    <?php if (session()->has('sukses')) : ?>
        <script>
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: "<?= session('sukses') ?>"
            });
        </script>
    <?php endif; ?>


</body>

</html>