<?php
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'jurnalis') {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../koneksi.php';

$id          = (int)($_POST['id'] ?? 0);
$judul       = trim($_POST['judul'] ?? '');
$konten      = trim($_POST['konten'] ?? '');
$konten2     = trim($_POST['konten2'] ?? '');
$id_kategori = (int)($_POST['id_kategori'] ?? 0);
$id_penulis  = (int)$_SESSION['user_id'];

$aksi   = $_POST['aksi'] ?? 'draft'; // draft atau kirim

// draft → "draft"
// kirim → "pending"
$status = ($aksi === 'kirim') ? 'pending' : 'draft';

// Ambil data lama & pastikan artikel milik jurnalis
$q_old = $conn->query("SELECT * FROM articles WHERE id = $id AND id_penulis = $id_penulis");
$old   = $q_old->fetch_assoc();

if (!$old) {
    die("Artikel tidak ditemukan atau Anda tidak berhak mengedit.");
}

$gambar = $old['gambar_sampul']; // default: gambar lama

// Jika upload gambar baru
if (!empty($_FILES['gambar']['name'])) {
    $namaBaru = time() . "_" . basename($_FILES['gambar']['name']);
    move_uploaded_file($_FILES['gambar']['tmp_name'], "../uploads/" . $namaBaru);
    $gambar = $namaBaru;
}

// Update artikel (mengganti artikel lama)
$sql = "UPDATE articles SET 
            judul            = '$judul',
            konten           = '$konten',
            konten2          = '$konten2',
            id_kategori      = $id_kategori,
            gambar_sampul    = '$gambar',
            status           = '$status',
            tanggal_diupdate = NOW()
        WHERE id = $id AND id_penulis = $id_penulis";

$conn->query($sql);

// Kembali ke halaman artikel saya
header("Location: jurnalis_saya.php?msg=updated");
exit;

?>
