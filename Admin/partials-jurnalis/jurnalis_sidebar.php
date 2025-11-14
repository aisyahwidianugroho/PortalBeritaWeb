<?php
function is_active($key, $active){ return $key === $active ? 'active' : ''; }
?>
<aside class="sidebar">
  <div class="brand">
    <h2>Portal Berita</h2>
    <span class="badge">Jurnalis</span>
  </div>
  <nav class="menu">
    <a class="<?= is_active('dashboard', $ACTIVE_MENU) ?>" href="jurnalis_dashboard.php">
      <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
    </a>
    <a class="<?= is_active('tulis', $ACTIVE_MENU) ?>" href="jurnalis_tulis.php">
      <i class="fas fa-pen"></i><span>Tulis Artikel</span>
    </a>
    <a class="<?= is_active('saya', $ACTIVE_MENU) ?>" href="jurnalis_saya.php">
      <i class="fas fa-newspaper"></i><span>Artikel Saya</span>
    </a>
    <a href="../logoutadmin.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
  </nav>
</aside>
