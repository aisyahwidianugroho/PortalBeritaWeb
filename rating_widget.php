<?php
$error_rating = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_rating'])) {
  $nama = trim($_POST['nama'] ?? '');
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
      header("Location: " . $_SERVER['REQUEST_URI']);
      exit;
    } else {
      $error_rating = "Gagal menyimpan rating: " . mysqli_error($conn);
      mysqli_stmt_close($stmt);
    }
  }
}
?>

<style>
:root{--brand:#2563eb;--brand2:#1d4ed8;--muted:#64748b;--line:#e2e8f0}
.rating-modal-container{
  position:fixed; inset:0; display:none; align-items:center; justify-content:center;
  padding:16px; background:rgba(15,23,42,.52); backdrop-filter:blur(10px);
  z-index:999999;
}
.rating-modal{
  width:min(540px,94vw);
  border-radius:22px;
  padding:22px 20px 18px;
  position:relative; overflow:hidden;
  background:linear-gradient(180deg,#fff 0%,#fbfdff 100%);
  border:1px solid rgba(226,232,240,.95);
  box-shadow:0 28px 70px rgba(2,6,23,.28),0 2px 0 rgba(255,255,255,.7) inset;
  animation:pop .18s ease-out;
}
@keyframes pop{from{transform:translateY(10px) scale(.98);opacity:0}to{transform:translateY(0) scale(1);opacity:1}}
.rating-modal::before{
  content:""; position:absolute; width:320px; height:320px; border-radius:999px;
  top:-170px; left:-170px;
  background:radial-gradient(circle at 30% 30%, rgba(37,99,235,.22), rgba(37,99,235,0) 60%);
}
.close-btn{
  position:absolute; top:12px; right:12px; width:40px; height:40px; border-radius:14px;
  display:grid; place-items:center; cursor:pointer;
  border:1px solid rgba(226,232,240,.95);
  background:rgba(248,250,252,.92);
  color:#334155; transition:.15s; font-size:18px; user-select:none;
}
.close-btn:hover{transform:translateY(-1px);background:#fff;color:#0f172a}
.badge{
  display:inline-flex; align-items:center; gap:8px;
  padding:7px 12px; border-radius:999px; font-size:12px; font-weight:700;
  color:var(--brand2); background:rgba(37,99,235,.10);
  border:1px solid rgba(37,99,235,.18); margin-bottom:10px;
}
.modal-title{margin:0;font-size:clamp(18px,2.6vw,22px);font-weight:900;line-height:1.2}
.modal-sub{margin:8px 0 0;font-size:clamp(12.5px,2.2vw,13.5px);line-height:1.45;color:var(--muted)}
.error-message{margin-top:12px;padding:10px 12px;border-radius:14px;background:#ef4444;color:#fff;font-size:13px}
.input{
  width:100%; margin-top:14px; padding:13px 14px; border-radius:14px;
  border:1px solid var(--line); outline:none; background:#fff; font-size:14px; transition:.15s;
}
.input:focus{border-color:rgba(37,99,235,.45);box-shadow:0 0 0 4px rgba(37,99,235,.12)}
.stars{
  display:flex; flex-direction:row-reverse; justify-content:center;
  gap:12px; margin:16px 0 6px; touch-action:manipulation;
}
.stars input{display:none}
.stars label{
  font-size:clamp(34px,8vw,44px);
  cursor:pointer; color:#cbd5e1; transition:.15s;
  user-select:none; -webkit-tap-highlight-color:transparent;
}
.stars input:checked ~ label,.stars label:hover,.stars label:hover ~ label{
  color:#fbbf24!important; filter:drop-shadow(0 10px 20px rgba(251,191,36,.28));
}
.selected{text-align:center;margin:0;font-size:13px;color:#334155;min-height:18px}
.selected strong{color:var(--brand2)}
.send-btn{
  width:100%; margin-top:14px; padding:13px 18px;
  border:none; border-radius:14px; font-size:15px; font-weight:800;
  cursor:pointer; color:#fff;
  background:linear-gradient(180deg,var(--brand) 0%,var(--brand2) 100%);
  box-shadow:0 14px 32px rgba(37,99,235,.26); transition:.15s;
}
.send-btn:disabled{background:#cbd5e1;cursor:not-allowed;box-shadow:none}
</style>

<div class="rating-modal-container" id="ratingModal" aria-hidden="true">
  <div class="rating-modal" role="dialog" aria-modal="true">
    <span class="close-btn" onclick="closeRating()">✕</span>

    <div style="position:relative;text-align:left;margin-bottom:12px">
      <div class="badge">⭐ Feedback</div>
      <h2 class="modal-title">Gimana pengalaman kamu di portal ini?</h2>
      <p class="modal-sub">Kasih rating biar kami bisa bikin the Surabaya iNews makin enak dibaca ✨</p>
    </div>

    <?php if ($error_rating): ?>
      <div class="error-message"><?= htmlspecialchars($error_rating) ?></div>
    <?php endif; ?>

    <form method="POST" onsubmit="return guardSubmit();">
      <input type="hidden" name="submit_rating" value="1">

      <input class="input" id="nameInput" type="text" name="nama" placeholder="Tulis nama kamu..." required maxlength="80" autocomplete="name">

      <div class="stars" aria-label="Rating 1 sampai 5">
        <input type="radio" id="star5" name="rating" value="5" required><label for="star5">★</label>
        <input type="radio" id="star4" name="rating" value="4"><label for="star4">★</label>
        <input type="radio" id="star3" name="rating" value="3"><label for="star3">★</label>
        <input type="radio" id="star2" name="rating" value="2"><label for="star2">★</label>
        <input type="radio" id="star1" name="rating" value="1"><label for="star1">★</label>
      </div>

      <p class="selected" id="selectedText">Isi nama & pilih bintang dulu ya 🙂</p>
      <button class="send-btn" id="submitBtn" type="submit" disabled>Kirim Rating</button>
    </form>
  </div>
</div>

<script>
(function(){
  const modal = document.getElementById("ratingModal");
  if (!modal) return;

  // ✅ DEMO MODE: biar pasti muncul terus selama demo
  const DEMO_MODE = true;

  const shownOnceKey = "tsinews_rating_shown_once";
  const ratedKey = "tsinews_rated_done";
  const hasError = <?= $error_rating ? 'true' : 'false' ?>;

  function openModal(){
    modal.style.display = "flex";
    modal.setAttribute("aria-hidden","false");
    const inp = document.getElementById("nameInput");
    if (inp) setTimeout(() => inp.focus(), 40);
  }

  function canShow(){
    if (DEMO_MODE) return true;
    const ratedDone = localStorage.getItem(ratedKey) === "1";
    const shownOnce = sessionStorage.getItem(shownOnceKey) === "1";
    return !ratedDone && !shownOnce;
  }

  function showModal(){
    if (!canShow()) return;
    if (!DEMO_MODE) sessionStorage.setItem(shownOnceKey, "1");
    openModal();
  }

  // kalau ada error server, tampilkan
  if (hasError) {
    if (!DEMO_MODE) sessionStorage.setItem(shownOnceKey,"1");
    openModal();
  }

  // trigger scroll 60%
  function getScrollPercent(){
    const el = document.scrollingElement || document.documentElement;
    const scrollTop = el.scrollTop;
    const scrollHeight = el.scrollHeight - el.clientHeight;
    if (scrollHeight <= 0) return 0;
    return (scrollTop / scrollHeight) * 100;
  }

  let fired = false;
  function maybeShow(){
    if (fired) return;
    const p = getScrollPercent();
    if (p >= 60){
      fired = true;
      showModal();
    }
  }

  window.addEventListener("scroll", maybeShow, { passive:true });
  document.addEventListener("scroll", maybeShow, { passive:true, capture:true });

  // fallback: 8 detik
  setTimeout(() => {
    if (fired) return;
    fired = true;
    showModal();
  }, 8000);

  // state tombol submit
  const nameInput = document.getElementById("nameInput");
  const radios = document.querySelectorAll('input[name="rating"]');
  const btn = document.getElementById("submitBtn");
  const text = document.getElementById("selectedText");

  function updateState(){
    const namaOk = nameInput.value.trim().length > 0;
    const picked = document.querySelector('input[name="rating"]:checked');
    const ratingOk = !!picked;
    btn.disabled = !(namaOk && ratingOk);

    if (!namaOk && !ratingOk) text.textContent = "Isi nama & pilih bintang dulu ya 🙂";
    else if (!namaOk) text.textContent = "Nama wajib diisi dulu ya 🙂";
    else if (!ratingOk) text.textContent = "Sekarang pilih bintangnya ya ⭐";
    else text.innerHTML = `Kamu pilih: <strong>${picked.value} / 5</strong> ⭐`;
  }

  nameInput.addEventListener("input", updateState);
  radios.forEach(r => r.addEventListener("change", updateState));
  updateState();

})();

function closeRating(){
  const modal = document.getElementById("ratingModal");
  modal.style.display = "none";
  modal.setAttribute("aria-hidden","true");
}

function guardSubmit(){
  const nama = document.getElementById("nameInput").value.trim();
  const rating = document.querySelector('input[name="rating"]:checked');
  if (!nama || !rating) return false;

  // setelah submit, boleh diset stop selamanya (kalau DEMO_MODE true, ini gak ngaruh)
  localStorage.setItem("tsinews_rated_done","1");
  return true;
}
</script>
