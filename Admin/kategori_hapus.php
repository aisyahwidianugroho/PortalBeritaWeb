<?php
include "../koneksi.php";

$id = intval($_GET['id']);

// cek apakah kategori dipakai oleh artikel
$cek = mysqli_query($conn, "SELECT * FROM articles WHERE id_kategori = $id");

if (mysqli_num_rows($cek) > 0) {
    die("Kategori tidak bisa dihapus karena masih dipakai artikel!");
}

// aman → hapus
mysqli_query($conn, "DELETE FROM categories WHERE id = $id");
header("Location: admin_dashboard.php?menu=kategori&msg=hapus_sukses");
exit;
?>
