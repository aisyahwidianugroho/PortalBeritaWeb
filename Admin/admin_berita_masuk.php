<?php
include "../koneksi.php";

$sql = "SELECT a.id, a.judul, a.tanggal_dibuat, u.nama_lengkap AS penulis
        FROM articles a
        LEFT JOIN users u ON u.id = a.id_penulis
        WHERE a.status = 'menunggu_admin'
        ORDER BY a.tanggal_dibuat DESC";

$res = $conn->query($sql);
?>

<section class="card">
  <div class="card-header">
    <h3><i class="fas fa-inbox"></i> Data Berita Masuk</h3>
  </div>

  <div class="card-body">
    <?php if ($res && $res->num_rows): ?>
      <table class="table">
        <thead>
          <tr>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Dibuat</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php while($r = $res->fetch_assoc()): ?>
            <tr>
              <td><b><?= htmlspecialchars($r['judul']) ?></b></td>
              <td><?= htmlspecialchars($r['penulis']) ?></td>
              <td><?= date('d M Y H:i', strtotime($r['tanggal_dibuat'])) ?></td>
              <td>
                <a class="btn btn-sm btn-primary"
                   href="publish.php?id=<?= $r['id'] ?>"
                   onclick="return confirm('Publish artikel ini?')">
                  Publish
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>Tidak ada berita menunggu admin.</p>
    <?php endif; ?>
  </div>
</section>
