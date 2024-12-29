<?php
include('../../inc/config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM tbl_log WHERE id='$id'";
    if (mysqli_query($conn, $query)) {
        header('location: ../../admin/pengguna.php?status=success');
        exit();
    } else {
        header('location: ../../admin/pengguna.php?status=error');
        exit();
    }
}
?>
