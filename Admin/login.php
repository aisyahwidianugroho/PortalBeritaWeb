<?php
// Admin/login.php
session_start();
if (isset($_SESSION['user_id'])) {
  switch ($_SESSION['role'] ?? '') {
    case 'jurnalis': header('Location: jurnalis_dashboard.php'); exit;
    case 'editor'  : header('Location: editor_dashboard.php');   exit;
    case 'admin'   : header('Location: admin_dashboard.php');    exit;
  }
}
$err = $_GET['err'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portal Berita - Admin Dashboard</title>
  <link rel="stylesheet" href="../CSS/loginadmin.css">
</head>
<body>
  <div class="login-container" id="login-container">
    <div class="login-box">
      <div class="login-header">
        <h1>Portal Berita</h1>
        <p>Admin Dashboard</p>
      </div>
      <div class="login-body">
        <?php if ($err): ?>
          <div style="margin-bottom:12px;padding:10px;border:1px solid #f5c6cb;background:#fdecea;color:#8a1f2d;border-radius:6px">
            <?= htmlspecialchars($err) ?>
          </div>
        <?php endif; ?>

        <!-- HANYA username & password -->
        <form id="login-form" method="POST" action="auth.php" autocomplete="on">
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control"
                   placeholder="Masukkan username" required autofocus>
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control"
                   placeholder="Masukkan password" required>
          </div>

          <button type="submit" class="btn btn-login">Masuk</button>
        </form>

        <!-- Info demo (opsional) -->
        <div class="login-info-box">
          <h4>(password: <code>nama123</code>)</h4>
          <p><strong>Jurnalis:</strong> amira_jurnalis</p>
          <p><strong>Editor:</strong> aisyah_editor</p>
          <p><strong>Admin:</strong> naila_admin</p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
