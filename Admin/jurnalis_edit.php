<?php
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'jurnalis') {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../koneksi.php';

$id = (int)($_GET['id'] ?? 0);
$id_penulis = (int)$_SESSION['user_id'];

$q = $conn->query("SELECT * FROM articles WHERE id=$id AND id_penulis=$id_penulis");
$artikel = $q->fetch_assoc();

if (!$artikel) {
    die("Artikel tidak ditemukan atau Anda tidak berhak mengedit.");
}

?>

<?php include __DIR__.'/partials-jurnalis/jurnalis_header.php'; ?>

<section class="card">
    <div class="card-header">
        <h3>Edit Artikel</h3>
    </div>

    <div class="card-body">
        <form action="update-artikel.php" method="POST" enctype="multipart/form-data">

            <!-- ID artikel -->
            <input type="hidden" name="id" value="<?= $artikel['id'] ?>">

            <label>Judul</label>
            <input type="text" name="judul" class="form-control"
                   value="<?= htmlspecialchars($artikel['judul']) ?>">

            <label>Kategori</label>
            <select name="id_kategori" class="form-control">
                <?php
                $cat = $conn->query("SELECT * FROM categories");
                while ($c = $cat->fetch_assoc()):
                ?>
                    <option value="<?= $c['id'] ?>" 
                        <?= $artikel['id_kategori'] == $c['id'] ? 'selected' : '' ?>>
                        <?= $c['nama_kategori'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Konten</label>
            <textarea name="konten" class="form-control"><?= htmlspecialchars($artikel['konten']) ?></textarea>

            <label>Konten Tambahan</label>
            <textarea name="konten2" class="form-control"><?= htmlspecialchars($artikel['konten2']) ?></textarea>

            <label>Gambar Sampul (opsional)</label><br>
            <img src="../uploads/<?= $artikel['gambar_sampul'] ?>" height="120"><br><br>
            <input type="file" name="gambar">

            <br><br>
            <button type="submit" name="aksi" value="draft" class="btn btn-warning">Simpan Draft</button>
            <button type="submit" name="aksi" value="kirim" class="btn btn-primary">Kirim ke Editor</button>

        </form>
    </div>
</section>

<?php include __DIR__.'/partials-jurnalis/jurnalis_footer.php'; ?>
