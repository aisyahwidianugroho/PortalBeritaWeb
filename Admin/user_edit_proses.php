<?php
require_once "../koneksi.php";

$id = intval($_POST['id']);
$nama = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$role = $_POST['role'];
$status = $_POST['status'];

$sql = "
    UPDATE users 
    SET nama_lengkap='$nama', email='$email', role='$role', status='$status'
    WHERE id=$id
";

if (mysqli_query($conn, $sql)) {
    header("Location: admin_dashboard.php?menu=user&msg=updated");
} else {
    echo "SQL ERROR: " . mysqli_error($conn);
}
