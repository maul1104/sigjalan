<?php
// Mulai session
session_start();

// Koneksi ke database
include 'inc/config.php'; // Pastikan ada file koneksi ke database

if (isset($_POST['insert'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $aju_jln = mysqli_real_escape_string($conn, $_POST['aju_jln']);
    $kampung = intval($_POST['kampung']);
    $jln_rusak = mysqli_real_escape_string($conn, $_POST['jln_rusak']);
    $lat = mysqli_real_escape_string($conn, $_POST['lat']);
    $lng = mysqli_real_escape_string($conn, $_POST['lng']);

    // Mengambil nama file foto dan menyimpannya ke folder
    $foto = $_FILES['foto']['name'];
    $tmp_foto = $_FILES['foto']['tmp_name'];
    $folder = "assets/img/";

    // Memindahkan file foto ke folder
    if (move_uploaded_file($tmp_foto, $folder . $foto)) {
        // Query untuk memasukkan data pengajuan ke database
        $query = "INSERT INTO pengajuan (nama, alamat, aju_jln, kampung, jln_rusak, foto, lat, lng, status)
                  VALUES ('$nama', '$alamat', '$aju_jln', '$kampung', '$jln_rusak', '$foto', '$lat', '$lng', 'Menunggu')";

        if (mysqli_query($conn, $query)) {
            // Set session sukses
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Pengajuan Anda sudah masuk. Silakan menunggu konfirmasi dari admin.';
        } else {
            // Set session error
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Terjadi kesalahan saat mengajukan.';
        }
    } else {
        // Set session error karena upload gagal
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Gagal mengupload file foto.';
    }

    // Redirect kembali ke index.php
    header("Location: index.php");
    exit();
}

mysqli_close($conn);
?>
