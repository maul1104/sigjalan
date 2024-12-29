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
                <h5 class="card-title fw-semibold mb-4">Data Pengajuan Masuk</h5>
                <div class="table-responsive">
                  <table id="dataTable" class="table text-nowrap mb-0 align-middle">
                    <thead class="text-dark fs-4">
                      <tr>
                        <th class="border-bottom-0">No</th>
                        <th class="border-bottom-0">Nama</th>
                        <th class="border-bottom-0">Alamat</th>
                        <th class="border-bottom-0">Lokasi Pengajuan</th>
                        <th class="border-bottom-0">Status</th>
                        <th class="border-bottom-0">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM pengajuan 
                                  WHERE status = 'menunggu' 
                                  ORDER BY id DESC";
                        $result_tasks = mysqli_query($conn, $query);
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result_tasks)) { ?>
                        <tr>
                            <td class="border-bottom-0"><h6 class="fw-semibold mb-0"><?= $no++ ?></h6></td>
                            <td class="border-bottom-0"><h6 class="fw-semibold mb-1"><?= $row['nama'] ?></h6></td>
                            <td class="border-bottom-0"><p class="mb-0 fw-normal"><?= $row['alamat'] ?></p></td>
                            <td class="border-bottom-0"><p class="mb-0 fw-normal"><?= $row['aju_jln'] ?></p></td>
                            <td class="border-bottom-0"><p class="mb-0 fw-normal"><?= ucfirst($row['status']) ?></p></td>
                            <td class="border-bottom-0">
                                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#detailModal<?= $row['id'] ?>">
                                    Detail
                                </button>
                            </td>
                        </tr>
                            <!-- Modal Detail -->
                            <div class="modal fade" id="detailModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="detailModalLabel<?= $row['id'] ?>" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="detailModalLabel<?= $row['id'] ?>">Detail Pengajuan: <?= $row['nama'] ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6 class="fw-semibold">Informasi Pengajuan</h6>
                                                        <p><strong>Nama: </strong><?= $row['nama'] ?></p>
                                                        <p><strong>Alamat: </strong><?= $row['alamat'] ?></p>
                                                        <p><strong>Lokasi Pengajuan: </strong><?= $row['aju_jln'] ?></p>
                                                        <p><strong>Latitude: </strong><?= $row['lat'] ?></p>
                                                        <p><strong>Longitude: </strong><?= $row['lng'] ?></p>
                                                    </div>
                                                    <div class="col-md-6 text-center">
                                                        <h6 class="fw-semibold">Foto Lokasi</h6>
                                                        <img src="../assets/img/<?= $row['foto'] ?>" alt="Foto Lokasi" class="img-fluid img-thumbnail mb-3" style="max-width: 100%; height: auto; max-height: 300px; object-fit: cover;">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div id="mapid<?= $row['id'] ?>" style="height: 300px;" class="card-body p-0"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <!-- Tombol untuk SweetAlert -->
                                            <button type="button" class="btn btn-success" onclick="confirmAction('terima', <?= $row['id'] ?>)">Terima</button>
                                            <button type="button" class="btn btn-danger" onclick="confirmAction('tolak', <?= $row['id'] ?>)">Tolak</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <script>
                            var map<?= $row['id'] ?>;

                            document.getElementById('detailModal<?= $row['id'] ?>').addEventListener('shown.bs.modal', function () {
                                // Cek jika peta sudah diinisialisasi
                                if (map<?= $row['id'] ?>) {
                                    // Hapus semua layer dari peta
                                    map<?= $row['id'] ?>.eachLayer(function (layer) {
                                        map<?= $row['id'] ?>.removeLayer(layer);
                                    });

                                    // Set view ulang untuk menghindari error re-inisialisasi
                                    map<?= $row['id'] ?>.setView([<?= $row['lat'] ?>, <?= $row['lng'] ?>], 13);
                                } else {
                                    // Jika belum ada, inisialisasi peta baru
                                    map<?= $row['id'] ?> = L.map('mapid<?= $row['id'] ?>').setView([<?= $row['lat'] ?>, <?= $row['lng'] ?>], 13);
                                }

                                // Tambahkan tile layer Google Maps
                                L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
                                    attribution: '&copy; <a href="https://maps.google.com">Google Maps</a> contributors'
                                }).addTo(map<?= $row['id'] ?>);
                            
                                // Tambahkan marker di lokasi yang diberikan
                                L.marker([<?= $row['lat'] ?>, <?= $row['lng'] ?>]).addTo(map<?= $row['id'] ?>);
                            });
                        </script>
                        <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>

<?php include('../template/footer.php'); ?>


<script>
    // Fungsi untuk SweetAlert konfirmasi
    function confirmAction(action, id_pengajuan) {
        let actionText = action === 'terima' ? 'setujui' : 'tolak';
        let statusMessage = action === 'terima' ? 'Laporan telah disetujui' : 'Laporan telah ditolak';

        // Tampilkan konfirmasi dengan SweetAlert
        Swal.fire({
            title: `Apakah Anda yakin ingin ${actionText} laporan ini?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, ' + actionText,
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Lakukan request dengan AJAX
                $.ajax({
                    url: '../proses_admin/pengajuan/update_status.php',
                    type: 'POST',
                    data: {
                        id_pengajuan: id_pengajuan,
                        action: action
                    },
                    success: function(response) {
                        // Tampilkan SweetAlert sukses setelah update status berhasil
                        Swal.fire({
                            title: statusMessage,
                            icon: 'success',
                            confirmButtonText: 'Oke'
                        }).then(() => {
                            // Reload halaman setelah SweetAlert ditutup
                            window.location.href = 'riwayat_pengajuan.php';
                        });
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Terjadi kesalahan',
                            text: 'Gagal memperbarui status laporan.',
                            icon: 'error',
                            confirmButtonText: 'Oke'
                        });
                    }
                });
            }
        });
    }
</script>
