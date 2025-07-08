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
    <div class="overlay"></div>
    <!-- ======== sidebar-nav end =========== -->

    <div class="menu-toggle-btn mr-15 header d-none">
        <button id="menu-toggle" class="main-btn primary-btn btn-hover">
            <i class="lni lni-chevron-left me-2"></i> Menu
        </button>
    </div>

    <!-- ======== main-wrapper start =========== -->
    <main class="main-wrapper" style="margin-left: 0px;">
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
                <div class="d-flex justify-content-center">
                    <div class="row">
                        <?php if (session()->getFlashdata('pesan')) : ?>
                            <div class="alert alert-warning mb-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <span><?= session()->getFlashdata('pesan') ?></span>
                                </div>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-center">
                    <form action="<?= url_to('mahasiswa.lengkapi.store') ?>" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?= $mahasiswa['id'] ?>">
                        <!-- Jenis Kelamin -->
                        <div class="input-style-1">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select id="jenis_kelamin" class="form-control<?= isset(session()->getFlashdata('errors')['jenis_kelamin']) ? ' is-invalid' : '' ?>" name="jenis_kelamin">
                                <option value="" disabled <?= isset(session()->getFlashdata('data')['jenis_kelamin']) ? '' : 'selected' ?>>--Pilih Jenis Kelamin--</option>
                                <option value="Laki-laki" <?= (isset(session()->getFlashdata('data')['jenis_kelamin']) ? session()->getFlashdata('data')['jenis_kelamin'] : $mahasiswa['jenis_kelamin']) == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="Perempuan" <?= (isset(session()->getFlashdata('data')['jenis_kelamin']) ? session()->getFlashdata('data')['jenis_kelamin'] : $mahasiswa['jenis_kelamin'] ) == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                            <div class="invalid-feedback"><?= session()->getFlashdata('errors')['jenis_kelamin'] ?? '' ?></div>
                        </div>
                        <!-- Alamat -->
                        <div class="input-style-1">
                            <label for="alamat">Alamat</label>
                            <textarea id="alamat" class="form-control<?= isset(session()->getFlashdata('errors')['alamat']) ? ' is-invalid' : '' ?>"
                                name="alamat" placeholder="Alamat Mahasiswa" rows="4"><?= isset(session()->getFlashdata('data')['alamat']) ? session()->getFlashdata('data')['alamat'] : $mahasiswa['alamat']  ?></textarea>
                            <div class="invalid-feedback"><?= session()->getFlashdata('errors')['alamat'] ?? '' ?></div>
                        </div>
                        <!-- No Handphone -->
                        <div class="input-style-1">
                            <label for="no_handphone">No Handphone</label>
                            <input id="no_handphone" type="text" class="form-control<?= isset(session()->getFlashdata('errors')['no_handphone']) ? ' is-invalid' : '' ?>"
                                name="no_handphone" placeholder="No Handphone"
                                value="<?= isset(session()->getFlashdata('data')['no_handphone']) ? session()->getFlashdata('data')['no_handphone'] : $mahasiswa['no_handphone']  ?>" onblur="formatPhoneNumber(this)" />
                            <div class="invalid-feedback"><?= session()->getFlashdata('errors')['no_handphone'] ?? '' ?></div>
                        </div>
                        <!-- Semester -->
                        <div class="input-style-1">
                            <label for="semester">Semester</label>
                            <select id="semester" name="semester" class="form-control<?= isset(session()->getFlashdata('errors')['semester']) ? ' is-invalid' : '' ?>">
                                <option value="" disabled <?= isset(session()->getFlashdata('data')['semester']) ? '' : 'selected' ?>>-- Pilih Semester --</option>
                                <?php for ($i = 1; $i <= 8; $i++): ?>
                                    <option value="Semester <?= $i ?>" <?= (isset(session()->getFlashdata('data')['semester']) ? session()->getFlashdata('data')['semester'] : $mahasiswa['semester'] ) == "Semester $i" ? 'selected' : '' ?>>Semester <?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                            <div class="invalid-feedback"><?= session()->getFlashdata('errors')['semester'] ?? '' ?></div>
                        </div>
                        <!-- Jurusan -->
                        <div class="input-style-1">
                            <label for="jurusan">Jurusan</label>
                            <input id="jurusan" type="text" class="form-control<?= isset(session()->getFlashdata('errors')['jurusan']) ? ' is-invalid' : '' ?>"
                                name="jurusan" placeholder="Jurusan"
                                value="<?= isset(session()->getFlashdata('data')['jurusan']) ? session()->getFlashdata('data')['jurusan'] : $mahasiswa['jurusan']  ?>" />
                            <div class="invalid-feedback"><?= session()->getFlashdata('errors')['jurusan'] ?? '' ?></div>
                        </div>
                        <!-- Foto -->
                        <div class="input-style-1 mb-4">
                            <label for="foto">Foto</label>
                            <input type="file" id="foto" class="form-control<?= isset(session()->getFlashdata('errors')['foto']) ? ' is-invalid' : '' ?>" name="foto" />
                            <div class="invalid-feedback"><?= session()->getFlashdata('errors')['foto'] ?? '' ?></div>
                        </div>
                        <hr>
                        <div class="text-dark w-100 text-center">Data Orang Tua</div>
                        <!-- Nama Orang Tua -->
                        <div class="input-style-1">
                            <label for="nama_ortu">Nama Orang Tua</label>
                            <input id="nama_ortu" type="text" class="form-control<?= isset(session()->getFlashdata('errors')['nama_ortu']) ? ' is-invalid' : '' ?>"
                                name="nama_ortu" placeholder="Masukkan Nama Lengkap"
                                value="<?= isset(session()->getFlashdata('data')['nama_ortu']) ? session()->getFlashdata('data')['nama_ortu'] : $mahasiswa['nama_ortu']  ?>" />
                            <div class="invalid-feedback"><?= session()->getFlashdata('errors')['nama_ortu'] ?? '' ?></div>
                        </div>
                        <!-- Jenis Kelamin Orang Tua -->
                        <div class="input-style-1">
                            <label for="jenis_kelamin_ortu">Jenis Kelamin</label>
                            <select id="jenis_kelamin_ortu" class="form-control<?= isset(session()->getFlashdata('errors')['jenis_kelamin_ortu']) ? ' is-invalid' : '' ?>" name="jenis_kelamin_ortu">
                                <option value="" disabled <?= isset(session()->getFlashdata('data')['jenis_kelamin_ortu']) ? '' : 'selected' ?>>--Jenis Kelamin--</option>
                                <option value="Laki-laki" <?= (isset(session()->getFlashdata('data')['jenis_kelamin']) ? session()->getFlashdata('data')['jenis_kelamin'] : $mahasiswa['jk_ortu']) == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="Perempuan" <?= (isset(session()->getFlashdata('data')['jenis_kelamin']) ? session()->getFlashdata('data')['jenis_kelamin'] : $mahasiswa['jk_ortu']) == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                            <div class="invalid-feedback"><?= session()->getFlashdata('errors')['jenis_kelamin_ortu'] ?? '' ?></div>
                        </div>
                        <!-- No WhatsApp Orang Tua -->
                        <div class="input-style-1">
                            <label for="no_whatsapp">No WhatsApp</label>
                            <input id="no_whatsapp" type="tel" class="form-control<?= isset(session()->getFlashdata('errors')['no_whatsapp']) ? ' is-invalid' : '' ?>"
                                name="no_whatsapp" placeholder="No WhatsApp"
                                value="<?= isset(session()->getFlashdata('data')['no_whatsapp']) ? session()->getFlashdata('data')['no_whatsapp'] : $mahasiswa['nohp_ortu']  ?>" onblur="formatPhoneNumber(this)" />
                            <div class="invalid-feedback"><?= session()->getFlashdata('errors')['no_whatsapp'] ?? '' ?></div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
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
</body>
<script src="<?= base_url('assets/js/mhs.js') ?>"></script>

</html>