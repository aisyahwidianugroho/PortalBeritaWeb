<?php
$PAGE_TITLE  = 'Edit Artikel';
$ACTIVE_MENU = 'edit';
include __DIR__ . '/partials/editor_header.php';
?>
<section class="card">
  <div class="card-header"><h3>Artikel Dalam Proses Edit</h3></div>
  <div class="card-body">
    <!-- TODO: render artikel yang statusnya 'review' atau sedang di-edit -->
    <p>Daftar artikel saat ini dalam proses edit.</p>
  </div>
</section>
<?php include __DIR__ . '/partials/editor_footer.php'; ?>
