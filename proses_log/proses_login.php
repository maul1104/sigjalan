<?php 
 
include('../inc/config.php');
 
error_reporting(0);
 
session_start();

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
 
    $sql = "SELECT * FROM tbl_log WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);

        if($row['level'] == 'admin'){
            $_SESSION['level'] = $row['level'];
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header('Location: '.$base_url.'admin/dashboard.php');

        }else if($row['level'] == 'kabag'){
            $_SESSION['level'] = $row['level'];
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header('Location: '.$base_url.'kabag/dashboard.php');
        }
    } else {
        $_SESSION['message'] = 'login Gagal !!';
        $_SESSION['message_type'] = 'danger';
        header('Location: '.$base_url.'admin/login.php');
    }
}else{
    $_SESSION['message'] = 'login Gagal !!';
    $_SESSION['message_type'] = 'danger';
    header('Location: '.$base_url.'');
}
 
?>