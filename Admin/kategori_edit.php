<?php
require_once '../koneksi.php';

// Ambil ID kategori
$id = (int)($_GET['id'] ?? 0);

// Ambil data kategori
$q = mysqli_query($conn, "SELECT * FROM categories WHERE id=$id");
$kat = mysqli_fetch_assoc($q);

if (!$kat) {
    die("Kategori tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kategori</title>

    <link rel="stylesheet" href="../CSS/admin_dashboard.css">
    <link rel="stylesheet" href="../CSS/dashboard.css">
</head>
<body style="padding:30px;">

    <div class="card" style="max-width:500px; margin:0 auto;">
        <h2 class="section-title">Edit Kategori</h2>

        <form action="kategori_edit_proses.php" method="POST" style="display:flex; flex-direction:column; gap:12px;">
            <input type="hidden" name="id" value="<?= $kat['id'] ?>">

            <label>Nama Kategori</label>
            <input type="text" name="nama_kategori" value="<?= htmlspecialchars($kat['nama_kategori']) ?>"
                   style="padding:10px; border-radius:8px; border:1px solid #cbd5e1;" required>

            <label>Deskripsi (opsional)</label>
            <textarea name="deskripsi" style="padding:10px; border-radius:8px; border:1px solid #cbd5e1; height:100px;">
<?= htmlspecialchars($kat['deskripsi'] ?? '') ?>
            </textarea>

            <label>Status</label>
            <select name="status" style="padding:10px; border-radius:8px; border:1px solid #cbd5e1;">
                <option value="active"   <?= ($kat['status'] == 'active') ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= ($kat['status'] == 'inactive') ? 'selected' : '' ?>>Inactive</option>
            </select>

            <button class="btn-action btn-publish" style="margin-top:10px;">Simpan Perubahan</button>
        </form>
    </div>

</body>
</html>
