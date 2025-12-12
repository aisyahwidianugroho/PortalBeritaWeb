<?php
$error_rating = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_rating'])) {
  $nama   = trim($_POST['nama'] ?? '');
  $rating = intval($_POST['rating'] ?? 0);

  if ($nama === '' || mb_strlen($nama) > 80 || strtolower($nama) === 'anonymous') {
    $error_rating = "Nama wajib diisi (maks 80 karakter dan jangan 'Anonymous').";
  } elseif ($rating < 1 || $rating > 5) {
    $error_rating = "Pilih rating 1 sampai 5 ya.";
  } else {
    $stmt = mysqli_prepare($conn, "INSERT INTO rating_website (nama, rating) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "si", $nama, $rating);

    if (mysqli_stmt_execute($stmt)) {
      mysqli_stmt_close($stmt);
      header("Location: " . strtok($_SERVER['REQUEST_URI'], '#')); // reload halaman yg sama
      exit;
    } else {
      $error_rating = "Gagal menyimpan rating: " . mysqli_error($conn);
      mysqli_stmt_close($stmt);
    }
  }
}
?>

<style>
:root{
  --brand:#2563eb;
  --brand2:#1d4ed8;
  --muted:#64748b;
  --line:#e2e8f0;
}

#tsRatingModalWrap{
  position:fixed;
  inset:0;
  display:none;
  align-items:center;
  justify-content:center;
  padding:16px;
  background:rgba(15,23,42,.52);
  backdrop-filter:blur(10px);
  z-index:999999;
}

#tsRatingModal{
  width:min(540px,94vw);
  border-radius:22px;
  padding:22px 20px 18px;
  position:relative;
  overflow:hidden;
  background:linear-gradient(180deg,#fff 0%,#fbfdff 100%);
  border:1px solid rgba(226,232,240,.95);
  box-shadow:
    0 28px 70px rgba(2,6,23,.28),
    0 2px 0 rgba(255,255,255,.7) inset;
  animation:pop .18s ease-out;
}

@keyframes pop{
  from{
    transform:translateY(10px) scale(.98);
    opacity:0;
  }
  to{
    transform:translateY(0) scale(1);
    opacity:1;
  }
}

#tsRatingModal::before{
  content:"";
  position:absolute;
  width:320px;
  height:320px;
  border-radius:999px;
  top:-170px;
  left:-170px;
  background:radial-gradient(
    circle at 30% 30%,
    rgba(37,99,235,.22),
    rgba(37,99,235,0) 60%
  );
}

#tsRatingClose{
  position:absolute;
  top:12px;
  right:12px;
  width:40px;
  height:40px;
  border-radius:14px;
  display:grid;
  place-items:center;
  cursor:pointer;
  border:1px solid rgba(226,232,240,.95);
  background:rgba(248,250,252,.92);
  color:#334155;
  transition:.15s;
  font-size:18px;
  user-select:none;
}

#tsRatingClose:hover{
  transform:translateY(-1px);
  background:#fff;
  color:#0f172a;
}

.tsBadge{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding:7px 12px;
  border-radius:999px;
  font-size:12px;
  font-weight:700;
  color:var(--brand2);
  background:rgba(37,99,235,.10);
  border:1px solid rgba(37,99,235,.18);
  margin-bottom:10px;
}

.tsTitle{
  margin:0;
  font-size:clamp(18px,2.6vw,22px);
  font-weight:900;
  line-height:1.2;
}

.tsSub{
  margin:8px 0 0;
  font-size:clamp(12.5px,2.2vw,13.5px);
  line-height:1.45;
  color:var(--muted);
}

.tsErr{
  margin-top:12px;
  padding:10px 12px;
  border-radius:14px;
  background:#ef4444;
  color:#fff;
  font-size:13px;
}

.tsInput{
  width:100%;
  margin-top:14px;
  padding:13px 14px;
  border-radius:14px;
  border:1px solid var(--line);
  outline:none;
  background:#fff;
  font-size:14px;
  transition:.15s;
}

.tsInput:focus{
  border-color:rgba(37,99,235,.45);
  box-shadow:0 0 0 4px rgba(37,99,235,.12);
}

.tsStars{
  display:flex;
  flex-direction:row-reverse;
  justify-content:center;
  gap:12px;
  margin:16px 0 6px;
  touch-action:manipulation;
}

.tsStars input{
  display:none;
}

.tsStars label{
  font-size:clamp(34px,8vw,44px);
  cursor:pointer;
  color:#cbd5e1;
  transition:.15s;
  user-select:none;
  -webkit-tap-highlight-color:transparent;
}

.tsStars input:checked ~ label,
.tsStars label:hover,
.tsStars label:hover ~ label{
  color:#fbbf24 !important;
  filter:drop-shadow(0 10px 20px rgba(251,191,36,.28));
}

.tsSelected{
  text-align:center;
  margin:0;
  font-size:13px;
  color:#334155;
  min-height:18px;
}

.tsSelected strong{
  color:var(--brand2);
}

.tsBtn{
  width:100%;
  margin-top:14px;
  padding:13px 18px;
  border:none;
  border-radius:14px;
  font-size:15px;
  font-weight:800;
  cursor:pointer;
  color:#fff;
  background:linear-gradient(180deg,var(--brand) 0%,var(--brand2) 100%);
  box-shadow:0 14px 32px rgba(37,99,235,.26);
  transition:.15s;
}

.tsBtn:disabled{
  background:#cbd5e1;
  cursor:not-allowed;
  box-shadow:none;
}
</style>

<div id="tsRatingModalWrap" aria-hidden="true">
  <div id="tsRatingModal" role="dialog" aria-modal="true" aria-label="Form rating website">
    <span id="tsRatingClose" onclick="tsRatingClose()">✕</span>

    <div style="position:relative;text-align:left;margin-bottom:12px">
      <div class="tsBadge">⭐ Feedback</div>
      <h2 class="tsTitle">Gimana pengalaman kamu di portal ini?</h2>
      <p class="tsSub">Kasih rating biar kami bisa bikin the Surabaya iNews makin enak dibaca ✨</p>
    </div>

    <?php if ($error_rating): ?>
      <div class="tsErr"><?= htmlspecialchars($error_rating) ?></div>
    <?php endif; ?>

    <form method="POST" id="tsRatingForm" onsubmit="return tsRatingSubmit();">
      <input type="hidden" name="submit_rating" value="1">

      <input class="tsInput" id="tsName" type="text" name="nama"
             placeholder="Tulis nama kamu..." required maxlength="80" autocomplete="name">

      <div class="tsStars" aria-label="Rating 1 sampai 5">
        <input type="radio" id="tsStar5" name="rating" value="5" required><label for="tsStar5">★</label>
        <input type="radio" id="tsStar4" name="rating" value="4"><label for="tsStar4">★</label>
        <input type="radio" id="tsStar3" name="rating" value="3"><label for="tsStar3">★</label>
        <input type="radio" id="tsStar2" name="rating" value="2"><label for="tsStar2">★</label>
        <input type="radio" id="tsStar1" name="rating" value="1"><label for="tsStar1">★</label>
      </div>

      <p class="tsSelected" id="tsSelected">Isi nama & pilih bintang dulu ya 🙂</p>
      <button class="tsBtn" id="tsBtn" type="submit" disabled>Kirim Rating</button>
    </form>
  </div>
</div>

<script>
(function(){
  const wrap = document.getElementById("tsRatingModalWrap");
  if (!wrap) return;

  const DEMO_MODE = true;

  const pageKey = (location.pathname + location.search).replace(/[^a-z0-9]/gi,'_').slice(0,120);
  const ratedKey = "ts_rated_" + pageKey;
  const shownKey = "ts_shown_" + pageKey;

  const hasError = <?= $error_rating ? 'true' : 'false' ?>;

  function openModal(){
    wrap.style.display = "flex";
    wrap.setAttribute("aria-hidden","false");
    const inp = document.getElementById("tsName");
    if (inp) setTimeout(() => inp.focus(), 50);
  }

  function canShow(){
    if (DEMO_MODE) return true;
    const rated = localStorage.getItem(ratedKey) === "1";
    const shown = sessionStorage.getItem(shownKey) === "1";
    return !rated && !shown;
  }

  function showModal(){
    if (!canShow()) return;
    if (!DEMO_MODE) sessionStorage.setItem(shownKey, "1");
    openModal();
  }

  function getScrollPercent(){
    const el = document.scrollingElement || document.documentElement;
    const scrollTop = el.scrollTop || 0;
    const scrollHeight = (el.scrollHeight || 0) - (el.clientHeight || 0);
    if (scrollHeight <= 0) return 0;
    return (scrollTop / scrollHeight) * 100;
  }

  let fired = false;
  function maybeShow(){
    if (fired) return;
    if (getScrollPercent() >= 60){
      fired = true;
      showModal();
    }
  }

  // trigger utama
  window.addEventListener("scroll", maybeShow, { passive:true });
  document.addEventListener("scroll", maybeShow, { passive:true, capture:true });

  // fallback pasti muncul (buat halaman yg scroll-nya pendek / desktop)
  setTimeout(() => {
    if (fired) return;
    fired = true;
    showModal();
  }, 8000);

  // kalau error dari PHP, langsung buka
  if (hasError){
    fired = true;
    openModal();
  }

  // enable tombol
  const nameInput = document.getElementById("tsName");
  const radios = document.querySelectorAll('#tsRatingForm input[name="rating"]');
  const btn = document.getElementById("tsBtn");
  const text = document.getElementById("tsSelected");

  function update(){
    const namaOk = (nameInput.value || "").trim().length > 0;
    const picked = document.querySelector('#tsRatingForm input[name="rating"]:checked');
    const ratingOk = !!picked;
    btn.disabled = !(namaOk && ratingOk);

    if (!namaOk && !ratingOk) text.textContent = "Isi nama & pilih bintang dulu ya 🙂";
    else if (!namaOk) text.textContent = "Nama wajib diisi dulu ya 🙂";
    else if (!ratingOk) text.textContent = "Sekarang pilih bintangnya ya ⭐";
    else text.innerHTML = `Kamu pilih: <strong>${picked.value} / 5</strong> ⭐`;
  }

  nameInput.addEventListener("input", update);
  radios.forEach(r => r.addEventListener("change", update));
  update();

  // expose biar gampang demo (opsional)
  window.tsRatingOpen = openModal;

  // close fn global
  window.tsRatingClose = function(){
    wrap.style.display = "none";
    wrap.setAttribute("aria-hidden","true");
    if (!DEMO_MODE) sessionStorage.setItem(shownKey, "1");
  };

  // submit fn global
  window.tsRatingSubmit = function(){
    const nama = (nameInput.value || "").trim();
    const picked = document.querySelector('#tsRatingForm input[name="rating"]:checked');
    if (!nama || !picked) return false;

    if (!DEMO_MODE) localStorage.setItem(ratedKey, "1");
    return true;
  };
})();
</script>
