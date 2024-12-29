<?php
include('../../inc/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $level = $_POST['level'];
    $foto = $_FILES['foto']['name'];

    // Cek apakah user memasukkan password baru
    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']); // Hash password baru dengan md5
        $password_query = ", password='$password'";
    } else {
        $password_query = ""; // Jika tidak ada password baru, tidak mengubah password
    }

    // Jika ada file foto yang diupload
    if (!empty($foto)) {
        $target_dir = "../../assets/img/";
        $target_file = $target_dir . basename($foto);
        move_uploaded_file($_FILES['foto']['tmp_name'], $target_file);
        $query = "UPDATE tbl_log SET nama='$nama', username='$username', level='$level', foto='$foto' $password_query WHERE id='$id'";
    } else {
        $query = "UPDATE tbl_log SET nama='$nama', username='$username', level='$level' $password_query WHERE id='$id'";
    }

    if (mysqli_query($conn, $query)) {
        header('Location: ../../admin/pengguna.php?status=success');
    } else {
        header('Location: ../../admin/pengguna.php?status=error');
    }
    exit();
       
    
}
?>
