<?php
include "koneksi.php";

$artikel_id = $_POST['artikel_id'];
$nama = mysqli_real_escape_string($conn, $_POST['nama']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$komentar = mysqli_real_escape_string($conn, $_POST['komentar']);

$sql = "INSERT INTO comments (artikel_id,nama,email,komentar,status)
        VALUES ($artikel_id, '$nama', '$email', '$komentar', 'pending')";

mysqli_query($conn, $sql);

header("Location: detail.php?id=$artikel_id&msg=komentar_terkirim");
exit;
?>
