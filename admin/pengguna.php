<?php
include('../inc/config.php');
if ($_SESSION['id'] == NULL) {
    header('location: '.$base_url.'admin/login.php');
}
include('../template/header.php');
include('../template/sidebar.php');

// Handle delete response
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    echo "<script>
        Swal.fire({
            title: 'Berhasil!',
            text: 'Data pengguna berhasil dihapus!',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    </script>";
} elseif (isset($_GET['status']) && $_GET['status'] == 'error') {
    echo "<script>
        Swal.fire({
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat menghapus data.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    </script>";
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 d-flex align-item-stretch">
            <div class="card w-100">
                <div class="card-body p-4">
                    <h5 class="card-title fw-semibold mb-4">Data Pengguna</h5>
                    <!-- Tombol untuk tambah pengguna -->
                    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah Pengguna</button>
                    <div class="table-responsive">
                        <table id="dataTable" class="table text-nowrap mb-0 align-middle">
                            <thead class="text-dark fs-4">
                                <tr>
                                    <th class="border-bottom-0">No</th>
                                    <th class="border-bottom-0">Nama</th>
                                    <th class="border-bottom-0">Username</th>
                                    <th class="border-bottom-0">Jabatan</th>
                                    <th class="border-bottom-0">Foto</th>
                                    <th class="border-bottom-0">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT * FROM tbl_log WHERE level IN ('admin', 'kabag') ORDER BY id DESC";
                                $result = mysqli_query($conn, $query);
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr>
                                    <td class="border-bottom-0"><?= $no++ ?></td>
                                    <td class="border-bottom-0"><?= $row['nama'] ?></td>
                                    <td class="border-bottom-0"><?= $row['username'] ?></td>
                                    <td class="border-bottom-0"><?= ucfirst($row['level']) ?></td>
                                    <td class="border-bottom-0">
                                        <img src="../assets/img/<?= $row['foto'] ?>" alt="Foto Pengguna" class="img-fluid img-thumbnail" style="max-width: 80px; max-height: 80px; object-fit: cover;">
                                    </td>
                                    <td class="border-bottom-0">
                                        <!-- Tombol Edit -->
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">Edit</button>

                                        <!-- Tombol Hapus -->
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete(<?= $row['id'] ?>)">Delete</button>
                                    </td>
                                </tr>

                                <!-- Modal Edit Pengguna -->
                                <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row['id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel<?= $row['id'] ?>">Edit Pengguna</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="editForm<?= $row['id'] ?>" action="<?= $base_url ?>proses_admin/pengguna/update.php" method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                    <div class="mb-3">
                                                        <label for="nama" class="form-label">Nama</label>
                                                        <input type="text" class="form-control" id="nama" name="nama" value="<?= $row['nama'] ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="username" class="form-label">Username</label>
                                                        <input type="text" class="form-control" id="username" name="username" value="<?= $row['username'] ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="password" class="form-label">Password (kosongkan jika tidak ingin mengganti)</label>
                                                        <input type="password" class="form-control" id="password" name="password">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="level" class="form-label">Jabatan</label>
                                                        <select class="form-select" id="jabatan" name="level" required>
                                                            <option value="admin" <?= $row['level'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                                            <option value="kabag" <?= $row['level'] == 'kabag' ? 'selected' : '' ?>>Kabag</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="foto" class="form-label">Foto</label>
                                                        <input type="file" class="form-control" id="foto" name="foto">
                                                    </div>
                                                    <button type="button" class="btn btn-primary" onclick="confirmUpdate(<?= $row['id'] ?>)">Simpan Perubahan</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pengguna -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel">Tambah Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="tambahForm" action="<?= $base_url ?>proses_admin/pengguna/insert.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="level" class="form-label">Jabatan</label>
                        <select class="form-select" id="jabatan" name="level" required>
                            <option value="admin">Admin</option>
                            <option value="kabag">Kabag</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="foto" name="foto" required>
                    </div>
                    <button type="button" class="btn btn-success" onclick="confirmAdd()">Tambah Pengguna</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('../template/footer.php'); ?>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Yakin Hapus?',
            text: 'Anda tidak bisa mengembalikan data ini setelah dihapus!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= $base_url ?>proses_admin/pengguna/delete.php?id=" + id;
            }
        });
    }

    function confirmAdd() {
    Swal.fire({
        title: 'Tambah Pengguna?',
        text: 'Apakah Anda yakin untuk menambahkan pengguna baru?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Tambah'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('tambahForm').submit(); // Submit form setelah konfirmasi
        }
    });
}

function confirmUpdate(id) {
    Swal.fire({
        title: 'Update Pengguna?',
        text: 'Apakah Anda yakin ingin mengubah data pengguna ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Update'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('editForm' + id).submit(); // Submit form setelah konfirmasi
        }
    });
}


</script>
