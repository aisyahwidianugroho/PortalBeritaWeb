<?php
include "koneksi.php";

// Tangkap data
$artikel_id = (int)$_POST['artikel_id'];
$nama       = mysqli_real_escape_string($conn, $_POST['nama']);
$komentar   = mysqli_real_escape_string($conn, $_POST['komentar']);

// Simpan komentar
mysqli_query($conn, "
    INSERT INTO comments (artikel_id, nama, komentar, tanggal, status)
    VALUES ($artikel_id, '$nama', '$komentar', NOW(), 'approved')
");

// Redirect kembali
header("Location: detail-isi-berita.php?id=$artikel_id");
exit;
?>
