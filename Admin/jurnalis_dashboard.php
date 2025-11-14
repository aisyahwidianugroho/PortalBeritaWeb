<?php
$PAGE_TITLE  = 'Dashboard Jurnalis';
$ACTIVE_MENU = 'dashboard';
include __DIR__ . '/partials-jurnalis/jurnalis_header.php';

$draft     = scalar($conn, "SELECT COUNT(*) FROM articles WHERE id_penulis={$USER_ID} AND status='draft'");
$pending   = scalar($conn, "SELECT COUNT(*) FROM articles WHERE id_penulis={$USER_ID} AND status='pending'");
$published = scalar($conn, "SELECT COUNT(*) FROM articles WHERE id_penulis={$USER_ID} AND status='published'");
$views     = scalar($conn, "SELECT COALESCE(SUM(views),0) FROM articles WHERE id_penulis={$USER_ID}");
?>
<section class="stat" style="margin-top:18px">
  <div class="item"><div style="font:800 26px/1 Segoe UI;color:var(--role-color)"><?= $draft ?></div><div style="color:#6b7280;font-weight:600">Draft</div></div>
  <div class="item"><div style="font:800 26px/1 Segoe UI;color:var(--role-color)"><?= $pending ?></div><div style="color:#6b7280;font-weight:600">Menunggu Review</div></div>
  <div class="item"><div style="font:800 26px/1 Segoe UI;color:var(--role-color)"><?= $published ?></div><div style="color:#6b7280;font-weight:600">Dipublikasikan</div></div>
  <div class="item"><div style="font:800 26px/1 Segoe UI;color:var(--role-color)"><?= number_format($views) ?></div><div style="color:#6b7280;font-weight:600">Total Pembaca</div></div>
</section>

<section class="card">
  <div class="card-header">
    <h3>Artikel Terbaru Saya</h3>
    <a href="jurnalis_saya.php" class="btn btn-outline" style="padding:8px 12px"><i class="fa-solid fa-list"></i> Lihat Semua</a>
  </div>
  <div class="card-body">
    <?php
    $q = $conn->query("SELECT id, judul, status, tanggal_dibuat, views
                       FROM articles
                       WHERE id_penulis={$USER_ID}
                       ORDER BY tanggal_dibuat DESC
                       LIMIT 5");
    if ($q && $q->num_rows):
      echo '<ul style="margin:0;padding-left:18px;line-height:1.9">';
      while($r=$q->fetch_assoc()):
        $status = strtolower($r['status']);
        $badge = [
          'draft'=>'background:#f3f4f6;color:#374151',
          'pending'=>'background:#fff7ed;color:#9a3412',
          'review'=>'background:#eef2ff;color:#3730a3',
          'published'=>'background:#ecfdf5;color:#065f46',
          'rejected'=>'background:#fef2f2;color:#991b1b'
        ][$status] ?? 'background:#f3f4f6;color:#374151';
        ?>
        <li>
          <b><?= esc($r['judul']) ?></b>
          <span style="margin-left:8px;padding:2px 8px;border-radius:999px;font-size:.75rem;<?= $badge ?>">
            <?= esc(ucfirst($r['status'])) ?>
          </span>
          <span style="color:#6b7280;margin-left:8px"><?= date('d M Y', strtotime($r['tanggal_dibuat'])) ?></span>
          <span style="color:#6b7280;margin-left:8px"><i class="fa-regular fa-eye"></i> <?= (int)$r['views'] ?></span>
        </li>
      <?php endwhile; echo '</ul>';
    else:
      echo '<p>Tidak ada artikel.</p>';
    endif; ?>
  </div>
</section>
<?php include __DIR__ . '/partials-jurnalis/jurnalis_footer.php'; ?>
