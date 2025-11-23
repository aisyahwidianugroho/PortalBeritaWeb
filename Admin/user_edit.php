<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php?err=Akses ditolak");
    exit;
}

require_once "../koneksi.php";

$id = intval($_GET['id']);
$u = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$id"));

if (!$u) {
    die("Pengguna tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pengguna</title>
    <link rel="stylesheet" href="../CSS/admin_dashboard.css">
    <link rel="stylesheet" href="../CSS/dashboard.css">
</head>

<body class="role-admin">

<?php include __DIR__ . "/partials-admin/admin_header.php"; ?>

<main class="main">

<section class="card dashboard-section" style="max-width:600px; margin:auto;">
    <h2 class="section-title">Edit Pengguna</h2>

    <form ac
