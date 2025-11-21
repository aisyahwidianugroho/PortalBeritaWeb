<?php
session_start();
include "../koneksi.php";

// Pastikan hanya admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php?err=Akses ditolak");
    exit;
}

// Ambil ID berita
$id = intval($_GET['id']);

// UPDATE status artikel → published
$sql = "
    UPDATE articles 
    SET status='dipublikasikan'
        tanggal_publish = NOW()
    WHERE id = $id
";

if (mysqli_query($conn, $sql)) {
    header("Location: admin_dashboard.php?menu=berita_masuk&msg=Berhasil+dipublish");
    exit;
} else {
    echo "SQL ERROR: " . mysqli_error($conn);
}
?>
