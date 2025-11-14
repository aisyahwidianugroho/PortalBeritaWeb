<?php
// Guard untuk semua halaman editor
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'editor') {
  header('Location: ../Admin/login.php?err=Silakan login sebagai Editor'); exit;
}
$NAMA_EDITOR = $_SESSION['nama'] ?? 'Editor';
$parts = preg_split('/\s+/', trim($NAMA_EDITOR));
$EDITOR_INITIALS = strtoupper(substr($parts[0] ?? '',0,1) . substr($parts[count($parts)-1] ?? '',0,1));
