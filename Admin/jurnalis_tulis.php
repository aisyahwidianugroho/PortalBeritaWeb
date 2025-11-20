<?php
$PAGE_TITLE  = 'Tulis Artikel';
$ACTIVE_MENU = 'tulis';
include __DIR__ . '/partials-jurnalis/jurnalis_header.php';
?>
<section class="card">
  <div class="card-header"><h3>Tulis Artikel Baru</h3></div>
  <div class="card-body">
    <form action="simpan-artikel.php" method="POST" enctype="multipart/form-data">
      <div class="row" style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
        <div>
          <label>Judul</label>
          <input type="text" name="judul" class="input" required>
        </div>
        <div>
          <label>Kategori</label>
          <select name="id_kategori" required class="input">
            <option value="">Pilih...</option>
            <?php
            $cats=$conn->query("SELECT id, nama_kategori FROM categories ORDER BY nama_kategori");
            while($c=$cats->fetch_assoc()): ?>
              <option value="<?= (int)$c['id'] ?>"><?= esc($c['nama_kategori']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>
      </div>

      <div style="margin-top:12px">
        <label>Konten</label>
        <textarea name="konten" rows="10" class="input" required></textarea>
      </div>

      <div style="margin-top:12px">
        <label>Gambar Sampul</label>
        <input type="file" name="gambar" accept="image/*" class="input">
      </div>

      <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:14px">
        <button class="btn btn-outline" name="status" value="draft" type="submit">Simpan Draft</button>
        <button class="btn" name="status" value="review" type="submit">Kirim ke Editor</button>
      </div>
    </form>
  </div>
</section>
<?php include __DIR__ . '/partials-jurnalis/jurnalis_footer.php'; ?>
