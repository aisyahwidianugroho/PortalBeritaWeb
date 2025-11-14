<?php
// Admin/partials-jurnalis/jurnalis_guard.php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'jurnalis') {
  header('Location: ../Admin/login.php?err=Silakan login sebagai Jurnalis'); exit;
}
require_once __DIR__ . '/../../koneksi.php';

$USER_ID = (int)($_SESSION['user_id'] ?? 0);
$NAMA    = $_SESSION['nama'] ?? 'Jurnalis';

function scalar($c, string $sql){
  if (!$c) return 0;
  $r = $c->query($sql);
  if (!$r) return 0;
  $row = $r->fetch_row();
  return (int)($row[0] ?? 0);
}
function esc($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

$parts = preg_split('/\s+/', trim($NAMA));
$INITIALS = strtoupper(substr($parts[0] ?? '',0,1) . substr($parts[count($parts)-1] ?? '',0,1));
