<?php
$servername = "localhost"; // Nama host server
$username   = "root";      // Username database
$password   = "";          // Password database (kosong di XAMPP)
$dbname     = "datab_inews"; // Nama database kamu di phpMyAdmin

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
} else {
    echo "Koneksi berhasil!";
}
?>
