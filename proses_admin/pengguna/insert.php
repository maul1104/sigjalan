<?php
include('../../inc/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);  // Hashing password dengan md5
    $level = $_POST['level'];
    $foto = $_FILES['foto']['name'];
    
    $target_dir = "../../assets/img/";
    $target_file = $target_dir . basename($foto);
    
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
        $query = "INSERT INTO tbl_log (nama, username, password, level, foto) VALUES ('$nama', '$username', '$password', '$level', '$foto')";
        if (mysqli_query($conn, $query)) {
            header('Location: ../../admin/pengguna.php?status=success');
        } else {
            header('Location: ../../admin/pengguna.php?status=error');
        }
        exit();               
    }
}
?>
