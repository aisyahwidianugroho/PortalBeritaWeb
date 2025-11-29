<?php
include "../koneksi.php";

if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php?menu=riwayat");
    exit;
}

$id = (int) $_GET['id'];

$query = mysqli_query($conn, "DELETE FROM articles WHERE id=$id");

if ($query) {
    echo "<script>
            alert('Artikel berhasil dihapus.');
            window.location='admin_dashboard.php?menu=riwayat';
          </script>";
} else {
    echo "<script>
            alert('Gagal menghapus artikel.');
            window.location='admin_dashboard.php?menu=riwayat';
          </script>";
}
?>
