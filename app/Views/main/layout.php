<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title; ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url() ?>/plugins/fontawesome-free/css/all.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url() ?>/dist/css/adminlte.min.css">
    <!-- jQuery -->
    <script src="<?= base_url() ?>/plugins/jquery/jquery.min.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>/plugins/sweetalert2/sweetalert2.min.css">
    <script src="<?= base_url() ?>/plugins/sweetalert2/sweetalert2.all.min.js"></script>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url(); ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url(); ?>/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <h6 class="text-sm font-weight-bold text-gray mb-1">
                        <?= $this->renderSection('judul'); ?>
                    </h6>
                </li>
            </ul>

            <!-- Right navbar links -->
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class=" main-sidebar sidebar-light-cyan elevation-4" style="background-color: #e2e9ff;">
            <!-- Brand Logo -->
            <a href="#" class=" brand-link">
                <span class="brand-text font-weight-light">Sistem Inventory Lab TKJ </span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="<?= base_url() ?>/dist/img/5856.png" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <?= session()->namauser; ?>
                    </div>
                </div>


                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="<?= base_url('/main/index'); ?>" class="nav-link">
                                <i class="nav-icon fas fa-fw fa-tachometer-alt text-primary"></i>
                                <p class="text">Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-header">Master</li>
                        <li class="nav-item">
                            <a href="<?= base_url('/kategori/index'); ?>" class="nav-link">
                                <i class="nav-icon fa fa-list text-warning"></i>
                                <p class="text">Kategori</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('/satuan/index'); ?>" class="nav-link">
                                <i class="nav-icon fa fa-angle-double-right text-success"></i>
                                <p class="text">Satuan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('/barang/index'); ?>" class="nav-link">
                                <i class="nav-icon fa fa-tasks text-info"></i>
                                <p class="text">Barang</p>
                            </a>
                        </li>
                        <li class="nav-header">Transaksi</li>
                        <li class="nav-item">
                            <a href="<?= base_url('/barangpinjam/data'); ?>" class="nav-link">
                                <i class="nav-icon fa fa-arrow-circle-up text-info"></i>
                                <p class="text">Peminjaman Barang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('/barangmasuk/data'); ?>" class="nav-link">
                                <i class="nav-icon fa fa-arrow-circle-down text-primary"></i>
                                <p class="text">Barang Masuk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('/inventarisRuang/data'); ?>" class="nav-link">
                                <i class="nav-icon fa fa-arrow-circle-up text-info"></i>
                                <p class="text">Daftar Inventaris Ruang</p>
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                                <a href="<?= base_url('/barangkeluar/index'); ?>" class="nav-link">
                                    <i class="nav-icon fa fa-arrow-circle-up text-info"></i>
                                    <p class="text">Barang Keluar</p>
                                </a>
                            </li> -->
                        <li class="nav-item">
                            <a href="<?= base_url('/laporan/index'); ?>" class="nav-link">
                                <i class="nav-icon fa fa-file text-warning"></i>
                                <p class="text">Laporan Barang</p>
                            </a>
                        </li>
                        <li class="nav-header">Utility</li>
                        <li class="nav-item">
                            <a href="<?= base_url('/users/gantiPassword'); ?>" class="nav-link">
                                <i class="nav-icon fa fa-lock text-light-grey"></i>
                                <p class="text">Ganti Password</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('/login/logout'); ?>" class="nav-link">
                                <i class="nav-icon fa fa-sign-out-alt text-primary"></i>
                                <p class="text">Logout</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">

                <!-- Default box -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?= $this->renderSection('subjudul'); ?> </h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <?= $this->renderSection('isi'); ?>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">

                    </div>
                    <!-- /.card-footer-->
                </div>
                <!-- /.card -->

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <!-- <b>Version</b> 3.2.0 -->
            </div>

        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->


    <!-- Bootstrap 4 -->
    <script src="<?= base_url() ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url() ?>/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?= base_url() ?>/dist/js/demo.js"></script>
    <script src="<?= base_url() ?>/dist/js/custom.js"></script>


</body>

</html>