<?php
// gunakan $ACTIVE_MENU dari header
function is_active(string $key, string $active): string { return $key === $active ? 'active' : ''; }
?>
<nav class="menu">
  <a class="<?= is_active('dashboard', $ACTIVE_MENU) ?>" href="editor_dashboard.php">
    <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
  </a>
  <a class="<?= is_active('review', $ACTIVE_MENU) ?>" href="editor_review.php">
    <i class="fas fa-tasks"></i><span>Review Artikel</span>
  </a>
  <a class="<?= is_active('edit', $ACTIVE_MENU) ?>" href="editor_edit.php">
    <i class="fas fa-edit"></i><span>Edit Artikel</span>
  </a>
  <a class="<?= is_active('approved', $ACTIVE_MENU) ?>" href="editor_approved.php">
    <i class="fas fa-check-circle"></i><span>Artikel Disetujui</span>
  </a>
  <a href="../logoutadmin.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
</nav>
