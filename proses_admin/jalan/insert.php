<?php
// Mulai sesi atau tambahkan ini jika belum ada session_start di atas
session_start();

// Koneksi ke database
include_once("../../inc/config.php"); // Pastikan koneksi sudah benar

if (isset($_POST['insert'])) {
    // Ambil data dari form
    $nm_jalan = mysqli_real_escape_string($conn, $_POST['nm_jalan']);
    $kampung = mysqli_real_escape_string($conn, $_POST['kampung']);
    $panjang = mysqli_real_escape_string($conn, $_POST['panjang']);
    $lebar = mysqli_real_escape_string($conn, $_POST['lebar']);
    $jl_bagus = mysqli_real_escape_string($conn, $_POST['jl_bagus']);
    $jl_sedang = mysqli_real_escape_string($conn, $_POST['jl_sedang']);
    $jl_rusak = mysqli_real_escape_string($conn, $_POST['jl_rusak']);
    $lat = mysqli_real_escape_string($conn, $_POST['lat']);
    $lng = mysqli_real_escape_string($conn, $_POST['lng']);

    // Proses upload file gambar
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto = $_FILES['foto']['name'];
        $tmp_foto = $_FILES['foto']['tmp_name'];

        // Direktori tempat menyimpan file gambar
        $upload_dir = "../../assets/images/";
        $foto_path = uniqid() . "-" . basename($foto); // Buat nama unik untuk menghindari bentrok
        $target_file = $upload_dir . $foto_path;

        // Validasi tipe file gambar
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png'];

        if (in_array($imageFileType, $allowed_types)) {
            // Pindahkan file ke direktori upload
            if (move_uploaded_file($tmp_foto, $target_file)) {
                // Lakukan insert data ke database
                $query = "INSERT INTO jalan (nm_jalan, kampung, panjang, lebar, jl_bagus, jl_sedang, jl_rusak, foto, lat, lng) 
                          VALUES ('$nm_jalan', '$kampung', '$panjang', '$lebar', '$jl_bagus', '$jl_sedang', '$jl_rusak', '$foto_path', '$lat', '$lng')";

                // Eksekusi query
                if (mysqli_query($conn, $query)) {
                    // Redirect atau tampilkan pesan berhasil
                    $_SESSION['message'] = "Data jalan berhasil ditambahkan.";
                    $_SESSION['message_type'] = "success";
                    header('location: '.$base_url.'admin/jalan.php');
                    exit;
                } else {
                    $_SESSION['message'] = "Gagal menyimpan data: " . mysqli_error($conn);
                    $_SESSION['message_type'] = "danger";
                    header('location: '.$base_url.'admin/jalan.php');
                    exit;
                }
            } else {
                $_SESSION['message'] = "Gagal meng-upload gambar.";
                $_SESSION['message_type'] = "danger";
                header('location: '.$base_url.'admin/jalan.php');
                exit;
            }
        } else {
            $_SESSION['message'] = "Format gambar tidak valid. Hanya JPG, JPEG, atau PNG yang diperbolehkan.";
            $_SESSION['message_type'] = "danger";
            header('location: '.$base_url.'admin/jalan.php');
            exit;
        }
    } else {
        $_SESSION['message'] = "Tidak ada file gambar yang di-upload.";
        $_SESSION['message_type'] = "danger";
        header('location: '.$base_url.'admin/jalan.php');
        exit;
    }
} else {
    // Redirect jika akses langsung ke halaman ini
    header('location: '.$base_url.'admin/jalan.php');
    exit;
}
?>
