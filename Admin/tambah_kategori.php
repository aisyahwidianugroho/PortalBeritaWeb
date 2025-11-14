<?php
include "../koneksi.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);

    mysqli_query($conn, "INSERT INTO categories (name) VALUES ('$name')");
    header("Location: admin_dashboard.php?menu=kategori&msg=added");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kategori</title>
</head>
<body>
<h2>Tambah Kategori</h2>

<form method="POST">
    <label>Nama Kategori</label><br>
    <input type="text" name="name" required><br><br>

    <button type="submit">Simpan</button>
</form>

</body>
</html>
