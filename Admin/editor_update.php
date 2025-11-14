<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'editor') {
  header("Location: ../login.php?err=Akses ditolak");
  exit;
}

require_once __DIR__ . '/../koneksi.php';

$id     = (int)($_POST['id'] ?? 0);
$judul  = trim($_POST['judul'] ?? '');
$konten = trim($_POST['konten'] ?? '');
$kategori = (int)($_POST['id_kategori'] ?? 0);
$aksi   = $_POST['aksi'] ?? 'save';

// Ambil data lama
$res = $conn->query("SELECT * FROM articles WHERE id=$id");
$old  = $res->fetch_assoc();

if (!$old) {
  die("Artikel tidak ditemukan");
}

// --- Upload Gambar (opsional)
$gambar = $old['gambar_sampul'];
if (!empty($_FILES['gambar']['name'])) {
  $uploadDir = __DIR__ . '/../uploads';
  if (!is_dir($uploadDir)) mkdir($uploadDir);

  $safeName = preg_replace('/[^A-Za-z0-9_\.-]/', '_', basename($_FILES['gambar']['name']));
  $fileName = uniqid('img_', true) . '_' . $safeName;
  $target   = $uploadDir . '/' . $fileName;

  if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
    $gambar = 'uploads/' . $fileName;
  }
}

// Jika editor klik "Setujui"
if ($aksi === 'approve') {
  $status = 'published';
  $tanggal = "tanggal_dipublikasi = NOW(),";
} else {
  $status = 'review';
  $tanggal = "";
}

// Update
$sql = "
  UPDATE articles SET 
    judul = ?,
    konten = ?,
    id_kategori = ?,
    gambar_sampul = ?, 
    $tanggal
    status = ?
  WHERE id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssissi", $judul, $konten, $kategori, $gambar, $status, $id);
$stmt->execute();

header("Location: editor_edit.php?msg=Berhasil diupdate");
exit;
