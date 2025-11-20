<?php
include "koneksi.php";

$artikel_id = $_POST['artikel_id'];
$nama       = $_POST['nama'];
$komentar   = $_POST['komentar'];

// Insert komentar
mysqli_query($conn, "
    INSERT INTO comments (artikel_id, nama, komentar, tanggal, status)
    VALUES ('$artikel_id', '$nama', '$komentar', NOW(), 'approved')
");

// Kembali ke halaman artikel
header("Location: detail-isi-berita.php?id=" . $artikel_id);
exit;
?>
