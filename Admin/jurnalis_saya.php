<?php
$PAGE_TITLE  = 'Artikel Saya';
$ACTIVE_MENU = 'saya';
include __DIR__ . '/partials-jurnalis/jurnalis_header.php';

$page = max(1,(int)($_GET['p'] ?? 1)); 
$per = 10; 
$off = ($page-1) * $per;

$total = scalar($conn, "SELECT COUNT(*) FROM articles WHERE id_penulis={$USER_ID}");

$q = $conn->query("
    SELECT a.id, a.judul, a.status, a.tanggal_dibuat, a.views, c.nama_kategori
    FROM articles a
    LEFT JOIN categories c ON c.id = a.id_kategori
    WHERE a.id_penulis = {$USER_ID}
    ORDER BY a.tanggal_dibuat DESC
    LIMIT {$per} OFFSET {$off}
");
?>
<section class="card">
  <div class="card-header"><h3>Artikel Saya</h3></div>
  <div class="card-body" style="overflow:auto">

    <table style="width:100%;border-collapse:collapse">

      <!-- PERBAIKAN: HEADER TABEL TIDAK BOLEH MENGGUNAKAN $r -->
      <thead>
        <tr>
          <th style="padding:10px;border-bottom:1px solid #f1f5f9">Judul</th>
          <th style="padding:10px;border-bottom:1px solid #f1f5f9">Kategori</th>
          <th style="padding:10px;border-bottom:1px solid #f1f5f9">Status</th>
          <th style="padding:10px;border-bottom:1px solid #f1f5f9">Tanggal Dibuat</th>
          <th style="padding:10px;border-bottom:1px solid #f1f5f9">Views</th>
          <th style="padding:10px;border-bottom:1px solid #f1f5f9">Aksi</th>
        </tr>
      </thead>

      <tbody>
      <?php if ($q && $q->num_rows > 0): ?>
          <?php while ($r = $q->fetch_assoc()): ?>
              <tr>
                  <td style="padding:10px;border-bottom:1px solid #f1f5f9;">
                      <?= htmlspecialchars($r['judul']) ?>
                  </td>

                  <td style="padding:10px;border-bottom:1px solid #f1f5f9;">
                      <?= htmlspecialchars($r['nama_kategori'] ?? '-') ?>
                  </td>

                  <td style="padding:10px;border-bottom:1px solid #f1f5f9;">
                      <?= ucfirst($r['status']) ?>
                  </td>

                  <td style="padding:10px;border-bottom:1px solid #f1f5f9;">
                      <?= date('d M Y', strtotime($r['tanggal_dibuat'])) ?>
                  </td>

                  <td style="padding:10px;border-bottom:1px solid #f1f5f9;">
                      <?= (int)$r['views'] ?>
                  </td>

                  <!-- Kolom Aksi -->
                  <td style="padding:10px;border-bottom:1px solid #f1f5f9;">
                      <?php if ($r['status'] === 'draft'): ?>
                          <a href="jurnalis_edit.php?id=<?= $r['id']; ?>" 
                             class="btn btn-warning btn-sm">
                             Edit Draft
                          </a>
                      <?php elseif ($r['status'] === 'pending'): ?>
                          <span style="color:#64748b;">Menunggu Editor</span>
                      <?php elseif ($r['status'] === 'published'): ?>
                          <span style="color:#22c55e;">✔ Sudah Terbit</span>
                      <?php else: ?>
                          -
                      <?php endif; ?>
                  </td>
              </tr>
          <?php endwhile; ?>
      <?php else: ?>
          <tr>
              <td colspan="6">Tidak ada artikel.</td>
          </tr>
      <?php endif; ?>
      </tbody>

    </table>

    <?php
    $pages = max(1, ceil($total / $per));
    if ($pages > 1){
      echo '<div style="margin-top:12px;display:flex;gap:6px">';
      for($i=1; $i <= $pages; $i++){
        $act = $i==$page ? 
          'background:var(--role-color);color:#fff' :
          'background:#fff;color:#374151;border:1px solid #e5e7eb';

        echo '<a style="padding:6px 10px;border-radius:8px;'.$act.'" href="?p='.$i.'">'.$i.'</a>';
      }
      echo '</div>';
    }
    ?>
  </div>
</section>

<?php include __DIR__ . '/partials-jurnalis/jurnalis_footer.php'; ?>
