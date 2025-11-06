<?php
// sekarang posisi ke finance buat menu finance nya untuk buat generate invoice setelah cek dokumen customer sudah lengkap


// baru abis itu buat setiap alur kasih validasi approval, sales, approval noc, finance
  // Ambil data sesi
  $session_role = $this->session->userdata('role');
  $session_akses_menu = (array)$this->session->userdata('akses_menu');

  // Jika role admin, otomatis bisa akses semua menu
  if ($session_role == 'admin') {
    $session_akses_menu = [
      'approval', 'tagihan', 'customer', 'tiketing', 'spk',
      'paket', 'warehouse', 'laporan', 'employee', 'pengaturan', 'pengajuan','request',
      'finance', 'berlangganan', 'survei', 'teknisi', 'jabatan'
    ];
  }

  // Ambil segment URI untuk menentukan menu aktif
  $segment1 = $this->uri->segment(1); // controller
  $segment2 = $this->uri->segment(2); // method

  // Fungsi helper untuk menentukan class 'active' pada parent menu
  function get_parent_active_class($current_segment1, $segment1, $segment2 = null) {
      $segments = (array)$segment1;
      if ($segment2) {
          $segments = array_merge($segments, (array)$segment2);
      }
      if (in_array($current_segment1, $segments)) {
          return 'active';
      }
      return '';
  }
?>

<!-- ========== Left Sidebar ========== -->
<div class="main-menu">
    <!-- Brand Logo (Dibiarkan seperti kode Aplikasi 2) -->
    <div class="logo-box">
        <a href="index.html" class="logo-light">
            <!-- logo sidebar -->
            <!-- <img src="assets/images/logo-light.png" alt="logo" class="logo-lg" height="28"> -->
            <img src="assets/dcn1.png" alt="logo" class="logo-lg" height="48">

            <img src="assets/dcn1.png" alt="small logo" class="logo-sm" height="28">
        </a>
        <a href="index.html" class="logo-dark">
            <img src="assets/dcn1.png" alt="dark logo" class="logo-lg" height="28">
            <img src="assets/dcn1.png" alt="small logo" class="logo-sm" height="28">
        </a>
    </div>

    <!--- Menu -->
    <div data-simplebar>
        <ul class="app-menu">

            <li class="menu-title">Menu</li>

            <!-- Dashboard -->
            <li class="menu-item <?= ($segment1 == 'dashboard') ? 'active' : '' ?>">
                <a href="<?= base_url('dashboard'); ?>" class="menu-link waves-effect waves-light">
                    <span class="menu-icon"><i class="fas fa-tachometer-alt"></i></span>
                    <span class="menu-text"> Dashboard </span>
                </a>
            </li>

            <!-- CUSTOMER -->
            <?php
            $is_customer_active = ($segment1 == 'customer');
            if (in_array('customer', $session_akses_menu)): ?>
            <li class="menu-item <?= $is_customer_active ? 'active' : '' ?>">
                <a href="#customer_menu" data-bs-toggle="collapse" class="menu-link waves-effect waves-light">
                    <span class="menu-icon"><i class="fas fa-user-friends"></i></span>
                    <span class="menu-text"> Customer </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse <?= $is_customer_active ? 'show' : '' ?>" id="customer_menu">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a href="<?= base_url('customer'); ?>" class="menu-link <?= ($segment1 == 'customer' && $segment2 == '') ? 'active' : '' ?>">
                                <span class="menu-text">Daftar Customer</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="<?= base_url('customer/create'); ?>" class="menu-link <?= ($segment1 == 'customer' && $segment2 == 'create') ? 'active' : '' ?>">
                                <span class="menu-text">Tambah Customer</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <!-- PAKET -->
            <?php
            $is_paket_active = ($segment1 == 'paket');
            if (in_array('paket', $session_akses_menu)): ?>
            <li class="menu-item <?= $is_paket_active ? 'active' : '' ?>">
                <a href="#paket_menu" data-bs-toggle="collapse" class="menu-link waves-effect waves-light">
                    <span class="menu-icon"><i class="fas fa-box"></i></span>
                    <span class="menu-text"> Paket Layanan </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse <?= $is_paket_active ? 'show' : '' ?>" id="paket_menu">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a href="<?= base_url('paket'); ?>" class="menu-link <?= ($segment1 == 'paket' && $segment2 == '') ? 'active' : '' ?>">
                                <span class="menu-text">Daftar Paket</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="<?= base_url('paket/create'); ?>" class="menu-link <?= ($segment1 == 'paket' && $segment2 == 'create') ? 'active' : '' ?>">
                                <span class="menu-text">Tambah Paket</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <!-- BERLANGGANAN -->
            <?php
            $is_berlangganan_active = ($segment1 == 'berlangganan' || $segment1 == 'subscriptions');
            if (in_array('berlangganan', $session_akses_menu)): ?>
            <li class="menu-item <?= $is_berlangganan_active ? 'active' : '' ?>">
                <a href="#berlangganan_menu" data-bs-toggle="collapse" class="menu-link waves-effect waves-light">
                    <span class="menu-icon"><i class="fas fa-contract"></i></span>
                    <span class="menu-text"> Berlangganan </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse <?= $is_berlangganan_active ? 'show' : '' ?>" id="berlangganan_menu">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a href="<?= base_url('berlangganan'); ?>" class="menu-link <?= ($segment1 == 'berlangganan' && $segment2 == '') ? 'active' : '' ?>">
                                <span class="menu-text">Daftar Berlangganan</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="<?= base_url('subscriptions'); ?>" class="menu-link <?= ($segment1 == 'subscriptions') ? 'active' : '' ?>">
                                <span class="menu-text">Management Subscription</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="<?= base_url('subscriptions/history'); ?>" class="menu-link <?= ($segment1 == 'subscriptions' && $segment2 == 'history') ? 'active' : '' ?>">
                                <span class="menu-text">Riwayat Langganan</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <!-- APPROVAL -->
            <?php
            $is_approval_active = ($segment1 == 'approval_langganan');
            if (in_array('approval', $session_akses_menu)): ?>
            <li class="menu-item <?= $is_approval_active ? 'active' : '' ?>">
                <a href="<?= base_url('approval_langganan'); ?>" class="menu-link waves-effect waves-light">
                    <span class="menu-icon"><i class="fas fa-check-circle"></i></span>
                    <span class="menu-text"> Approval </span>
                </a>
            </li>
            <?php endif; ?>

            <!-- PENGAJUAN -->
            <?php $is_pengajuan_active = ($segment1 == 'request'); // if ($session_role == 'sales' || $session_role == 'admin' || in_array('request', $session_akses_menu)): ?>
                       <li class="menu-item <?= $is_pengajuan_active ? 'active' : '' ?>">
                <a href="#pengajuan_menu" data-bs-toggle="collapse" class="menu-link waves-effect waves-light">
                    <span class="menu-icon"><i class="fas fa-paper-plane"></i></span>
                    <span class="menu-text"> List Pemasangan</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse <?= $is_pengajuan_active ? 'show' : '' ?>" id="pengajuan_menu">
                    <ul class="sub-menu">
                        <?php if ($session_role === "sales"): ?>
                        <li class="menu-item">
                            <a href="<?= base_url('request'); ?>" class="menu-link <?= ($segment1 == 'request' && $segment2 == '') ? 'active' : '' ?>">
                                <span class="menu-text">Ajukan Pemasangan</span>
                            </a>
                        </li>
                        <?php endif ?>

                        <?php if ($session_role === "sales" || $session_role === "surveyor"): ?>
                        <li class="menu-item">
                            <a href="<?= base_url('request/survei_index'); ?>" class="menu-link <?= ($segment1 == 'request' && $segment2 == 'survei_index') ? 'active' : '' ?>">
                                <span class="menu-text">Request Survei</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="<?= base_url('request/survei_dokumen'); ?>" class="menu-link <?= ($segment1 == 'request' && $segment2 == 'survei_dokumen') ? 'active' : '' ?>">
                                <span class="menu-text">Dokumen Survei</span>
                            </a>
                        </li>
                        <?php endif ?>
                        <?php if ($session_role === "noc" || $session_role === "admin"): ?>
                        <li class="menu-item">
                            <a href="<?= base_url('request/instalasi'); ?>" class="menu-link <?= ($segment1 == 'request' && $segment2 == 'instalasi') ? 'active' : '' ?>">
                                <span class="menu-text">Request Instalasi</span>
                            </a>
                        </li>
                        <?php endif ?>
                        <?php if ($session_role == "finance"): ?>
                        <li class="menu-item">
                            <a href="<?= base_url('request'); ?>" class="menu-link <?= ($segment1 == 'request' && $segment2 == '') ? 'active' : '' ?>">
                                <span class="menu-text">Pemasangan</span>
                            </a>
                        </li>
                        <?php endif ?>
                        <?php if ($session_role === "sales" || $session_role === "finance"): ?>
                        <li class="menu-item">
                            <a href="<?= base_url('request/dokumen_customer'); ?>" class="menu-link <?= ($segment1 == 'request' && $segment2 == 'dokumen_customer') ? 'active' : '' ?>">
                                <span class="menu-text">Dokumen Customer</span>
                            </a>
                        </li>
                        <?php endif ?>

                        <?php if ($session_role === "customer"): ?>
                        <li class="menu-item">
                            <a href="<?= base_url('tagihan/bulanan'); ?>" class="menu-link <?= ($segment1 == 'request' && $segment2 == 'dokumen_customer') ? 'active' : '' ?>">
                                <span class="menu-text">Tagihan Bulanan</span>
                            </a>
                        </li>
                        <?php endif ?>

                        
                    </ul>
                </div>
            </li>

            <!-- <?php //endif; ?> -->

            <!-- FINANCE -->
            <?php
            $is_finance_active = ($segment1 == 'finance' || $segment1 == 'tagihan' || $segment1 == 'pembayaran');
            if (in_array('finance', $session_akses_menu) || in_array('tagihan', $session_akses_menu)): ?>
            <li class="menu-item <?= $is_finance_active ? 'active' : '' ?>">
                <a href="#finance_menu" data-bs-toggle="collapse" class="menu-link waves-effect waves-light">
                    <span class="menu-icon"><i class="fas fa-money-bill-wave"></i></span>
                    <span class="menu-text"> Finance </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse <?= $is_finance_active ? 'show' : '' ?>" id="finance_menu">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a href="<?= base_url('finance/invoice'); ?>" class="menu-link <?= ($segment1 == 'finance' && $segment2 == 'invoice') ? 'active' : '' ?>">
                                <span class="menu-text">Generate Invoice</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="<?= base_url('tagihan/bulanan'); ?>" class="menu-link <?= ($segment1 == 'tagihan' && $segment2 == 'bulanan') ? 'active' : '' ?>">
                                <span class="menu-text">Tagihan Bulanan</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="<?= base_url('pembayaran'); ?>" class="menu-link <?= ($segment1 == 'pembayaran') ? 'active' : '' ?>">
                                <span class="menu-text">Pembayaran</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <!-- SURVEI -->
            <?php
            $is_survei_active = ($segment1 == 'survei');
            if (in_array('survei', $session_akses_menu) || $session_role == 'surveyor' || $session_role == 'admin'): ?>
            <li class="menu-item <?= $is_survei_active ? 'active' : '' ?>">
                <a href="#survei_menu" data-bs-toggle="collapse" class="menu-link waves-effect waves-light">
                    <span class="menu-icon"><i class="fas fa-search-location"></i></span>
                    <span class="menu-text"> Survei </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse <?= $is_survei_active ? 'show' : '' ?>" id="survei_menu">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a href="<?= base_url('survei'); ?>" class="menu-link <?= ($segment1 == 'survei' && $segment2 == '') ? 'active' : '' ?>">
                                <span class="menu-text">Request Survei</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="<?= base_url('survei/dokumen_detail'); ?>" class="menu-link <?= ($segment1 == 'survei' && $segment2 == 'dokumen_detail') ? 'active' : '' ?>">
                                <span class="menu-text">Dokumen Survei</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <!-- SPK -->
            <?php
            $is_spk_active = ($segment1 == 'spk');
            if (in_array('spk', $session_akses_menu) || $session_role == 'noc' || $session_role == 'teknisi' || $session_role == 'admin'): ?>
            <li class="menu-item <?= $is_spk_active ? 'active' : '' ?>">
                <a href="#spk_menu" data-bs-toggle="collapse" class="menu-link waves-effect waves-light">
                    <span class="menu-icon"><i class="fas fa-clipboard-list"></i></span>
                    <span class="menu-text"> SPK </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse <?= $is_spk_active ? 'show' : '' ?>" id="spk_menu">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a href="<?= base_url('spk'); ?>" class="menu-link <?= ($segment1 == 'spk' && $segment2 == '') ? 'active' : '' ?>">
                                <span class="menu-text">Daftar SPK</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="<?= base_url('spk/create'); ?>" class="menu-link <?= ($segment1 == 'spk' && $segment2 == 'create') ? 'active' : '' ?>">
                                <span class="menu-text">Tambah SPK</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <!-- WAREHOUSE -->
            <?php
            $is_warehouse_active = ($segment1 == 'warehouse');
            if (in_array('warehouse', $session_akses_menu)): ?>
            <li class="menu-item <?= $is_warehouse_active ? 'active' : '' ?>">
                <a href="#warehouse_menu" data-bs-toggle="collapse" class="menu-link waves-effect waves-light">
                    <span class="menu-icon"><i class="fas fa-warehouse"></i></span>
                    <span class="menu-text"> Warehouse </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse <?= $is_warehouse_active ? 'show' : '' ?>" id="warehouse_menu">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a href="<?= base_url('warehouse/masuk'); ?>" class="menu-link <?= ($segment1 == 'warehouse' && $segment2 == 'masuk') ? 'active' : '' ?>">
                                <span class="menu-text">Barang Masuk</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="<?= base_url('warehouse/keluar'); ?>" class="menu-link <?= ($segment1 == 'warehouse' && $segment2 == 'keluar') ? 'active' : '' ?>">
                                <span class="menu-text">Barang Keluar</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="<?= base_url('warehouse/rusak'); ?>" class="menu-link <?= ($segment1 == 'warehouse' && $segment2 == 'rusak') ? 'active' : '' ?>">
                                <span class="menu-text">Barang Rusak</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <!-- TEKNISI -->
            <?php
            $is_teknisi_active = ($segment1 == 'teknisi');
            if (in_array('teknisi', $session_akses_menu)): ?>
            <li class="menu-item <?= $is_teknisi_active ? 'active' : '' ?>">
                <a href="#teknisi_menu" data-bs-toggle="collapse" class="menu-link waves-effect waves-light">
                    <span class="menu-icon"><i class="fas fa-tools"></i></span>
                    <span class="menu-text"> Teknisi </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse <?= $is_teknisi_active ? 'show' : '' ?>" id="teknisi_menu">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a href="<?= base_url('teknisi'); ?>" class="menu-link <?= ($segment1 == 'teknisi' && $segment2 == '') ? 'active' : '' ?>">
                                <span class="menu-text">Daftar Teknisi</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="<?= base_url('teknisi/create'); ?>" class="menu-link <?= ($segment1 == 'teknisi' && $segment2 == 'create') ? 'active' : '' ?>">
                                <span class="menu-text">Tambah Teknisi</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <!-- JABATAN -->
            <?php
            $is_jabatan_active = ($segment1 == 'jabatan');
            if (in_array('jabatan', $session_akses_menu)): ?>
            <li class="menu-item <?= $is_jabatan_active ? 'active' : '' ?>">
                <a href="<?= base_url('jabatan'); ?>" class="menu-link waves-effect waves-light">
                    <span class="menu-icon"><i class="fas fa-user-tag"></i></span>
                    <span class="menu-text"> Jabatan </span>
                </a>
            </li>
            <?php endif; ?>

            <!-- EMPLOYEE -->
            <?php
            $is_employee_active = ($segment1 == 'employee');
            if (in_array('employee', $session_akses_menu)): ?>
            <li class="menu-item <?= $is_employee_active ? 'active' : '' ?>">
                <a href="#employee_menu" data-bs-toggle="collapse" class="menu-link waves-effect waves-light">
                    <span class="menu-icon"><i class="fas fa-users"></i></span>
                    <span class="menu-text"> Employee </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse <?= $is_employee_active ? 'show' : '' ?>" id="employee_menu">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a href="<?= base_url('employee'); ?>" class="menu-link <?= ($segment1 == 'employee' && $segment2 == '') ? 'active' : '' ?>">
                                <span class="menu-text">Daftar Employee</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="<?= base_url('employee/add'); ?>" class="menu-link <?= ($segment1 == 'employee' && $segment2 == 'add') ? 'active' : '' ?>">
                                <span class="menu-text">Tambah Employee</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>
 
  
            <!-- Pengaturan -->
            <?php
            $is_pengaturan_active = ($segment1 == 'users' || $segment1 == 'pengaturan');
            if (in_array('pengaturan', $session_akses_menu)): ?>
            <li class="menu-item <?= $is_pengaturan_active ? 'active' : '' ?>">
                <a href="#pengaturan_menu" data-bs-toggle="collapse" class="menu-link waves-effect waves-light">
                    <span class="menu-icon"><i class="fas fa-cog"></i></span>
                    <span class="menu-text"> Pengaturan </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse <?= $is_pengaturan_active ? 'show' : '' ?>" id="pengaturan_menu">
                    <ul class="sub-menu">
                        <li class="menu-item"><a href="<?= base_url('users'); ?>" class="menu-link <?= ($segment1 == 'users') ? 'active' : '' ?>"><span class="menu-text">Manajemen Akun</span></a></li>
                        <li class="menu-item"><a href="#" class="menu-link <?= ($segment2 == 'kontak') ? 'active' : '' ?>"><span class="menu-text">Kontak Perusahaan</span></a></li>
                        <li class="menu-item"><a href="#" class="menu-link <?= ($segment2 == 'visi_misi') ? 'active' : '' ?>"><span class="menu-text">Visi & Misi</span></a></li>
                        <li class="menu-item"><a href="#" class="menu-link <?= ($segment2 == 'tentang') ? 'active' : '' ?>"><span class="menu-text">Tentang Perusahaan</span></a></li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

        </ul>
    </div>
</div>
<!-- End Left Sidebar -->
<!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="page-content">

            <!-- ========== Topbar Start ========== -->
            <div class="navbar-custom">
                <div class="topbar">
                    <div class="topbar-menu d-flex align-items-center gap-lg-2 gap-1">

                        <!-- Brand Logo -->
                        <div class="logo-box">
                            <!-- Brand Logo Light -->
                            <a href="index.html" class="logo-light">
                                <img src="assets/images/logo-light.png" alt="logo" class="logo-lg" height="22">
                                <img src="assets/images/logo-sm.png" alt="small logo" class="logo-sm" height="22">
                            </a>

                            <!-- Brand Logo Dark -->
                            <a href="index.html" class="logo-dark">
                                <img src="assets/images/logo-dark.png" alt="dark logo" class="logo-lg" height="22">
                                <img src="assets/images/logo-sm.png" alt="small logo" class="logo-sm" height="22">
                            </a>
                        </div>

                        <!-- Sidebar Menu Toggle Button -->
                        <button class="button-toggle-menu">
                            <i class="mdi mdi-menu"></i>
                        </button>
                    </div>

                    <ul class="topbar-menu d-flex align-items-center gap-4">

                        <li class="d-none d-md-inline-block">
                            <a class="nav-link" href="" data-bs-toggle="fullscreen">
                                <i class="mdi mdi-fullscreen font-size-24"></i>
                            </a>
                        </li>

                      
                        <li class="nav-link" id="theme-mode">
                            <i class="bx bx-moon font-size-24"></i>
                        </li>

                        <li class="dropdown">
                            <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <img src="assets/images/users/avatar-4.jpg" alt="user-image" class="rounded-circle">
                                <span class="ms-1 d-none d-md-inline-block">
                                    <?php echo $this->session->userdata('nama'); ?> <i class="mdi mdi-chevron-down"></i>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                                <!-- item-->
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">Menu</h6>
                                </div>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <i class="fe-user"></i>
                                    <span>Akun</span>
                                </a>
 
                                <div class="dropdown-divider"></div>

                                <!-- item-->
                                <a href="<?php echo base_url('auth/logout'); ?>" class="dropdown-item notify-item">
                                    <i class="fe-log-out"></i>
                                    <span>Logout</span>
                                </a>

                            </div>
                        </li>
          
                    </ul>
                </div>
            </div>
            <!-- ========== Topbar End ========== -->