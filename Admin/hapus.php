<?php
session_start();
include "../koneksi.php";

// Validasi admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id']);

$sql = "DELETE FROM articles WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    header("Location: admin_dashboard.php?menu=berita_masuk&msg=deleted");
    exit;
} else {
    echo "ERROR: " . mysqli_error($conn);
}
?>
