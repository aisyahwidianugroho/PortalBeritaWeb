<?php
session_start();
include "../koneksi.php";

// Validasi hanya admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php?err=Akses ditolak");
    exit;
}

// Ambil ID artikel
$id = intval($_GET['id']);

// UPDATE status artikel → published
$sql = "
    UPDATE articles 
    SET status='published',
        tanggal_publish = NOW()
    WHERE id = $id
";

if (mysqli_query($conn, $sql)) {
    header("Location: admin_dashboard.php?menu=riwayat&msg=Berhasil+dipublish");
    exit;
} else {
    echo "SQL ERROR: " . mysqli_error($conn);
}
?>
