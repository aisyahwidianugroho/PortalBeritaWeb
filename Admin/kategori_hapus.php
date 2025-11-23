<?php
require_once '../koneksi.php';

$id = (int)$_GET['id'];
mysqli_query($conn, "DELETE FROM categories WHERE id=$id");

header("Location: admin_dashboard.php?menu=kategori&msg=Kategori+dihapus");
exit;
