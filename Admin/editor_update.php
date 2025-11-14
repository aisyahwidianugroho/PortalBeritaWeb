<?php
session_start();

// Hanya editor yang boleh update
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'editor') {
    header("Location: ../login.php?err=Akses ditolak");
    exit;
}

require_once __DIR__ . '/../koneksi.php';

// Ambil data dari form
$id        = (int)($_POST['id'] ?? 0);
$judul     = trim($_POST['judul'] ?? '');
$konten    = trim($_POST['konten'] ?? '');
$konten2   = trim($_POST['konten2'] ?? '');
$kategori  = (int)($_POST['id_kategori'] ?? 0);

// Ambil data lama
$res = $conn->query("SELECT * FROM articles WHERE id = $id");
$old = $res->fetch_assoc();

if (!$old) {
    die("Artikel tidak ditemukan.");
}

// ---- Upload Gambar (opsional) ----
$gambar = $old['gambar_sampul']; // default tetap gambar lama

if (!empty($_FILES['gambar']['name'])) {
    $uploadDir = __DIR__ . '/../uploads';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $safeName = preg_replace('/[^A-Za-z0-9_\.-]/', '_', basename($_FILES['gambar']['name']));
    $fileName = uniqid('img_', true) . '_' . $safeName;

    if (move_uploaded_file($_FILES['gambar']['tmp_name'], "$uploadDir/$fileName")) {
        $gambar = 'uploads/' . $fileName;
    }
}

// ---- Status SELALU pending (editor TIDAK BOLEH publish) ----
$status = "pending";

// ---- Update artikel ----
$sql = "
    UPDATE articles SET 
        judul = ?, 
        konten = ?, 
        konten2 = ?,
        id_kategori = ?, 
        gambar_sampul = ?, 
        status = ?
    WHERE id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssissi", 
    $judul, 
    $konten, 
    $konten2,
    $kategori, 
    $gambar, 
    $status, 
    $id
);

$stmt->execute();

// Redirect balik ke halaman editor
header("Location: editor_edit.php?id=$id&msg=Terkirim+ke+Admin");
exit;
?>
