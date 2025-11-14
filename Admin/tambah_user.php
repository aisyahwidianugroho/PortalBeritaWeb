<?php
session_start();
include "../koneksi.php";

// Hanya admin yang bisa akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $status = "active";

    $sql = "INSERT INTO users (username, password, nama_lengkap, email, role, status)
            VALUES ('$username', '$password', '$nama', '$email', '$role', '$status')";

    if (mysqli_query($conn, $sql)) {
        header("Location: admin_dashboard.php?menu=user&msg=added");
        exit;
    } else {
        $error = "Gagal menambahkan pengguna: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pengguna</title>
    <link rel="stylesheet" href="../CSS/dashboard.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background: #f4f6fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-card {
            width: 520px;
            background: white;
            margin: 50px auto;
            padding: 30px 40px;
            border-radius: 14px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.07);
        }

        h3 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 22px;
            font-weight: 700;
            color: #243447;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
            color: #243447;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #d4d9e2;
            font-size: 14px;
            outline: none;
            transition: .2s;
        }

        .input-group input:focus,
        .input-group select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 2px rgba(52,152,219,0.2);
        }

        .btn-submit {
            padding: 10px 20px;
            background: #3498db;
            border: none;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 15px;
            transition: .2s;
        }

        .btn-submit:hover {
            background: #2c82bd;
        }

        .btn-back {
            padding: 10px 20px;
            background: #e74c3c;
            border: none;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 15px;
            transition: .2s;
            margin-left: 8px;
        }

        .btn-back:hover {
            background: #cf3d2d;
        }

        .btn-wrap {
            margin-top: 20px;
        }
    </style>
</head>

<body>

<div class="form-card">
    <h3>Tambah Pengguna</h3>

    <?php if (isset($error)): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">

        <div class="input-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" required>
        </div>

        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>

        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <div class="input-group">
            <label>Role</label>
            <select name="role" required>
                <option value="jurnalis">Jurnalis</option>
                <option value="editor">Editor</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <div class="btn-wrap">
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Simpan
            </button>

            <a href="admin_dashboard.php?menu=user" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

    </form>
</div>

</body>
</html>
