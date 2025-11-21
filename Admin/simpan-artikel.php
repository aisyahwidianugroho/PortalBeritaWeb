<?php
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'jurnalis') {
    header("Location: ../login.php?err=Akses ditolak");
    exit;
}

require_once __DIR__ . '/../koneksi.php';

$id_penulis = $_SESSION['user_id'];

$judul     = trim($_POST['judul'] ?? '');
$konten    = trim($_POST['konten'] ?? '');
$id_kategori = (int)($_POST['id_kategori'] ?? 0);

// Tentukan status berdasarkan tombol
$aksi = $_POST['aksi'] ?? 'draft';

if ($aksi === "kirim") {
    $status = "pending"; // dikirim ke editor
} else {
    $status = "draft";   // simpan sebagai draft
}

// Upload gambar
$gambar_sampul = null;
if (!empty($_FILES['gambar']['name'])) {

    $uploadDir = __DIR__ . '/../uploads';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $safeName = preg_replace('/[^A-Za-z0-9_\.-]/', '_', basename($_FILES['gambar']['name']));
    $fileName = uniqid('img_', true) . "_" . $safeName;

    if (move_uploaded_file($_FILES['gambar']['tmp_name'], "$uploadDir/$fileName")) {
        $gambar_sampul = "uploads/" . $fileName;
    }
}

// Simpan ke DB
$stmt = $conn->prepare("
    INSERT INTO articles (judul, konten, id_kategori, id_penulis, gambar_sampul, status, tanggal_dibuat)
    VALUES (?, ?, ?, ?, ?, ?, NOW())
");

$stmt->bind_param("ssiiss", 
    $judul, 
    $konten, 
    $id_kategori, 
    $id_penulis, 
    $gambar_sampul, 
    $status
);

$stmt->execute();

// Redirect
if ($status === "pending") {
    header("Location: jurnalis_saya.php?msg=Terkirim+ke+Editor");
} else {
    header("Location: jurnalis_draft.php?msg=Draft+disimpan");
}
exit;
?>
