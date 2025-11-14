<?php
session_start();
include "../koneksi.php";

// Validasi admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil ID berita
$id = intval($_GET['id']);

// Update status jadi dipublikasikan
$sql = "UPDATE articles 
        SET status='dipublikasikan', tanggal_publish=NOW()
        WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    header("Location: admin_dashboard.php?menu=berita_masuk&msg=published");
    exit;
} else {
    echo "ERROR: " . mysqli_error($conn);
}
?>
