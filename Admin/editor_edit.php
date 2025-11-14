<?php
$PAGE_TITLE  = 'Edit Artikel';
$ACTIVE_MENU = 'edit';
require_once __DIR__ . '/../koneksi.php';
include __DIR__ . '/partials/editor_header.php';

// ambil artikel yang sedang di-review atau di-edit
$sql = "SELECT id, judul, tanggal_dibuat, status 
        FROM articles 
        WHERE status IN ('review', 'edit')
        ORDER BY tanggal_dibuat DESC";

$q = mysqli_query($conn, $sql);
?>

<section class="card">
  <div class="card-header">
    <h3>Artikel Dalam Proses Edit</h3>
  </div>

  <div class="card-body">

    <?php if (mysqli_num_rows($q) === 0): ?>
        <p>Tidak ada artikel dalam proses edit.</p>

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
                  <?php if ($row['status'] === 'review'): ?>
                      <span class="badge-status badge-review">Review</span>
                  <?php elseif ($row['status'] === 'edit'): ?>
                      <span class="badge-status badge-draft">Edit</span>
                  <?php endif; ?>
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
