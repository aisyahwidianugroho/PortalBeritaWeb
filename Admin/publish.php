<?php
include "../koneksi.php";
session_start();

$id = $_GET['id'];

// Ambil data artikel dari tabel articles
$q = mysqli_query($conn, "SELECT * FROM articles WHERE id='$id'");
$data = mysqli_fetch_assoc($q);

// Pindahkan ke tabel berita
mysqli_query($conn, "INSERT INTO berita (judul, kategori, tags, isi, gambar, status, tanggal)
VALUES (
    '".$data['judul']."',
    '".$data['id_kategori']."',
    '".$data['tags']."',
    '".$data['konten']."',
    '".$data['gambar_sampul']."',
    'dipublikasikan',
    NOW()
)");

// Hapus dari tabel articles
mysqli_query($conn, "DELETE FROM articles WHERE id='$id'");

header("Location: admin_dashboard.php?menu=berita_masuk&msg=published");
exit;
?>
