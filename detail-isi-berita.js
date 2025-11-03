/* Isi-berita.js
   Versi stabil dan lengkap.
   Fitur: menu (☰), komentar dinamis + rating bintang + pagination.
   Pastikan di HTML ada: <script src="Isi-berita.js" defer></script>
*/

document.addEventListener("DOMContentLoaded", () => {

  /* ===================================================
     MENU POPUP (TOMBOL ☰)
  =================================================== */
  const menuBtn = document.querySelector(".menu-btn");
  const navLinks = document.querySelector(".nav-links");
  const leftArea = document.querySelector(".left");

  function isMobileWidth() {
    return window.innerWidth <= 900;
  }

  function openNavMobile() {
    if (!navLinks) return;
    navLinks.style.display = "flex";
    navLinks.style.flexDirection = isMobileWidth() ? "column" : "row";
    navLinks.classList.add("show");
  }

  function closeNavMobile() {
    if (!navLinks) return;
    if (isMobileWidth()) {
      navLinks.style.display = "none";
      navLinks.classList.remove("show");
    }
  }

  if (menuBtn && navLinks) {
    // inisialisasi awal
    if (isMobileWidth()) navLinks.style.display = "none";

    menuBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      navLinks.classList.contains("show") ? closeNavMobile() : openNavMobile();
    });

    document.addEventListener("click", (e) => {
      if (!leftArea.contains(e.target) && !navLinks.contains(e.target)) {
        closeNavMobile();
      }
    });

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") closeNavMobile();
    });

    window.addEventListener("resize", () => {
      if (!navLinks) return;
      if (isMobileWidth()) {
        navLinks.style.display = "none";
        navLinks.classList.remove("show");
      } else {
        navLinks.style.display = "flex";
        navLinks.style.flexDirection = "row";
        navLinks.classList.remove("show");
      }
    });
  }

  /* ===================================================
     KOMENTAR DINAMIS + RATING BINTANG + PAGINATION
  =================================================== */
  const commentsSection = document.querySelector(".comments");
  if (!commentsSection) return;

  const commentFormButton = document.querySelector(".comment-form button");
  const commentTextarea = document.querySelector(".comment-form textarea");
  const commentUserNameEl = document.querySelector(".comment-form .user-name");
  const commentStarsBox = document.querySelector(".comment-form .stars");

  const staticComments = Array.from(commentsSection.querySelectorAll(":scope > .comment"));

  // buat wadah komentar dinamis
  const DYN_CLASS = "dynamic-comments";
  let dynamicContainer = commentsSection.querySelector(`.${DYN_CLASS}`);
  if (!dynamicContainer) {
    dynamicContainer = document.createElement("div");
    dynamicContainer.className = DYN_CLASS;
    const maybeViewMore = commentsSection.querySelector(".view-more");
    if (maybeViewMore) commentsSection.insertBefore(dynamicContainer, maybeViewMore);
    else commentsSection.appendChild(dynamicContainer);
  }

  // Simpan & muat dari localStorage
  const STORAGE_KEY = "isi_comments";
  let storedComments = [];

  function loadStoredComments() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      storedComments = raw ? JSON.parse(raw) : [];
      if (!Array.isArray(storedComments)) storedComments = [];
    } catch (err) {
      console.error("Gagal parse komentar dari localStorage:", err);
      storedComments = [];
    }
  }

  function saveCommentsToStorage() {
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(storedComments));
    } catch (err) {
      console.error("Gagal menyimpan komentar ke localStorage:", err);
    }
  }

  loadStoredComments();

  /* ---------- Pagination ---------- */
  const PAGE_SIZE = 3;
  let currentShown = 0;

  function updateCommentsHeader() {
    const h3 = commentsSection.querySelector("h3");
    if (!h3) return;
    const total = staticComments.length + storedComments.length;
    h3.textContent = `Comments (${total})`;
  }

  function formatDate(isoString) {
    try {
      const d = new Date(isoString);
      return d.toLocaleString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
      });
    } catch {
      return isoString || "";
    }
  }

  function renderDynamicComments(reset = false) {
    if (reset) {
      dynamicContainer.innerHTML = "";
      currentShown = 0;
    }
    const remaining = storedComments.length - currentShown;
    if (remaining <= 0) {
      updateCommentsHeader();
      updateViewMore();
      return;
    }

    const count = Math.min(PAGE_SIZE, remaining);
    for (let i = currentShown; i < currentShown + count; i++) {
      const c = storedComments[i];
      const commentDiv = document.createElement("div");
      commentDiv.className = "comment";

      const avatar = document.createElement("div");
      avatar.className = "avatar";
      avatar.textContent = "👤";

      const body = document.createElement("div");
      body.className = "comment-body";

      const header = document.createElement("div");
      header.className = "comment-header";

      const nameSpan = document.createElement("span");
      nameSpan.className = "name";
      nameSpan.textContent = c.name || "Anon";

      const starsSpan = document.createElement("span");
      starsSpan.className = "stars";
      const starCount = Number.isFinite(c.stars) ? Math.max(0, Math.min(5, c.stars)) : 0;
      starsSpan.textContent = "★".repeat(starCount) + "☆".repeat(5 - starCount);

      const timeSpan = document.createElement("span");
      timeSpan.className = "time";
      timeSpan.style.marginLeft = "8px";
      timeSpan.style.fontSize = "12px";
      timeSpan.style.color = "#666";
      timeSpan.textContent = formatDate(c.time);

      header.append(nameSpan, starsSpan, timeSpan);

      const p = document.createElement("p");
      p.className = "comment-text";
      p.textContent = c.text || "";

      body.append(header, p);
      commentDiv.append(avatar, body);
      dynamicContainer.appendChild(commentDiv);
    }

    currentShown += count;
    updateCommentsHeader();
    updateViewMore();
  }

  function updateViewMore() {
    let viewMore = commentsSection.querySelector(".view-more");
    if (storedComments.length === 0) {
      if (viewMore) viewMore.style.display = "none";
      return;
    }

    if (!viewMore) {
      viewMore = document.createElement("a");
      viewMore.href = "#";
      viewMore.className = "view-more";
      viewMore.textContent = "View More";
      commentsSection.appendChild(viewMore);
      viewMore.addEventListener("click", (e) => {
        e.preventDefault();
        renderDynamicComments(false);
      });
    }

    viewMore.style.display = currentShown < storedComments.length ? "block" : "none";
  }

  /* ---------- STAR RATING ---------- */
  let currentStarValue = 5;

  function setupStarRating() {
    if (!commentStarsBox) return;
    commentStarsBox.innerHTML = "";
    commentStarsBox.style.display = "inline-flex";
    commentStarsBox.style.gap = "6px";

    for (let i = 1; i <= 5; i++) {
      const btn = document.createElement("button");
      btn.type = "button";
      btn.className = "star-btn";
      btn.setAttribute("aria-label", `${i} stars`);
      btn.setAttribute("data-star", String(i));
      btn.style.border = "none";
      btn.style.background = "transparent";
      btn.style.cursor = "pointer";
      btn.style.fontSize = "18px";
      btn.textContent = i <= currentStarValue ? "★" : "☆";

      btn.addEventListener("click", () => setStars(i));
      btn.addEventListener("keydown", (e) => {
        if (e.key === "Enter" || e.key === " ") {
          e.preventDefault();
          setStars(i);
        }
      });

      commentStarsBox.appendChild(btn);
    }
  }

  function setStars(n) {
    currentStarValue = Math.max(0, Math.min(5, n));
    if (!commentStarsBox) return;
    const btns = commentStarsBox.querySelectorAll(".star-btn");
    btns.forEach((b, idx) => (b.textContent = idx < currentStarValue ? "★" : "☆"));
  }

  setupStarRating();

  // prefill username
  try {
    const savedUser = localStorage.getItem("username");
    if (savedUser && commentUserNameEl) commentUserNameEl.textContent = savedUser;
  } catch {}

  /* ---------- POST KOMENTAR ---------- */
  if (commentFormButton && commentTextarea) {
    commentFormButton.addEventListener("click", (e) => {
      e.preventDefault();

      const text = (commentTextarea.value || "").trim();
      if (text.length < 2) {
        alert("Komentar terlalu pendek.");
        commentTextarea.focus();
        return;
      }

      const name =
        (commentUserNameEl && commentUserNameEl.textContent?.trim()) || "Anon";

      const newComment = {
        id: Date.now(),
        name: name,
        text: text,
        stars: currentStarValue,
        time: new Date().toISOString(),
      };

      storedComments.unshift(newComment);
      saveCommentsToStorage();
      renderDynamicComments(true);

      commentTextarea.value = "";
      setStars(5);
      commentTextarea.focus();
    });

    // Shortcut: Ctrl + Enter
    commentTextarea.addEventListener("keydown", (e) => {
      if ((e.ctrlKey || e.metaKey) && e.key === "Enter") {
        e.preventDefault();
        commentFormButton.click();
      }
    });
  }

  // inisialisasi awal
  renderDynamicComments(true);
  updateCommentsHeader();
  updateViewMore();

  /* ---------- DEV UTILITIES (opsional) ---------- */
  // Untuk reset komentar manual lewat console:
  // window.clearAllComments = () => { localStorage.removeItem(STORAGE_KEY); location.reload(); }

});
