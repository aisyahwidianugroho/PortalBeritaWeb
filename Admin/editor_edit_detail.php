<?php
$PAGE_TITLE  = 'Edit Artikel (Detail)';
$ACTIVE_MENU = 'edit';
require_once __DIR__ . '/../koneksi.php';
include __DIR__ . '/partials/editor_header.php';

$id = (int)($_GET['id'] ?? 0);

// Ambil artikel
$sql = "SELECT * FROM articles WHERE id = $id LIMIT 1";
$res = $conn->query($sql);
$art = $res->fetch_assoc();

// Ambil kategori
$categories = $conn->query("SELECT id, nama_kategori FROM categories ORDER BY nama_kategori");
?>

<section class="card">
  <div class="card-header"><h3>Edit Artikel</h3></div>
  <div class="card-body">

    <?php if (!$art): ?>
      <p>Artikel tidak ditemukan.</p>

    <?php else: ?>

    <!-- Jika status sudah menunggu_admin / published / rejected, editor TIDAK BOLEH edit -->
    <?php if ($art['status'] !== 'pending' && $art['status'] !== 'review'): ?>
        <p style="color:red">Artikel ini sudah dikirim ke admin / sudah diterbitkan. Tidak bisa diedit lagi.</p>
    <?php else: ?>

    <form action="editor_update.php" method="POST" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:14px">

      <input type="hidden" name="id" value="<?= $art['id'] ?>">

      <!-- Judul -->
      <div>
        <label>Judul</label>
        <input type="text" name="judul" value="<?= htmlspecialchars($art['judul']) ?>" class="input" required>
      </div>

      <!-- Kategori -->
      <div>
        <label>Kategori</label>
        <select name="id_kategori" class="input" required>
          <?php while ($c = $categories->fetch_assoc()): ?>
            <option value="<?= $c['id'] ?>" <?= $c['id']==$art['id_kategori']?'selected':'' ?>>
              <?= htmlspecialchars($c['nama_kategori']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <!-- Konten -->
      <div>
        <label>Konten</label>
        <textarea name="konten" rows="10" class="input"><?= htmlspecialchars($art['konten']) ?></textarea>
      </div>

      <!-- Gambar Sampul -->
      <div>
        <label>Gambar Sampul (Opsional)</label>
        <input type="file" name="gambar" accept="image/*" class="input">
        <?php if ($art['gambar_sampul']): ?>
          <p style="margin-top:6px;">
            <img src="../<?= $art['gambar_sampul'] ?>" style="max-height:120px;border-radius:6px;">
          </p>
        <?php endif; ?>
      </div>

      <!-- Tombol -->
      <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:10px">
        <button class="btn btn-outline" type="submit" name="save_only">
          Simpan Perubahan
        </button>

        <button type="submit" name="kirim_admin" class="btn btn-success">
            Setujui & Kirim ke Admin
        </button>

      </div>
    </form>

    <?php endif; ?>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/partials/editor_footer.php'; ?>
