<?php
require_once '../koneksi.php';

$id   = (int)$_POST['id'];
$nama = trim($_POST['nama_kategori']);
$desk = trim($_POST['deskripsi']);
$stat = $_POST['status'];

mysqli_query($conn,
    "UPDATE categories 
     SET nama_kategori='$nama',
         deskripsi='$desk',
         status='$stat'
     WHERE id=$id"
);

header("Location: admin_dashboard.php?menu=kategori&msg=Kategori+diupdate");
exit;
