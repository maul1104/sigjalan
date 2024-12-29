<?php
include('inc/config.php');
include('index_tmp/header.php');
include('index_tmp/navbar.php');

if (isset($_SESSION['status']) && isset($_SESSION['message'])) {
    $status = $_SESSION['status'];  // 'success' atau 'error'
    $message = $_SESSION['message']; // Pesan yang akan ditampilkan
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '$status', 
                title: '". ($status == 'success' ? 'Berhasil!' : 'Gagal!') ."',
                text: '$message',
                showConfirmButton: true,  // Tombol OK akan muncul
                confirmButtonText: 'OK'
            }).then(function() {
                // Scroll ke modal setelah alert dikonfirmasi
                var modalElement = document.getElementById('pengajuan');
                if (modalElement) {
                    modalElement.scrollIntoView({behavior: 'smooth'});
                }
            });
        });
    </script>";

    // Hapus session alert setelah ditampilkan
    unset($_SESSION['status']);
    unset($_SESSION['message']);
}
// Ambil halaman saat ini, jika tidak ada default ke halaman 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // Jumlah data per halaman
$offset = ($page - 1) * $limit;

// Ambil data jalan dari database (data jalan terpisah dari data pengajuan)
$query_jalan = "SELECT nm_jalan, panjang, jl_bagus, jl_sedang, jl_rusak, lat, lng, foto FROM jalan";
$result_jalan = mysqli_query($conn, $query_jalan);

$jalan_data = [];
while ($row = mysqli_fetch_assoc($result_jalan)) {
    $jalan_data[] = $row; // Simpan data dalam array
}

// Ambil data pengajuan dari database dengan LIMIT dan OFFSET
$search = isset($_GET['search']) ? $_GET['search'] : '';
$query_pengajuan = "SELECT pengajuan.*, kampung.kampung AS kampung FROM pengajuan JOIN kampung ON pengajuan.kampung = kampung.id ORDER BY pengajuan.id DESC";
if (!empty($search)) {
    $query_pengajuan .= " WHERE nama LIKE '%$search%' OR kampung LIKE '%$search%'";
}
$query_pengajuan .= " LIMIT $limit OFFSET $offset";
$result_pengajuan = mysqli_query($conn, $query_pengajuan);

$pengajuan_data = [];
while ($row = mysqli_fetch_assoc($result_pengajuan)) {
    $pengajuan_data[] = $row;
}

// Hitung total data untuk pagination
$count_query = "SELECT COUNT(*) as total FROM pengajuan";
if (!empty($search)) {
    $count_query .= " WHERE nama LIKE '%$search%' OR kampung LIKE '%$search%'";
}
$count_result = mysqli_query($conn, $count_query);
$count_row = mysqli_fetch_assoc($count_result);
$total_data = $count_row['total'];

// Hitung total halaman
$total_pages = ceil($total_data / $limit);
?>

<!-- Carousel Start -->
<div class="container-fluid p-0 mb-5">
    <div id="header-carousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="w-100" src="assets_index/img/back.jpg" alt="Image">
                <div class="carousel-caption">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-7 pt-5">
                                <h1 class="display-4 text-white mb-3 animated slideInDown">Selamat Datang di Website M - Street</h1>
                                <p class="fs-5 text-white-50 mb-5 animated slideInDown">Website M - Street merupakan website yang menginformasikan ruas-ruas jalan rusak pada distrik semangga yang ditampilkan dalam bentuk peta interaktif</p>
                                <a class="btn btn-primary py-2 px-3 animated slideInDown" href="#map">
                                    Lihat peta
                                    <div class="d-inline-flex btn-sm-square bg-white text-primary rounded-circle ms-2">
                                        <i class="fa fa-arrow-right"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Carousel End -->

<!-- Peta Jalan -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <div class="d-inline-block rounded-pill bg-secondary text-primary py-1 px-3 mb-3">Peta</div>
            <h1 class="display-6 mb-5">Peta Sebaran Jalan</h1>
        </div>
        <div id="map" style="height: 800px;"></div>
    </div>
</div>

<!-- Data Pengajuan -->
<div class="container-xxl py-5">
    <div class="container" id="pengajuan">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <div class="d-inline-block rounded-pill bg-secondary text-primary py-1 px-3 mb-3">Pengajuan</div>
            <h1 class="display-6 mb-5">Data Pengajuan</h1>
        </div>

            <!-- Form pencarian -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form action="#pengajuan" method="GET" class="d-flex">
                        <input class="form-control me-2" type="search" placeholder="Cari Nama atau Kampung" aria-label="Search" name="search" value="<?= htmlspecialchars($search); ?>">
                        <button class="btn btn-outline-primary" type="submit">Cari</button>
                    </form>
                </div>
            <div class="col-md-6 text-end">
                <!-- Tombol untuk membuka modal pengajuan baru -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pengajuanModal">
                    Tambah Pengajuan Baru
                </button>
            </div>
        </div>

        <!-- Tabel Data Pengajuan -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Lokasi Pengajuan</th>
                        <th>Kampung</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (count($pengajuan_data) > 0) {
                        $no = $offset + 1;
                        foreach ($pengajuan_data as $pengajuan) { ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($pengajuan['nama']); ?></td>
                            <td><?= htmlspecialchars($pengajuan['alamat']); ?></td>
                            <td><?= htmlspecialchars($pengajuan['aju_jln']); ?></td>
                            <td><?= htmlspecialchars($pengajuan['kampung']); ?></td>
                            <td>
                                <?php
                                // Menentukan label status
                                if ($pengajuan['status'] == 'Menunggu') {
                                    echo '<span class="badge bg-warning text-dark">Menunggu</span>';
                                } elseif ($pengajuan['status'] == 'Ditolak') {
                                    echo '<span class="badge bg-danger">Ditolak</span>';
                                } elseif ($pengajuan['status'] == 'Disetujui') {
                                    echo '<span class="badge bg-success">Diterima</span>';
                                }
                                ?>
                            </td>
                        </tr>
                        <?php } 
                    } else {
                        echo '<tr><td colspan="6" class="text-center">No Available Data</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?= $page - 1; ?>&search=<?= htmlspecialchars($search); ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                    <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?= $i; ?>&search=<?= htmlspecialchars($search); ?>"><?= $i; ?></a>
                    </li>
                <?php } ?>
                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?= $page + 1; ?>&search=<?= htmlspecialchars($search); ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<!-- Modal Pengajuan Baru -->
<div class="modal fade" id="pengajuanModal" tabindex="-1" aria-labelledby="pengajuanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Tambahkan 'modal-lg' untuk membuat modal lebih besar -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pengajuanModalLabel">Pengajuan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="proses_pengajuan.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" required>
                    </div>
                    <div class="mb-3">
                        <label for="aju_jln" class="form-label">Lokasi Pengajuan</label>
                        <input type="text" class="form-control" id="aju_jln" name="aju_jln" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="kampung" class="form-label">Kampung</label>
                        <div class="input-group">
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
                    <div class="mb-3">
                        <label for="jln_rusak" class="form-label">Perkiraan Panjang Jalan Rusak</label>
                        <input type="text" class="form-control" id="jln_rusak" name="jln_rusak" required>
                    </div>

                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto Kondisi Jalan</label>
                            <input type="file" class="form-control" id="foto" name="foto" required>
                    </div>

                    <!-- Tambahkan field untuk latitude dan longitude -->
                    <div class="mb-3">
                        <label for="lat" class="form-label">Latitude</label>
                        <input type="text" class="form-control" id="latitude" name="lat" readonly required>
                    </div>
                    <div class="mb-3">
                        <label for="lng" class="form-label">Longitude</label>
                        <input type="text" class="form-control" id="longitude" name="lng" readonly required>
                    </div>

                    <!-- Peta untuk memilih lokasi -->
                    <div class="mb-3">
                        <label for="peta" class="form-label">Pilih Lokasi pada Peta</label>
                        <div id="mapModal" style="height: 400px;"></div> <!-- Peta akan ditampilkan di sini -->
                    </div>

                    <p class="text-muted">Klik pada peta untuk memilih lokasi (latitude & longitude akan otomatis terisi).</p>
                    <button type="submit" name="insert" class="btn btn-primary">Ajukan</button>
                </form>
            </div>
        </div>
    </div>
</div>


<?php include('index_tmp/footer.php'); ?> 

<script>
    // Scroll halus setelah pencarian
document.addEventListener("DOMContentLoaded", function() {
    if (window.location.hash === "#pengajuan") {
        document.querySelector('#pengajuan').scrollIntoView({ behavior: 'smooth' });
    }
});
</script>
<script>
// Inisialisasi peta di dalam modal
var mapModal;
var markerModal;

// Fungsi untuk menginisialisasi peta dengan tampilan hybrid
function initMapModal() {
    mapModal = L.map('mapModal').setView([-8.424786, 140.425191], 13); // Sesuaikan dengan koordinat daerah Anda

    // Layer hybrid dari Esri
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mapModal);

    // Layer citra satelit dari Esri
    L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
    }).addTo(mapModal);

    // Event listener untuk menangkap klik pada peta
    mapModal.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;

        // Jika marker sudah ada, hapus marker sebelumnya
        if (markerModal) {
            mapModal.removeLayer(markerModal);
        }

        // Tambah marker baru pada lokasi yang diklik
        markerModal = L.marker([lat, lng]).addTo(mapModal)
            .bindPopup("Lokasi yang Anda pilih:<br>Lat: " + lat + "<br>Lng: " + lng)
            .openPopup();

        // Masukkan koordinat ke dalam field di modal
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
    });
}

// Event listener saat modal dibuka
$('#pengajuanModal').on('shown.bs.modal', function () {
    if (!mapModal) {
        initMapModal(); // Inisialisasi peta hanya jika belum diinisialisasi
    }
    setTimeout(function() {
        mapModal.invalidateSize(); // Pastikan peta merender dengan benar
    }, 200);
});


</script>





