<?php
$PAGE_TITLE  = 'Artikel Disetujui';
$ACTIVE_MENU = 'approved';
include __DIR__ . '/partials/editor_header.php';

$sql = "SELECT a.id, a.judul, a.tanggal_publish, a.views, u.nama_lengkap AS penulis
        FROM articles a
        LEFT JOIN users u ON u.id = a.id_penulis
        WHERE a.status='published'
        ORDER BY COALESCE(a.tanggal_publish, a.tanggal_dibuat) DESC";

$res = $conn->query($sql);
?>
<section class="card">
  <div class="card-header"><h3>Artikel Disetujui</h3></div>
  <div class="card-body">
    <?php if ($res && $res->num_rows): ?>
      <ul style="margin:0;padding-left:18px;line-height:1.9">
        <?php while($r=$res->fetch_assoc()): ?>
          <li>
            <b><?= esc($r['judul']) ?></b>
            <span style="color:#6b7280;margin-left:8px">
              oleh <?= esc($r['penulis'] ?? '-') ?>
            </span>

            <span style="color:#6b7280;margin-left:8px">
              <?= date('d M Y', strtotime($r['tanggal_publish'] ?? 'now')) ?>
            </span>

            <span style="color:#6b7280;margin-left:8px">
              <i class="fa-regular fa-eye"></i> <?= (int)($r['views'] ?? 0) ?>
            </span>
          </li>
        <?php endwhile; ?>
      </ul>
    <?php else: ?>
      <p>Belum ada artikel yang dipublikasikan.</p>
    <?php endif; ?>
  </div>
</section>
<?php include __DIR__ . '/partials/editor_footer.php'; ?>
