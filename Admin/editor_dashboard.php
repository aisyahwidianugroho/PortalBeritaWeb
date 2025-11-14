<?php
$PAGE_TITLE  = 'Dashboard Editor';
$ACTIVE_MENU = 'dashboard';
require_once __DIR__ . '/../koneksi.php';   // <— Tambahkan baris ini
include __DIR__ . '/partials/editor_header.php';

// ------ Helper ringkas ------
function scalar(mysqli $c, string $sql){
  $r = $c->query($sql);
  if(!$r){ return 0; }
  $row = $r->fetch_row();
  return (int)($row[0] ?? 0);
}
function compact_number($n){
  if($n >= 1000000) return round($n/1000000,1).'M';
  if($n >= 1000)    return round($n/1000,1).'K';
  return (string)$n;
}

// ------ Nama tabel & kolom sesuai DB kamu ------
$TABLE    = 'articles';
$DATE_PUB = 'tanggal_dipublikasi';
$DATE_CR  = 'tanggal_dibuat';

// ------ COUNTER dari DB ------
$qty_review   = scalar($conn, "SELECT COUNT(*) FROM $TABLE WHERE status IN ('pending','review')");
$qty_in_edit  = scalar($conn, "SELECT COUNT(*) FROM $TABLE WHERE status='review'");
$qty_approved = scalar($conn, "SELECT COUNT(*) FROM $TABLE WHERE status='published'");
$total_views  = scalar($conn, "SELECT COALESCE(SUM(views),0) FROM $TABLE WHERE status='published'");

// ------ DATA GRAFIK (12 bulan terakhir) ------
// gunakan tanggal_dipublikasi kalau ada; jika NULL, pakai tanggal_dibuat
$sql = "
  SELECT
    DATE_FORMAT(COALESCE($DATE_PUB, $DATE_CR), '%Y-%m') ym,
    DATE_FORMAT(COALESCE($DATE_PUB, $DATE_CR), '%b') label,
    YEAR(COALESCE($DATE_PUB, $DATE_CR)) y,
    MONTH(COALESCE($DATE_PUB, $DATE_CR)) m,
    COUNT(*) cnt,
    SUM(views) sum_views
  FROM $TABLE
  WHERE COALESCE($DATE_PUB, $DATE_CR) >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH)
    AND status='published'
  GROUP BY y, m
  ORDER BY y, m;
";
$res = $conn->query($sql);
$mapCnt  = [];
$mapView = [];
if($res){
  while($row = $res->fetch_assoc()){
    $key = sprintf('%04d-%02d', $row['y'], $row['m']);
    $mapCnt[$key]  = (int)$row['cnt'];
    $mapView[$key] = (int)$row['sum_views'];
  }
}

// susun 12 bulan rolling
$labels = []; $seriesArtikel = []; $seriesReaders = [];
$start = new DateTime(date('Y-m-01', strtotime('-11 months')));
for($i=0;$i<12;$i++){
  $key = $start->format('Y-m');
  $labels[]        = $start->format('M');
  $seriesArtikel[] = (int)($mapCnt[$key]  ?? 0);
  $seriesReaders[] = (int)($mapView[$key] ?? 0);
  $start->modify('+1 month');
}
?>

<!-- ===== COUNTERS ===== -->
<section class="stat" style="margin-top:18px">
  <div class="item">
    <div style="font-size:26px;font-weight:800;margin-bottom:6px;color:var(--role-color)"><?= number_format($qty_review) ?></div>
    <div style="color:#6b7280;font-weight:600">Perlu Review</div>
  </div>
  <div class="item">
    <div style="font-size:26px;font-weight:800;margin-bottom:6px;color:var(--role-color)"><?= number_format($qty_in_edit) ?></div>
    <div style="color:#6b7280;font-weight:600">Dalam Edit</div>
  </div>
  <div class="item">
    <div style="font-size:26px;font-weight:800;margin-bottom:6px;color:var(--role-color)"><?= number_format($qty_approved) ?></div>
    <div style="color:#6b7280;font-weight:600">Disetujui</div>
  </div>
  <div class="item">
    <div style="font-size:26px;font-weight:800;margin-bottom:6px;color:var(--role-color)"><?= htmlspecialchars(compact_number($total_views)) ?></div>
    <div style="color:#6b7280;font-weight:600">Total Pembaca</div>
  </div>
</section>

<!-- ===== GRAFIK STATISTIK (tanpa card daftar review) ===== -->
<section class="card">
  <div class="card-header"><h3>Statistik Editor</h3></div>
  <div class="card-body">
    <div class="charts-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:18px">
      <div class="chart-box" style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:12px">
        <canvas id="chartStatus" height="120"></canvas>
      </div>
      <div class="chart-box" style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:12px">
        <canvas id="chartReaders" height="120"></canvas>
      </div>
    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // status
  const pending   = <?= (int)$qty_review   ?>;
  const inEdit    = <?= (int)$qty_in_edit  ?>;
  const approved  = <?= (int)$qty_approved ?>;
  new Chart(document.getElementById('chartStatus').getContext('2d'),{
    type:'bar',
    data:{ labels:['Perlu Review','Dalam Edit','Disetujui'],
      datasets:[{ label:'Jumlah Artikel', data:[pending,inEdit,approved] }] },
    options:{ plugins:{legend:{display:false}}, scales:{ y:{beginAtZero:true, ticks:{precision:0}}}}
  });

  // readers per bulan (dari SUM(views) published)
  const labels  = <?= json_encode($labels) ?>;
  const readers = <?= json_encode($seriesReaders, JSON_NUMERIC_CHECK) ?>;
  new Chart(document.getElementById('chartReaders').getContext('2d'),{
    type:'line',
    data:{ labels, datasets:[{ label:'Pembaca / Bulan', data: readers, tension:.35, fill:true }] },
    options:{ plugins:{legend:{display:false}}, scales:{ y:{beginAtZero:true}}}
  });
</script>

<?php include __DIR__ . '/partials/editor_footer.php'; ?>
