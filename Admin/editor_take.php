<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'editor') { header('Location: login.php'); exit; }
require_once __DIR__ . '/../koneksi.php';

$id = (int)($_POST['id'] ?? 0);
$editor_id = (int)$_SESSION['user_id'];

if ($id > 0) {
  $stmt = $conn->prepare("UPDATE articles SET status='review', id_editor=? WHERE id=?");
  $stmt->bind_param('ii', $editor_id, $id);
  $stmt->execute();
}
header('Location: editor_review.php?msg=' . urlencode('Artikel diambil untuk proses edit'));
