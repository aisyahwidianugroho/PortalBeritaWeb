<?php
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'editor') {
    header("Location: ../login.php?err=Akses ditolak");
    exit;
}

require_once __DIR__ . '/../koneksi.php';

$id        = (int)($_POST['id'] ?? 0);
$judul     = trim($_POST['judul'] ?? '');
$konten    = trim($_POST['konten'] ?? '');
$kategori  = (int)($_POST['id_kategori'] ?? 0);

// Ambil data lama
$old = $conn->query("SELECT * FROM articles WHERE id = $id")->fetch_assoc();
if (!$old) {
    die("Artikel tidak ditemukan.");
}

// Upload gambar
$gambar = $old['gambar_sampul'];

if (!empty($_FILES['gambar']['name'])) {

    $uploadDir = __DIR__ . '/../uploads';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $safe = preg_replace('/[^A-Za-z0-9_\.-]/', '_', basename($_FILES['gambar']['name']));
    $fname = uniqid('img_', true) . "_" . $safe;

    if (move_uploaded_file($_FILES['gambar']['tmp_name'], "$uploadDir/$fname")) {
        $gambar = "uploads/$fname";
    }
}

// Tentukan status baru
if (isset($_POST['kirim_admin'])) {
    $status = "menunggu_admin";
} else {
    $status = $old['status']; // tidak berubah
}

$stmt = $conn->prepare("
    UPDATE articles SET 
        judul = ?, 
        konten = ?, 
        id_kategori = ?, 
        gambar_sampul = ?, 
        status = ?
    WHERE id = ?
");

$stmt->bind_param(
    "ssissi",
    $judul,
    $konten,
    $kategori,
    $gambar,
    $status,
    $id
);

$stmt->execute();

if ($status === "menunggu_admin") {
    header("Location: editor_review.php?msg=Terkirim+ke+Admin");
} else {
    header("Location: editor_edit.php?msg=Perubahan+Disimpan");
}
exit;
?>
