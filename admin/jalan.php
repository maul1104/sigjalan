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
                <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#tambahdata">
                    Tambah Data
                </button>
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
                                      <div class="modal-footer">
                                          <!-- Tombol Update -->
                                          <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal<?= $row['id'] ?>">Update</button>
                                          
                                          <!-- Tombol Delete -->
                                          <form action="proses_admin/jalan/delete.php" method="POST">
                                              <input type="hidden" name="id_jalan" value="<?= $row['id'] ?>">
                                              <button type="submit" class="btn btn-danger">Delete</button>
                                          </form>
                                      </div>
                                  </div>
                              </div>
                          </div>
<?php } ?>


<!-- Modal Tambah Data -->
<div class="modal fade" id="tambahdata" tabindex="-1" aria-labelledby="tambahDataLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <!-- Header Modal -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="tambahDataLabel">Tambah Data Jalan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Body Modal -->
      <div class="modal-body">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <form action="<?= $base_url ?>proses_admin/jalan/insert.php" method="POST" enctype="multipart/form-data">
              
              <!-- Nama Ruas -->
              <div class="form-group mb-3">
                <label for="nm_jalan" class="form-label">Nama Ruas</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-road"></i></span>
                  <input type="text" class="form-control" id="nm_jalan" name="nm_jalan" placeholder="Masukkan Nama Ruas Jalan" required>
                </div>
              </div>

              <!-- Kampung -->
              <div class="form-group mb-3">
                <label for="kampung" class="form-label">Kampung</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-city"></i></span>
                  <select class="form-select" id="kampung" name="kampung" required>
                    <option value="" selected disabled>Pilih Kampung</option>
                    <?php
                      // Query untuk mengambil data kampung dari database
                      $query_kampung = "SELECT id, kampung FROM kampung";
                      $result_kampung = mysqli_query($conn, $query_kampung);
                      
                      // Loop data kampung ke dalam opsi dropdown
                      while ($row_kampung = mysqli_fetch_assoc($result_kampung)) {
                        echo '<option value="' . $row_kampung['id'] . '">' . $row_kampung['kampung'] . '</option>';
                      }
                    ?>
                  </select>
                </div>
              </div>

              <!-- Panjang ruas -->
              <div class="form-group mb-3">
                <label for="panjang" class="form-label">Panjang Ruas Jalan</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-road"></i></span>
                  <input type="text" class="form-control" id="panjang" name="panjang" placeholder="Masukkan Panjang Ruas Jalan" required>
                </div>
              </div>

              <!-- lebar ruas -->
              <div class="form-group mb-3">
                <label for="lebar" class="form-label">Lebar Ruas Jalan</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-road"></i></span>
                  <input type="text" class="form-control" id="lebar" name="lebar" placeholder="Masukkan Lebar Ruas Jalan" required>
                </div>
              </div>

              <!-- Kondisi ruas jalan Bagus -->
              <div class="form-group mb-3">
                <label for="jl_bagus" class="form-label">Panjang Ruas Jalan bagus</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-road"></i></span>
                  <input type="text" class="form-control" id="jl_bagus" name="jl_bagus" placeholder="Masukkan Panjang Ruas Jalan Bagus" required>
                </div>
              </div>

              <!-- Kondisi ruas jalan sedang -->
              <div class="form-group mb-3">
                <label for="jl_sedang" class="form-label">Panjang Ruas Jalan Sedang</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-road"></i></span>
                  <input type="text" class="form-control" id="jl_sedang" name="jl_sedang" placeholder="Masukkan Panjang Ruas Jalan Sedang" required>
                </div>
              </div>

              <!-- Kondisi ruas jalan rusak -->
              <div class="form-group mb-3">
                <label for="jl_rusak" class="form-label">Panjang Ruas Jalan Rusak</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-road"></i></span>
                  <input type="text" class="form-control" id="jl_rusak" name="jl_rusak" placeholder="Masukkan Panjang Ruas Jalan Rusak" required>
                </div>
              </div>

                <!-- Input File untuk Upload Foto Kondisi Jalan -->
              <div class="form-group mb-3">
                <label for="foto" class="form-label">Foto Kondisi Jalan</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-camera"></i></span>
                  <input type="file" class="form-control" id="foto" name="foto" required>
                </div>
                <small class="form-text text-muted">Upload file gambar dengan format JPG, JPEG, atau PNG.</small>
              </div>

              <!-- Kecamatan -->
              <!--<div class="form-group mb-3">
                <label for="kecamatan" class="form-label">Kecamatan</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-city"></i></span>
                  <input type="text" class="form-control" id="kecamatan" name="kecamatan" placeholder="Masukkan Nama Kecamatan" required>
                </div>
              </div>-->

              <!-- Latitude -->
              <div class="form-group mb-3">
                <label for="latitude" class="form-label">Latitude</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
                  <input type="text" class="form-control" id="latitude" name="lat" readonly>
                </div>
              </div>

              <!-- Longitude -->
              <div class="form-group mb-3">
                <label for="longitude" class="form-label">Longitude</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
                  <input type="text" class="form-control" id="longitude" name="lng" readonly>
                </div>
              </div>

              <!-- Pilih Lokasi Peta -->
              <div class="form-group mb-4">
                <label class="form-label">Pilih Lokasi pada Peta</label>
                <div class="card border-primary">
                  <div id="mapid" style="height: 300px;" class="card-body p-0"></div>
                </div>
              </div>

              <!-- Tombol Submit -->
              <button type="submit" name="insert" class="btn btn-success w-100 py-2">
                <i class="fas fa-save"></i> Simpan Data
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

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
      map = L.map('mapid').setView([-8.424786, 140.425191], 13);
// Tambahkan layer GeoJSON untuk menampilkan polygon dari distrik-semangga.geojson
      fetch('../distrik-semangga.geojson')
        .then(response => response.json())
        .then(data => {
          // Tambahkan GeoJSON ke peta
          L.geoJSON(data, {
            style: function (feature) {
              return { color: '#ff7800', weight: 1 }; // Atur gaya polygon
            },
            onEachFeature: function (feature, layer) {
              if (feature.properties && feature.properties.kampung) {
                // Tambahkan tooltip dengan nama kampung
                layer.bindTooltip(feature.properties.kampung, {
                  permanent: true, // Tooltip tetap terlihat
                  direction: 'center', // Letak tulisan di tengah polygon
                  className: 'polygon-label' // Tambahkan kelas CSS jika perlu
                }).openTooltip();
              }
            }
          }).addTo(map);
        })
        .catch(error => console.error('Error loading GeoJSON:', error));

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
