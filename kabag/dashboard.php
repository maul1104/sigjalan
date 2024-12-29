<?php 
include('../inc/config.php');

if ($_SESSION['id'] == NULL) {
  header('Location: '.$base_url.'admin/login.php');
}
include('../template/header.php');
include('../template/sidebar.php');

error_reporting(0);

// Ambil data jalan dari database
$query = "SELECT nm_jalan, panjang, jl_bagus, jl_sedang, jl_rusak, lat, lng, foto FROM jalan";
$result = mysqli_query($conn, $query);

$jalan_data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $jalan_data[] = $row; // Simpan data dalam array
}
 
$id = $_SESSION['id'];
?>
    <!--  Main wrapper -->
      <div class="container-fluid">
        <!--  Row 1 -->
        <div class="row">
          <div class="col-lg-12 d-flex align-items-strech">
            <div class="card w-100">
              <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                  <div class="mb-3 mb-sm-0">
                    <h5 class="card-title fw-semibold">Peta Jalan</h5>
                  </div>
                </div>
                <div id="map" style="height: 500px; z-index: 1;"></div>
              </div>
            </div>
          </div>
          <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
              <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4">Data Jalan</h5>
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
        </div>
        </div>
        <div class="py-6 px-6 text-center">
          <p class="mb-0 fs-4">Design and Developed by <a href="https://adminmart.com/" target="_blank" class="pe-1 text-primary text-decoration-underline">Admin</a></p>
        </div>
      </div>
    </div>
  </div>
  

  <?php include('../template/footer.php'); ?>
