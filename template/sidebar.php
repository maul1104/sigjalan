<?php 
error_reporting(0);
session_start();
include("../inc/config.php"); 

// Ambil ID pengguna dari session
$id = $_SESSION['id'];
// Query untuk mengambil data pengguna dari tbl_log
$query = "SELECT nama, foto FROM tbl_log WHERE id = '$id'";
$result = mysqli_query($conn, $query);

// Pastikan data pengguna ditemukan
if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $nama = $user['nama']; // Nama pengguna
    $foto = $user['foto']; // Foto pengguna (path file foto)

// Jika $foto hanya menyimpan nama file, tambahkan folder path 'assets/img/' di depan
if (!empty($foto)) {
  $foto_path = "../assets/img/" . $foto;
} else {
  $foto_path = "../assets/images/profile/default.jpg"; // Foto default jika tidak ada
}
} else {
    // Jika data tidak ditemukan, set nilai default
    $nama = "Guest";
    $foto = "../assets/images/profile/user-1.jpg"; // Foto default jika tidak ada
}
?>
<style>
    .profile-photo {
        width: 35px; /* Atur lebar */
        height: 35px; /* Atur tinggi sama dengan lebar */
        object-fit: cover; /* Menjaga rasio aspek */
        border-radius: 50%; /* Membuat gambar menjadi lingkaran */
    }
    .polygon-label {
  font-size: 12px;
  font-weight: bold;
  background-color: rgba(255, 255, 255, 0.8);
  padding: 2px 4px;
  border-radius: 4px;
}
</style>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
        <a href="./index.html" class="text-nowrap logo-img">
            <img src="../assets/images/logos/logo123.jpg" width="200" alt="" />
        </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <?php if($_SESSION['level'] == 'admin'){?>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Home</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?= $base_url ?>admin/dashboard.php" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Master Data</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?= $base_url ?>admin/jalan.php" aria-expanded="false">
                <span>
                  <i class="ti ti-file-description"></i>
                </span>
                <span class="hide-menu">Data Jalan</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?= $base_url ?>admin/pengajuan_masuk.php" aria-expanded="false">
                <span>
                  <i class="ti ti-alert-circle"></i>
                </span>
                <span class="hide-menu">Data Pengajuan</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?= $base_url ?>admin/riwayat_pengajuan.php" aria-expanded="false">
                <span>
                  <i class="ti ti-cards"></i>
                </span>
                <span class="hide-menu">Riwayat Pengaduan</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">User</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?= $base_url ?>admin/pengguna.php" aria-expanded="false">
                <span>
                  <i class="ti ti-users"></i>
                </span>
                <span class="hide-menu">Data Pengguna</span>
              </a>
            </li>
          </ul>
        </nav>
        <?php } else if($_SESSION['level'] == 'kabag'){?>
                  <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Home</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?= $base_url ?>kabag/dashboard.php" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Master Data</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?= $base_url ?>kabag/jalan.php" aria-expanded="false">
                <span>
                  <i class="ti ti-file-description"></i>
                </span>
                <span class="hide-menu">Data Jalan</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?= $base_url ?>kabag/riwayat_pengajuan.php" aria-expanded="false">
                <span>
                  <i class="ti ti-cards"></i>
                </span>
                <span class="hide-menu">Riwayat Pengajuan</span>
              </a>
            </li>
          </ul>
        </nav>
        <?php } ?>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->
    <div class="body-wrapper">
    <!-- Header Start -->
    <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item d-block d-xl-none">
                    <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
            </ul>
            <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                    <li class="nav-item dropdown">
                        <!-- Tampilkan foto berdasarkan data dari database -->
                        <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false" style="z-index: 1050;">
                        <img src="<?= $foto_path ?>" alt="Foto Profil" class="profile-photo">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                            <div class="message-body">
                                <!-- Tampilkan "Hi, Nama Pengguna" -->
                                <p class="mx-3 mt-2">Hi, <?= htmlspecialchars($nama) ?></p>
                                <a href="<?= $base_url ?>proses_log/logout.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Header End -->

