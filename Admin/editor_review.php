<?php
$PAGE_TITLE  = 'Review Artikel';
$ACTIVE_MENU = 'review';
include __DIR__ . '/partials/editor_header.php';

/* Ambil artikel pending/review */
$sql = "SELECT a.id, a.judul, a.status, a.tanggal_dibuat, u.nama_lengkap AS penulis
        FROM articles a
        LEFT JOIN users u ON u.id = a.id_penulis
        WHERE a.status IN ('pending','review')
        ORDER BY a.tanggal_dibuat DESC";
$res = $conn->query($sql);
?>
<section class="card">
  <div class="card-header">
    <h3><i class="fa-regular fa-clipboard"></i> Review Artikel</h3>
  </div>
  <div class="card-body">
    <?php if ($res && $res->num_rows): ?>
      <table class="table">
        <thead>
          <tr>
            <th style="width:40%">Judul</th>
            <th style="width:22%">Penulis</th>
            <th style="width:16%">Status</th>
            <th style="width:14%">Dibuat</th>
            <th style="width:8%">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php while($r = $res->fetch_assoc()): 
            $st = strtolower($r['status']);
            $class = [
              'pending'   => 'badge-pending',
              'review'    => 'badge-review',
              'draft'     => 'badge-draft',
              'published' => 'badge-published',
              'rejected'  => 'badge-rejected',
            ][$st] ?? 'badge-pending';
          ?>
            <tr>
              <td><b><?= esc($r['judul']) ?></b></td>
              <td><?= esc($r['penulis'] ?? '-') ?></td>
              <td><span class="badge-status <?= $class ?>"><?= ucfirst($st) ?></span></td>
              <td><?= date('d M Y H:i', strtotime($r['tanggal_dibuat'])) ?></td>
              <td style="display:flex; gap:8px;">
                <a class="btn btn-sm btn-ghost" href="editor_edit.php?id=<?= (int)$r['id'] ?>">
                  Edit
                </a>
                <a class="btn btn-sm btn-primary"
                  href="../Admin/editor_take.php?id=<?= (int)$r['id'] ?>"
                  onclick="return confirm('Setujui & kirim ke Admin?')">
                  Setujui
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>Tidak ada artikel menunggu review.</p>
    <?php endif; ?>
  </div>
</section>
<?php include __DIR__ . '/partials/editor_footer.php'; ?>
