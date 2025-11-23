<?php
require_once '../koneksi.php';

$nama = trim($_POST['nama_kategori']);

mysqli_query($conn, 
    "INSERT INTO categories (nama_kategori, deskripsi, status)
     VALUES ('$nama', '', 'active')"
);

header("Location: admin_dashboard.php?menu=kategori&msg=Kategori+ditambah");
exit;
