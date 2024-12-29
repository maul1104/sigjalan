<?php 
include('../inc/config.php');

if ($_SESSION['id'] == NULL) {
  header('Location: '.$base_url.'admin/login.php');
}
include('../template/header.php');
include('../template/sidebar.php');

error_reporting(0);
 
$id = $_SESSION['id'];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
              <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4">Data Jalan</h5>
                <!--<button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#tambahdata">
                    Tambah Data
                </button>-->
                <button type="button" class="btn btn-secondary mb-2" onclick="window.location.href='<?= $base_url?>cetak_pdf/cetak_pdf.php'">
                    Unduh Laporan
                </button>

                <?php if (isset($_SESSION['message'])) { ?>
                    <div class="alert alert-<?= $_SESSION['message_type']?> alert-dismissible fade show" role="alert">
                        <?= $_SESSION['message']?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php unset($_SESSION['message']); unset($_SESSION['message_type']); } ?>

                <div class="table-responsive">
                  <table id="dataTable" class="table text-nowrap mb-0 align-middle">
                    <thead class="text-dark fs-4">
                      <tr>
                        <th class="border-bottom-0">No</th>
                        <th class="border-bottom-0">Nama Ruas</th>
                        <th class="border-bottom-0">Kampung</th>
                        <th class="border-bottom-0">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT jalan.*, kampung.kampung AS kampung 
                        FROM jalan 
                        JOIN kampung ON jalan.kampung = kampung.id 
                        ORDER BY jalan.id DESC";
                        $result_tasks = mysqli_query($conn, $query);
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result_tasks)) { ?>
                        <tr>
                            <td class="border-bottom-0"><h6 class="fw-semibold mb-0"><?= $no++ ?></h6></td>
                            <td class="border-bottom-0"><h6 class="fw-semibold mb-1"><?= $row['nm_jalan'] ?></h6></td>
                            <td class="border-bottom-0"><p class="mb-0 fw-normal"><?= $row['kampung'] ?></p></td>
                            <td class="border-bottom-0">
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#detailModal<?= $row['id'] ?>">
                                        Detail
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>

<?php
$query = "SELECT jalan.*, kampung.kampung AS kampung 
FROM jalan 
JOIN kampung ON jalan.kampung = kampung.id 
ORDER BY jalan.id DESC";
$result_tasks = mysqli_query($conn, $query);
$no = 1;
while ($row = mysqli_fetch_assoc($result_tasks)) { ?>
 <!-- Modal Detail -->
 <div class="modal fade" id="detailModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="detailModalLabel<?= $row['id'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detailModalLabel<?= $row['id'] ?>">Detail Jalan: <?= $row['nm_jalan'] ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
          <div class="modal-body">
            <!-- Gunakan Grid Bootstrap untuk susunan lebih rapi -->
            <div class="container-fluid">
              <div class="row">
                <div class="col-md-6">
                                                      <!-- Informasi Jalan -->
                                                      <h6 class="fw-semibold">Informasi Jalan</h6>
                                                      <p><strong>Kampung: </strong><?= $row['kampung'] ?></p>
                                                      <p><strong>Panjang Ruas Jalan: </strong><?= $row['panjang'] ?> Kilometer</p>
                                                      <p><strong>Lebar Ruas Jalan: </strong><?= $row['lebar'] ?> meter</p>
                                                      <p><strong>Ruas Jalan Bagus: </strong><?= $row['jl_bagus'] ?> Kilometer</p>
                                                      <p><strong>Ruas Jalan Sedang: </strong><?= $row['jl_sedang'] ?> Kilometer</p>
                                                      <p><strong>Ruas Jalan Rusak: </strong><?= $row['jl_rusak'] ?> Kilometer</p>
                                                      <p><strong>Latitude: </strong><?= $row['lat'] ?></p>
                                                      <p><strong>Longitude: </strong><?= $row['lng'] ?></p>
                                                  </div>
                                                  <div class="col-md-6 text-center">
                                                      <!-- Foto Jalan -->
                                                      <h6 class="fw-semibold">Foto Jalan</h6>
                                                      <img src="../assets/images/<?= $row['foto'] ?>" alt="Foto Jalan" class="img-fluid img-thumbnail mb-3" style="max-width: 100%; height: auto; max-height: 300px; object-fit: cover;">
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
<?php } ?>

<?php include('../template/footer.php'); ?>
<script>
var map = null;
var marker = null;  // Variabel untuk menyimpan marker

// Event ketika modal terbuka sepenuhnya
document.getElementById('tambahdata').addEventListener('shown.bs.modal', function () {
  console.log('Modal terbuka...');

  var mapElement = document.getElementById('mapid');
  if (mapElement) {
    console.log('Elemen mapid ditemukan');
    
    // Hapus peta sebelumnya jika ada
    if (map !== null) {
      map.remove();
    }

    // Inisialisasi ulang peta setelah modal benar-benar terbuka
    setTimeout(function() {
      map = L.map('mapid').setView([-8.4944, 140.4018], 13);

      // Menggunakan Google Maps Hybrid Tile Layer
      L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
        attribution: '&copy; <a href="https://maps.google.com">Google Maps</a> contributors'
      }).addTo(map);

      // Event klik pada peta untuk mengambil latitude dan longitude
      map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        console.log('Latitude: ' + lat + ', Longitude: ' + lng);

        // Isi form dengan latitude dan longitude
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;

        // Jika marker sudah ada, hapus marker sebelumnya
        if (marker !== null) {
          map.removeLayer(marker);
        }

        // Tambahkan marker pada lokasi yang diklik
        marker = L.marker([lat, lng]).addTo(map);
      });

      // Pastikan ukuran peta di-refresh
      map.invalidateSize();
    }, 300); // Berikan waktu untuk modal tampil sepenuhnya
  } else {
    console.error('Elemen mapid tidak ditemukan!');
  }
});

// Event saat modal ditutup
document.getElementById('tambahdata').addEventListener('hidden.bs.modal', function () {
  console.log('Modal ditutup, reset form dan hapus peta...');

  document.getElementById('latitude').value = '';
  document.getElementById('longitude').value = '';

  // Hapus peta ketika modal ditutup
  if (map !== null) {
    map.remove(); // Hapus peta dari memori
    map = null; // Set map ke null agar bisa di-inisialisasi ulang
  }

  // Reset marker
  marker = null;
});

</script>
