<?php
include('inc/config.php');
include('index_tmp/header.php');
include('index_tmp/navbar.php');
?>



<!-- Tempatkan peta di sini -->
<div id="map" style="height: 800px;"></div>


<!-- Sidebar untuk pencarian -->
<div id="sidebar" class="leaflet-sidebar collapsed">
    <!-- Tabs pada sidebar -->
    <div class="leaflet-sidebar-tabs">
        <ul role="tablist">
            <li><a href="#home" role="tab"><i class="fa fa-search"></i></a></li> <!-- Ikon Search -->
        </ul>
    </div>

    <!-- Konten sidebar -->
    <div class="leaflet-sidebar-content">
        <div class="leaflet-sidebar-pane" id="home">
            <h1 class="leaflet-sidebar-header">
                Pencarian Lokasi
                <span class="leaflet-sidebar-close"><i class="fa fa-times"></i></span>
            </h1>
            <div>
                <input type="text" id="searchBox" placeholder="Cari lokasi..." style="width: 100%; padding: 8px;">
                <button onclick="searchLocation()" style="width: 100%; margin-top: 10px;">Cari</button>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
    // Inisialisasi peta
    var map = L.map('map').setView([-8.3867891,140.2253513], 13); // Sesuaikan koordinat dengan lokasi yang diinginkan

    // Tambahkan tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Tambahkan marker
    var marker = L.marker([-8.4944, 140.4018]).addTo(map)
        .bindPopup('Lokasi Peta.').openPopup();
</script>

<!-- Leaflet Sidebar JS -->
<script src="https://unpkg.com/leaflet-sidebar-v2/js/leaflet-sidebar.min.js"></script>
<script>
    // Inisialisasi sidebar
    var sidebar = L.control.sidebar({
        autopan: true,       // Peta akan bergerak saat sidebar dibuka
        closeButton: true,   // Tombol close pada sidebar
        container: 'sidebar',
        position: 'left'    // Posisi sidebar di sebelah kanan
    }).addTo(map);

    // Fungsi pencarian lokasi (contoh sederhana dengan zoom)
    function searchLocation() {
        var location = document.getElementById("searchBox").value;
        if (location === "lokasi1") {
            map.setView([-8.4944, 140.4018], 15); // Lokasi1: Sesuaikan dengan lokasi yang dicari
        } else {
            alert("Lokasi tidak ditemukan!"); // Contoh pesan error
        }
    }

    // Membuka sidebar secara default
    sidebar.open('home');
</script>

<?php include('index_tmp/footer2.php'); ?>
