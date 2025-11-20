<?php
session_start();

// Validasi: hanya jurnalis
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'jurnalis') {
    header('Location: ../login.php?err=Silakan login sebagai Jurnalis');
    exit;
}

require_once __DIR__ . '/../koneksi.php';

$USER_ID     = (int)$_SESSION['user_id'];
$ACTIVE_MENU = 'draft';
$PAGE_TITLE  = 'Draft Artikel';

include __DIR__ . '/partials-jurnalis/jurnalis_header.php';

// Ambil semua artikel yang status = draft
$q = $conn->query("
    SELECT id, judul, tanggal_dibuat 
    FROM articles 
    WHERE id_penulis = {$USER_ID}
      AND status = 'draft'
    ORDER BY tanggal_dibuat DESC
");
?>

<section class="card">
  <div class="card-header">
    <h3>Draft Artikel</h3>
  </div>

  <div class="card-body" style="overflow:auto">

    <?php if ($q->num_rows == 0): ?>
      <p style="padding:10px; color:#777;">Belum ada draft artikel.</p>
    <?php else: ?>

    <table style="width:100%; border-collapse:collapse;">
      <thead>
        <tr>
          <th>Judul</th>
          <th>Tanggal Dibuat</th>
          <th style="width:120px; text-align:center;">Aksi</th>
        </tr>
      </thead>

      <tbody>
        <?php while($row = $q->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['judul']) ?></td>

          <td><?= date('d M Y', strtotime($row['tanggal_dibuat'])) ?></td>

          <td style="text-align:center;">
            <a href="jurnalis_edit.php?id=<?= $row['id'] ?>" 
               class="btn-edit"
               style="color:#3498db; text-decoration:none; font-weight:500;">
              Edit
            </a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>

    </table>

    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/partials-jurnalis/jurnalis_footer.php'; ?>
