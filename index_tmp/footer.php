    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 footer mt-5 pt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h1 class="fw-bold text-primary mb-4">M -<span class="text-white">Street</span></h1>
                    <p>Diam dolor diam ipsum sit. Aliqu diam amet diam et eos. Clita erat ipsum et lorem et sit, sed stet lorem sit clita</p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-square me-1" href=""><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-square me-1" href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-square me-1" href=""><i class="fab fa-youtube"></i></a>
                        <a class="btn btn-square me-0" href=""><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-light mb-4">Address</h5>
                    <p><i class="fa fa-map-marker-alt me-3"></i>123 Street, New York, USA</p>
                    <p><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                    <p><i class="fa fa-envelope me-3"></i>info@example.com</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-light mb-4">Quick Links</h5>
                    <a class="btn btn-link" href="">Login Admin</a>
                    <a class="btn btn-link" href="">Lihat Peta Jalan</a>
                </div>
            </div>
        </div>
        <div class="container-fluid copyright">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a href="#">SIG Jalan</a>, All Right Reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets_index/lib/wow/wow.min.js"></script>
    <script src="assets_index/lib/easing/easing.min.js"></script>
    <script src="assets_index/lib/waypoints/waypoints.min.js"></script>
    <script src="assets_index/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="assets_index/lib/parallax/parallax.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <!-- DataTables JS -->
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

    <!-- Template Javascript -->
    <script src="assets_index/js/main.js"></script>

    

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
    $.getJSON('distrik-semangga.geojson', function(data) {
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
    var imagePath = "assets/images/"; // Ganti dengan path yang sesuai

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