<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'jurnalis') {
  header('Location: ../login.php?err=Silakan login sebagai Jurnalis');
  exit;
}

require_once __DIR__ . '/../../koneksi.php';

function esc($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$USER_ID = (int)($_SESSION['user_id'] ?? 0);

function scalar(mysqli $c, string $sql){
  $res = $c->query($sql);
  if(!$res){ return 0; }
  $row = $res->fetch_row();
  return $row ? (is_numeric($row[0]) ? (int)$row[0] : $row[0]) : 0;
}

$NAMA = $_SESSION['nama'] ?? 'Jurnalis';
$INITIALS = (function($full){
  $p = preg_split('/\s+/', trim($full));
  return strtoupper(substr($p[0]??'J',0,1) . substr($p[count($p)-1]??'R',0,1));
})($NAMA);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($PAGE_TITLE ?? 'Jurnalis') ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../CSS/dashboard.css">
</head>
<body class="role-jurnalis">
<?php include __DIR__ . '/jurnalis_sidebar.php'; ?>
<main class="main">
  <header class="header">
    <h1><?= esc($PAGE_TITLE ?? 'Jurnalis') ?></h1>
    <div class="header-right">
      <div class="chip-role"><?= esc($INITIALS) ?> • <?= esc($NAMA) ?></div>
      <div class="avatar"><?= esc($INITIALS) ?></div>
      <a class="btn btn-outline" href="../logoutadmin.php">Keluar</a>
    </div>
  </header>
