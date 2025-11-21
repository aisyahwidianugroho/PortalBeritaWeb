<?php
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'editor') {
    header("Location: ../login.php?err=Akses ditolak");
    exit;
}

require_once __DIR__ . '/../koneksi.php';

$id = intval($_GET['id']);

// ubah status
$sql = "UPDATE articles SET status='menunggu_admin' WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    header("Location: editor_review.php?msg=Terkirim+ke+Admin");
    exit;
} else {
    echo "SQL ERROR: " . mysqli_error($conn);
}
?>
