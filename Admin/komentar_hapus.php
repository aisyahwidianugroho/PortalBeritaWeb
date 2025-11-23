<?php
include '../koneksi.php';

// pastikan ID ada
if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php?menu=komentar");
    exit;
}

$id = (int) $_GET['id'];

// hapus komentar
mysqli_query($conn, "DELETE FROM comments WHERE id = $id");

// kembali ke halaman admin komentar
header("Location: admin_dashboard.php?menu=komentar");
exit;
?>
