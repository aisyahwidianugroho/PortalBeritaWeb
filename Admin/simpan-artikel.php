<?php
session_start();

// hanya jurnalis & editor yang boleh kirim artikel
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['jurnalis', 'editor'])) {
    header("Location: login.php?err=Silakan login sebagai Jurnalis/Editor");
    exit;
}

require_once "../koneksi.php";

// Ambil input
$judul       = trim($_POST['judul'] ?? '');
$konten      = trim($_POST['konten'] ?? '');
$konten2     = trim($_POST['konten2'] ?? '');
$id_kategori = (int)($_POST['id_kategori'] ?? 0);
$id_penulis  = (int)$_SESSION['user_id'];

// VALIDASI WAJIB
if ($judul === '' || $konten === '' || $id_kategori <= 0) {
    header("Location: jurnalis-tulis.php?err=Lengkapi semua field wajib!");
    exit;
}

// ------ UPLOAD GAMBAR (opsional) ------
$gambar_sampul = null;

if (!empty($_FILES['gambar']['name'])) {
    $dir = "../uploads";
    if (!is_dir($dir)) mkdir($dir, 0777, true);

    $safeName   = preg_replace('/[^A-Za-z0-9_\.-]/', '_', basename($_FILES['gambar']['name']));
    $fileName   = uniqid("img_", true) . "_" . $safeName;

    if (move_uploaded_file($_FILES['gambar']['tmp_name'], "$dir/$fileName")) {
        $gambar_sampul = "uploads/$fileName"; 
    }
}

// ------------ STATUS DARI TOMBOL ------------
$status_req = $_POST['status'] ?? 'draft';

if ($status_req === 'draft') {
    $status = 'draft';
} elseif ($status_req === 'pending') {
    $status = 'pending';
} else {
    $status = 'draft';
}

// ---------------- INSERT DATA ----------------
$sql = "INSERT INTO articles 
            (judul, konten, konten2, id_kategori, id_penulis, gambar_sampul, status, tanggal_dibuat)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "sssiiss",
    $judul,
    $konten,
    $konten2,
    $id_kategori,
    $id_penulis,
    $gambar_sampul,
    $status
);

if (mysqli_stmt_execute($stmt)) {

    if ($status === 'draft') {
        header("Location: jurnalis_draft.php?msg=Draft+Berhasil+Disimpan");
    } else {
        header("Location: jurnalis_dashboard.php?msg=Artikel+Berhasil+Dikirim");
    }
    exit;

} else {
    die("Gagal menyimpan artikel: " . mysqli_error($conn));
}
?>
