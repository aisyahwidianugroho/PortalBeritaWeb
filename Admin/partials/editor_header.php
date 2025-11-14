<?php
// Admin/partials/editor_header.php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'editor') {
  header('Location: ../login.php?err=Silakan login sebagai Editor'); exit;
}

require_once __DIR__ . '/../../koneksi.php';   // pastikan $conn (mysqli) ada

// context untuk header / halaman
$USER_ID = (int)($_SESSION['user_id'] ?? 0);
$NAMA    = $_SESSION['nama'] ?? 'Editor';
$parts   = preg_split('/\s+/', trim($NAMA));
$INITIALS = strtoupper(substr($parts[0] ?? '', 0, 1) . substr($parts[count($parts)-1] ?? '', 0, 1));

function esc($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($PAGE_TITLE ?? 'Editor') ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../CSS/dashboard.css">
</head>
<body class="role-editor">
<?php include __DIR__ . '/editor_sidebar.php'; ?>
<main class="main">
  <header class="header">
    <h1><?= esc($PAGE_TITLE ?? 'Editor') ?></h1>
    <div class="header-right">
      <div class="chip-role">AN • <?= esc($NAMA) ?></div>
      <div class="avatar"><?= $INITIALS ?: 'ED' ?></div>
      <a class="btn btn-outline" href="../logoutadmin.php">Keluar</a>
    </div>
  </header>
