<?php
// update_status.php

include('../../inc/config.php');

// Pastikan hanya menerima request POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data yang dikirim via AJAX
    $id_pengajuan = $_POST['id_pengajuan'];
    $action = $_POST['action'];

    // Tentukan status baru berdasarkan action
    $new_status = ($action === 'terima') ? 'Disetujui' : 'Ditolak';

    // Query untuk mengupdate status pengajuan di database
    $query = "UPDATE pengajuan SET status = '$new_status' WHERE id = $id_pengajuan";
    $result = mysqli_query($conn, $query);

    // Cek apakah update berhasil
    if ($result) {
        // Kirim respons sukses ke AJAX
        echo json_encode(['success' => true]);
    } else {
        // Kirim respons error ke AJAX
        echo json_encode(['success' => false, 'message' => 'Gagal memperbarui status']);
    }
}
?>
