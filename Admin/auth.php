<?php
// Admin/auth.php
session_start();
require_once __DIR__ . '/../koneksi.php'; // pastikan $conn (mysqli) ada

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: login.php'); exit;
}

$username = trim((string)($_POST['username'] ?? ''));
$password = (string)($_POST['password'] ?? '');

if ($username === '' || $password === '') {
  header('Location: login.php?err=' . urlencode('Username & password wajib diisi.')); exit;
}

// Ambil user
$sql  = "SELECT id, username, nama_lengkap, role, status, password AS pass
         FROM users WHERE username = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) { header('Location: login.php?err=' . urlencode('Kesalahan server (prepare).')); exit; }
mysqli_stmt_bind_param($stmt, 's', $username);
mysqli_stmt_execute($stmt);
$res  = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($res);

if (!$user) {
  header('Location: login.php?err=' . urlencode('Akun tidak ditemukan.')); exit;
}
if (isset($user['status']) && strtolower($user['status']) !== 'active') {
  header('Location: login.php?err=' . urlencode('Akun non-aktif. Hubungi admin.')); exit;
}

// Verifikasi password
$stored = (string)$user['pass'];
$ok = false;

// hash modern? (bcrypt/argon)
if (preg_match('~^\$2y\$|^\$2a\$|^\$2b\$|^\$argon2(id|i|d)\$~', $stored)) {
  $ok = password_verify($password, $stored);
} else {
  // fallback plaintext (tidak direkomendasikan, tapi aman untuk sementara)
  $ok = hash_equals($stored, $password);
}

if (!$ok) {
  header('Location: login.php?err=' . urlencode('Password salah.')); exit;
}

// Set session
$_SESSION['user_id']  = (int)$user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['nama']     = $user['nama_lengkap'] ?: $user['username'];
$_SESSION['role']     = strtolower($user['role'] ?? '');

// Redirect per role
switch ($_SESSION['role']) {
  case 'jurnalis': header('Location: ../Admin/jurnalis_dashboard.php'); break;
  case 'editor'  : header('Location: ../Admin/editor_dashboard.php');   break;
  case 'admin'   : header('Location: ../Admin/admin_dashboard.php');    break;
  default:
    session_unset(); session_destroy();
    header('Location: login.php?err=' . urlencode('Role tidak dikenali.'));
}
exit;
