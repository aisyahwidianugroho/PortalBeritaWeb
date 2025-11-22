<?php
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'jurnalis') {
    header("Location: ../login.php?err=Akses ditolak");
    exit;
}

require_once __DIR__ . '/../koneksi.php';

$id = intval($_GET['id']);

// ubah status draft → pending
$sql = "UPDATE articles SET status='pending' WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    header("Location: jurnalis_draft.php?msg=Terkirim+ke+Editor");
    exit;
} else {
    echo "SQL ERROR: " . mysqli_error($conn);
}
?>
