<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id']);

$sql = "UPDATE articles
        SET status='pending', tanggal_publish=NULL
        WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    header("Location: admin_dashboard.php?menu=riwayat&msg=unpublished");
    exit;
} else {
    echo "ERROR: " . mysqli_error($conn);
}
?>
