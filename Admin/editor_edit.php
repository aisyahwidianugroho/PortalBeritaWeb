<?php
$PAGE_TITLE  = 'Edit Artikel';
$ACTIVE_MENU = 'edit';
require_once __DIR__ . '/../koneksi.php';
include __DIR__ . '/partials/editor_header.php';

// Ambil artikel PENDING (dari jurnalis)
$sql = "SELECT id, judul, tanggal_dibuat, status 
        FROM articles 
        WHERE status = 'pending'
        ORDER BY tanggal_dibuat DESC";

$q = mysqli_query($conn, $sql);
?>

<section class="card">
  <div class="card-header">
    <h3>Artikel Dalam Proses Edit</h3>
  </div>

  <div class="card-body">

    <?php if (mysqli_num_rows($q) === 0): ?>
        <p>Tidak ada artikel untuk diedit.</p>

    <?php else: ?>
        <table class="table">
            <thead>
              <tr>
                <th>Judul</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
              </tr>
            </thead>

            <tbody>
            <?php while($row = mysqli_fetch_assoc($q)): ?>
              <tr>
                <td><?= htmlspecialchars($row['judul']) ?></td>

                <td>
                    <span class="badge-status badge-pending">Pending</span>
                </td>

                <td><?= date("d M Y", strtotime($row['tanggal_dibuat'])) ?></td>

                <td>
                  <a class="btn btn-sm btn-primary" 
                     href="editor_edit_detail.php?id=<?= $row['id'] ?>">
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

<?php include __DIR__ . '/partials/editor_footer.php'; ?>
