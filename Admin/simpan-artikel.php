<?php
// Admin/simpan-artikel.php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'jurnalis') {
  header('Location: ../Admin/login.php?err=Silakan login sebagai Jurnalis');
  exit;
}

require_once __DIR__ . '/../koneksi.php';  // <- perbaiki path

// Ambil & sanitasi input sesuai form dan skema tabel
$judul       = trim($_POST['judul'] ?? '');
$konten      = trim($_POST['konten'] ?? '');
$id_kategori = (int)($_POST['id_kategori'] ?? 0);
$status_req  = $_POST['status'] ?? 'draft';
$status      = in_array($status_req, ['draft','pending','review'], true) ? $status_req : 'draft';
$tags        = trim($_POST['tags'] ?? ''); // opsional, boleh kosong
$id_penulis  = (int)$_SESSION['user_id'];

// Validasi sederhana
if ($judul === '' || $konten === '' || $id_kategori <= 0) {
  header('Location: jurnalis-tulis.php?err=Lengkapi+judul,+konten,+dan+kategori');
  exit;
}

// Upload gambar (opsional)
$gambar_sampul = null;
if (!empty($_FILES['gambar']['name'])) {
  $uploadDir = __DIR__ . '/../uploads';
  if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0777, true); }

  $safeName   = preg_replace('/[^A-Za-z0-9_\.-]/', '_', basename($_FILES['gambar']['name']));
  $fileName   = uniqid('img_', true) . '_' . $safeName;
  $targetPath = $uploadDir . '/' . $fileName;

  if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetPath)) {
    // simpan path relatif utk ditampilkan di web
    $gambar_sampul = 'uploads/' . $fileName;
  }
}

// INSERT ke tabel `articles`
$sql = "INSERT INTO articles
          (judul, konten, id_kategori, id_penulis, gambar_sampul, tags, status, tanggal_dibuat)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
  die('Prepare gagal: ' . mysqli_error($conn));
}

mysqli_stmt_bind_param(
  $stmt,
  'ssissss',
  $judul,
  $konten,
  $id_kategori,
  $id_penulis,
  $gambar_sampul,
  $tags,
  $status
);

if (mysqli_stmt_execute($stmt)) {
  // kembali ke dashboard jurnalis dengan pesan
  header('Location: jurnalis_dashboard.php?msg=Artikel+berhasil+disimpan+sebagai+' . urlencode($status));
  exit;
} else {
  die('Gagal menyimpan artikel: ' . mysqli_error($conn));
}
