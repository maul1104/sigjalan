<?php
require_once '../vendor/autoload.php';  // Load Dompdf
require_once '../inc/config.php'; // Koneksi database

use Dompdf\Dompdf;

// Konversi gambar ke base64
$path = '../assets/images/logos/Lambang_Kabupaten_Merauke.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

// Query untuk mengambil data jalan
$query = "SELECT jalan.*, kampung.kampung AS kampung 
          FROM jalan 
          JOIN kampung ON jalan.kampung = kampung.id 
          ORDER BY jalan.id DESC";
$result = mysqli_query($conn, $query);

// Generate HTML untuk PDF
$html = '
<style>
    body {
        font-family: Arial, sans-serif;
    }
    .header {
        text-align: center;
        margin-bottom: 20px;
        position: relative;
    }
    .header img {
        width: 80px;
        position: absolute;
        top: 0;
        left: 0;
    }
    .header h3 {
        margin: 0;
        font-size: 14px;
    }
    .header p {
        margin: 0;
        font-size: 12px;
    }
    .header hr {
        margin-top: 30px;
        border: 0;
        border-top: 2px solid #000;
    }
    h3 {
        text-align: center;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }
    th {
        background-color: #f2f2f2;
        color: #333;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    tr:hover {
        background-color: #f1f1f1;
    }
    td {
        font-size: 12px;
    }
    .foto {
        width: 50px;
        height: 50px;
    }
</style>

<div class="header">
    <img src="' . $base64 . '" alt="Logo">
    <h3>PEMERINTAH KABUPATEN MERAUKE<br>DINAS PEKERJAAN UMUM DAN PENATAAN RUANG</h3>
    <p>Jalan Trikora Merauke – Papua Selatan, Telp (0971) 321154<br>
    Fax (0971) 321154, Pos-el : <a href="mailto:dpup.kabmrk@gmail.com">dpup.kabmrk@gmail.com</a>, Kode Pos 99613</p>
    <hr>
</div>';

$html .= '<h3>Data Jalan</h3>';
$html .= '<table>
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Ruas</th>
                <th>Kampung</th>
                <th>Panjang</th>
                <th>Lebar</th>
                <th>Panjang Jalan Bagus</th>
                <th>Panjang Jalan Sedang</th>
                <th>Panjang Jalan Rusak</th>
                <th>Foto Kondisi Jalan</th>
              </tr>
            </thead>
            <tbody>';

            $no = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                // Konversi foto pengajuan ke base64 jika tersedia
                $fotoPath = '../assets/images/' . $row['foto'];
                if (file_exists($fotoPath)) {
                    $fotoData = file_get_contents($fotoPath);
                    $fotoBase64 = 'data:image/jpeg;base64,' . base64_encode($fotoData);
                    $fotoTag = '<img src="' . $fotoBase64 . '" class="foto">';
                } else {
                    $fotoTag = 'Tidak Ada Foto';
                }
            
                $html .= '<tr>
                <td>' . $no++ . '</td>
                <td>' . $row['nm_jalan'] . '</td>
                <td>' . $row['kampung'] . '</td>
                <td>' . $row['panjang'] . ' KM</td>
                <td>' . $row['lebar'] . ' M</td>
                <td>' . $row['jl_bagus'] . ' KM</td>
                <td>' . $row['jl_sedang'] . ' KM</td>
                <td>' . $row['jl_rusak'] . ' KM</td>
                <td>' . $fotoTag . '</td>
              </tr>';
}

$html .= '</tbody></table>';

// Inisialisasi Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// Set orientasi kertas dan ukuran
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Output file PDF ke browser
$dompdf->stream("laporan_data_jalan.pdf", ["Attachment" => false]);
?>
