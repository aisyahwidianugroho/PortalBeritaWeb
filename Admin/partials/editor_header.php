<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'editor') {
  header('Location: ../login.php'); exit;
}

require_once __DIR__ . '/../../koneksi.php';

$USER_ID = (int)($_SESSION['user_id'] ?? 0);
$NAMA    = $_SESSION['nama'] ?? 'Editor';
$parts   = preg_split('/\s+/', trim($NAMA));
$INITIALS = strtoupper(substr($parts[0], 0, 1) . substr(end($parts), 0, 1));

function esc($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($PAGE_TITLE ?? 'Editor') ?></title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../CSS/dashboard.css">
</head>

<body class="role-editor">

<!-- ============================ SIDEBAR ========================== -->
<aside class="sidebar">
  <div class="brand">
      <h2>Portal Berita</h2>
      <span class="badge">Editor</span>
  </div>

  <?php include __DIR__ . '/editor_sidebar.php'; ?>
</aside>

<!-- ============================ MAIN WRAPPER ===================== -->
<main class="main">

  <!-- ============================ HEADER ======================== -->
  <header class="header">
      <h1><?= esc($PAGE_TITLE ?? 'Dashboard Editor') ?></h1>

      <div class="header-right">
        <div class="chip-role">AN • <?= esc($NAMA) ?></div>
        <div class="avatar"><?= $INITIALS ?></div>
        <a class="btn btn-outline" href="../logoutadmin.php">Keluar</a>
      </div>
  </header>
