<script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../assets/js/sidebarmenu.js"></script>
  <script src="../assets/js/app.min.js"></script>
  <script src="../assets/js/dashboard.js"></script>
  <!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css">
  <!-- Bootstrap JS (before closing body tag) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <!-- DataTables JS -->
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
  <script>
    // Base Layer untuk Hybrid
    var hybridLayer = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
        attribution: '&copy; <a href="https://maps.google.com">Google Maps</a>'
    });

    // Inisialisasi peta dengan hanya layer Hybrid
    var map = L.map('map', {
        center: [-8.424786, 140.425191], // Sesuaikan dengan lokasi yang diinginkan
        zoom: 13,
        layers: [hybridLayer] // Layer default hanya Hybrid
    });

    // Fungsi untuk menentukan warna berdasarkan nama kampung
    function getColor(kampung) {
        switch (kampung) {
            case 'urumb': return '#FF5733';
            case 'matara': return '#33FF57';
            case 'waninggap nanggo': return '#3357FF';
            case 'waninggap kai': return '#FF33A8';
            case 'semangga jaya': return '#33FFF3';
            case 'kuper': return '#F3FF33';
            case 'kuprik': return '#A833FF';
            case 'sidomulyo': return '#FF8333';
            case 'marga mulya': return '#3383FF';
            case 'muram sari': return '#83FF33';
            default: return '#999999'; // Warna default jika kampung tidak dikenali
        }
    }

    // Tambahkan polygon dari file GeoJSON
    $.getJSON('../distrik-semangga.geojson', function(data) {
        L.geoJSON(data, {
            style: function(feature) {
                return {
                    color: getColor(feature.properties.kampung), // Warna berdasarkan nama kampung
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.5
                };
            },
            onEachFeature: function(feature, layer) {
                if (feature.properties && feature.properties.kampung) {
                    layer.bindPopup('<strong>Kampung: </strong>' + feature.properties.kampung);
                }
            }
        }).addTo(map);
    });

    // Data marker diambil dari PHP
    var jalanData = <?php echo json_encode($jalan_data); ?>;

    // Path direktori tempat foto disimpan
    var imagePath = "../assets/images/"; // Ganti dengan path yang sesuai

    // Tambahkan marker secara dinamis
    jalanData.forEach(function(jalan) {
        var lat = jalan.lat;
        var lng = jalan.lng;
        var panjang = jalan.panjang;
        var bagus = jalan.jl_bagus;
        var sedang = jalan.jl_sedang;
        var rusak = jalan.jl_rusak;
        var nm_jalan = jalan.nm_jalan;
        var imgSrc = imagePath + jalan.foto; // Bangun URL gambar dari nama file

        // Tambahkan marker dengan popup yang berisi informasi dan gambar dengan ukuran konsisten
        var marker = L.marker([lat, lng]).addTo(map)
            .bindPopup(
                '<strong>' + nm_jalan + '</strong><br>' +
                'Panjang: ' + panjang + ' kilometer<br>' +
                'Jalan Bagus: ' + bagus + ' kilometer<br>' +
                'Jalan Sedang: ' + sedang + ' kilometer<br>' +
                'Jalan Rusak: ' + rusak + ' kilometer<br>' +
                '<img src="' + imgSrc + '" style="width: 150px; height: auto;" alt="Gambar Jalan">'
            );
    });
</script>
  
  <script>
    $(document).ready(function() {
      $('#dataTable').DataTable({
        "pageLength": 10,
        "lengthChange": false,
        "ordering": true,
        "searching": true,
        "paging": true,
        "info": true
      });
    });
  </script>

</body>

</html>