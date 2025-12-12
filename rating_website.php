<?php
include "koneksi.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = trim($_POST['nama'] ?? '');
    $rating = intval($_POST['rating'] ?? 0);

    if ($nama === '' || mb_strlen($nama) > 80 || strtolower($nama) === 'anonymous') {
        $error = "Nama wajib diisi (maks 80 karakter dan jangan 'Anonymous').";
    } elseif ($rating < 1 || $rating > 5) {
        $error = "Pilih rating 1 sampai 5 ya.";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO rating_website (nama, rating) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "si", $nama, $rating);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: kategori.php?id=1");
            exit;
        } else {
            $error = "Gagal menyimpan rating: " . mysqli_error($conn);
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Rate Website - the Surabaya iNews</title>

<style>
:root{
  --brand:#2563eb;
  --brand2:#1d4ed8;
  --text:#0f172a;
  --muted:#64748b;
  --line:#e2e8f0;
  --bg:#f5f7fa;
}

*{ box-sizing:border-box; }

body{
  margin:0;
  min-height:100vh;
  font-family:'Poppins',system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;
  background:var(--bg);
  color:var(--text);
}

header h1{
  margin:0;
  padding:20px 0;
  text-align:center;
  font-size:clamp(22px, 3.2vw, 30px);
  font-weight:800;
  letter-spacing:.3px;
  color:#111827;
}

/* BACKDROP */
.rating-modal-container{
  position:fixed;
  inset:0;
  display:none;
  align-items:center;
  justify-content:center;
  padding:16px;
  background:rgba(15,23,42,.52);
  backdrop-filter: blur(10px);
}

/* CARD */
.rating-modal{
  width:min(540px, 94vw);
  border-radius:22px;
  padding:22px 20px 18px;
  position:relative;
  overflow:hidden;
  background:linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
  border:1px solid rgba(226,232,240,.95);
  box-shadow:
    0 28px 70px rgba(2,6,23,.28),
    0 2px 0 rgba(255,255,255,.7) inset;
  animation:pop .18s ease-out;
}

@keyframes pop{
  from{ transform:translateY(10px) scale(.98); opacity:0; }
  to{ transform:translateY(0) scale(1); opacity:1; }
}

.rating-modal::before{
  content:"";
  position:absolute;
  width:320px;
  height:320px;
  border-radius:999px;
  top:-170px;
  left:-170px;
  background:radial-gradient(circle at 30% 30%, rgba(37,99,235,.22), rgba(37,99,235,0) 60%);
}

/* CLOSE */
.close-btn{
  position:absolute;
  top:12px; right:12px;
  width:40px; height:40px;
  border-radius:14px;
  display:grid; place-items:center;
  cursor:pointer;
  border:1px solid rgba(226,232,240,.95);
  background:rgba(248,250,252,.92);
  color:#334155;
  transition:.15s;
  font-size:18px;
  user-select:none;
}
.close-btn:hover{ transform:translateY(-1px); background:#fff; color:#0f172a; }

/* HEADER MODAL */
.modal-head{ position:relative; text-align:left; margin-bottom:12px; }

.badge{
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

.modal-title{
  margin:0;
  font-size:clamp(18px, 2.6vw, 22px);
  font-weight:900;
  letter-spacing:.2px;
  line-height:1.2;
}

.modal-sub{
  margin:8px 0 0;
  font-size:clamp(12.5px, 2.2vw, 13.5px);
  line-height:1.45;
  color:var(--muted);
}

/* ERROR */
.error-message{
  margin-top:12px;
  padding:10px 12px;
  border-radius:14px;
  background:#ef4444;
  color:#fff;
  font-size:13px;
}

/* INPUT */
.input{
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
.input:focus{
  border-color:rgba(37,99,235,.45);
  box-shadow:0 0 0 4px rgba(37,99,235,.12);
}

/* STARS */
.stars{
  display:flex;
  flex-direction:row-reverse;
  justify-content:center;
  gap:12px;
  margin:16px 0 6px;
  touch-action: manipulation;
}
.stars input{ display:none; }

.stars label{
  font-size:clamp(34px, 8vw, 44px);
  cursor:pointer;
  color:#cbd5e1;
  transition:.15s;
  filter:drop-shadow(0 0 0 rgba(0,0,0,0));
  transform:translateY(0) scale(1);
  user-select:none;
  -webkit-tap-highlight-color: transparent;
}
.stars label:hover{ transform:translateY(-1px) scale(1.06); }

.stars input:checked ~ label,
.stars label:hover,
.stars label:hover ~ label{
  color:#fbbf24 !important;
  filter: drop-shadow(0 10px 20px rgba(251,191,36,.28));
}

.selected{
  text-align:center;
  margin:0;
  font-size:13px;
  color:#334155;
  min-height:18px;
}
.selected strong{ color:var(--brand2); }

/* BUTTON */
.send-btn{
  width:100%;
  margin-top:14px;
  padding:13px 18px;
  border:none;
  border-radius:14px;
  font-size:15px;
  font-weight:800;
  cursor:pointer;
  color:#fff;
  background:linear-gradient(180deg, var(--brand) 0%, var(--brand2) 100%);
  box-shadow:0 14px 32px rgba(37,99,235,.26);
  transition:.15s;
}
.send-btn:hover{ transform:translateY(-1px); box-shadow:0 18px 38px rgba(37,99,235,.30); }
.send-btn:disabled{
  background:#cbd5e1;
  cursor:not-allowed;
  box-shadow:none;
  transform:none;
}

@media (max-width:420px){
  .rating-modal{ padding:18px 16px 14px; border-radius:20px; }
  .close-btn{ width:38px; height:38px; border-radius:12px; }
  .badge{ font-size:11px; padding:6px 10px; }
}
</style>
</head>

<body>
<header><h1>the Surabaya iNews</h1></header>

<div class="rating-modal-container" id="ratingModal" aria-hidden="true">
  <div class="rating-modal" role="dialog" aria-modal="true" aria-label="Form rating website">
    <span class="close-btn" onclick="closeRating()">✕</span>

    <div class="modal-head">
      <div class="badge">⭐ Feedback</div>
      <h2 class="modal-title">Gimana pengalaman kamu di portal ini?</h2>
      <p class="modal-sub">Kasih rating biar kami bisa bikin the Surabaya iNews makin enak dibaca ✨</p>
    </div>

    <?php if ($error): ?>
      <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" id="ratingForm" onsubmit="return guardSubmit();">
      <input class="input" id="nameInput" type="text" name="nama" placeholder="Tulis nama kamu..." required maxlength="80" autocomplete="name">

      <div class="stars" aria-label="Rating 1 sampai 5">
        <input type="radio" id="star5" name="rating" value="5" required><label for="star5" title="5">★</label>
        <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="4">★</label>
        <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="3">★</label>
        <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="2">★</label>
        <input type="radio" id="star1" name="rating" value="1"><label for="star1" title="1">★</label>
      </div>

      <p class="selected" id="selectedText">Isi nama & pilih bintang dulu ya 🙂</p>

      <button class="send-btn" id="submitBtn" type="submit" disabled>Kirim Rating</button>
    </form>
  </div>
</div>

<script>
(function(){
  const modal = document.getElementById("ratingModal");
  if (!modal) return; // kalau modal belum di halaman ini, stop

  const keyUntil = "tsinews_rating_until";
  const shownOnceKey = "tsinews_rating_shown_once";
  const hasError = <?= isset($error) && $error ? 'true' : 'false' ?>;

  function canShow(){
    const until = parseInt(localStorage.getItem(keyUntil) || "0", 10);
    const shownOnce = sessionStorage.getItem(shownOnceKey) === "1";
    return (Date.now() > until) && !shownOnce;
  }

  function showModal(){
    if (!canShow()) return;
    sessionStorage.setItem(shownOnceKey, "1");
    modal.style.display = "flex";
    modal.setAttribute("aria-hidden", "false");
    const inp = document.getElementById("nameInput");
    if (inp) setTimeout(() => inp.focus(), 50);
  }

  // kalau error validasi PHP, tampilkan langsung
  if (hasError) showModal();

  // === Trigger 1: scroll 60% ===
  function onScroll(){
    if (!canShow()) return;

    const doc = document.documentElement;
    const scrollTop = window.scrollY || doc.scrollTop;
    const scrollHeight = doc.scrollHeight - doc.clientHeight;
    if (scrollHeight <= 0) return;

    const percent = (scrollTop / scrollHeight) * 100;
    if (percent >= 60){
      window.removeEventListener("scroll", onScroll);
      showModal();
    }
  }
  window.addEventListener("scroll", onScroll, { passive: true });

  // === Trigger 2: fallback timer 12 detik (kalau user gak scroll) ===
  setTimeout(() => {
    // hanya tampil kalau user masih belum scroll jauh
    const doc = document.documentElement;
    const scrollTop = window.scrollY || doc.scrollTop;
    if (scrollTop < 120) showModal();
  }, 12000);

})();
</script>

</body>
</html>
