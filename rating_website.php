<?php
include "koneksi.php";

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = intval($_POST['rating'] ?? 0);

    if ($rating >= 1 && $rating <= 5) {
        mysqli_query($conn, "INSERT INTO rating_website (rating) VALUES ($rating)");
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rate Website - the Surabaya iNews</title>

<style>

/* ================== GLOBAL ================== */
body {
    font-family: 'Poppins', sans-serif;
    background: #f5f7fa;
    margin: 0;
    padding: 0;
    height: 100vh;
}

/* ================== HEADER ================== */
header h1 {
    text-align: center;
    padding: 25px 0;
    margin: 0;
    font-size: 32px;
    color: #222;
    letter-spacing: 1px;
}

/* ================== SUCCESS BOX ================== */
.success-message {
    background: #16a34a;
    color: white;
    padding: 12px;
    margin: 20px auto;
    width: 80%;
    border-radius: 8px;
    font-size: 16px;
    text-align: center;
}

/* ================== POPUP BACKDROP ================== */
.rating-modal-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    backdrop-filter: blur(4px);
    background: rgba(0,0,0,0.35);
    display: flex;
    justify-content: center;
    align-items: center;
}

/* ================== POPUP BOX ================== */
.rating-modal {
    background: white;
    width: 350px;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0px 8px 24px rgba(0,0,0,0.15);
    text-align: center;
    position: relative;
    animation: popup 0.2s ease-out;
}

@keyframes popup {
    from { transform: scale(0.8); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

/* Close Button */
.close-btn {
    position: absolute;
    top: 12px;
    right: 15px;
    font-size: 24px;
    cursor: pointer;
    color: #777;
    transition: 0.2s;
}
.close-btn:hover {
    color: #000;
}

/* Popup Title */
.rating-modal p:first-child {
    margin-top: 10px;
    font-size: 18px;
    color: #333;
}
.rating-modal p:nth-child(2) {
    font-size: 15px;
    margin-top: -5px;
    color: #555;
}

/* ================== STAR RATING ================== */
.stars input {
    display: none;
}

.stars label {
    font-size: 40px;
    cursor: pointer;
    color: #ddd;
    transition: 0.2s;
}

.stars input:checked ~ label,
.stars label:hover,
.stars label:hover ~ label {
    color: #ffd700 !important;
}

/* ================== BUTTON ================== */
.send-btn {
    margin-top: 18px;
    padding: 12px 25px;
    font-size: 15px;
    border: none;
    border-radius: 8px;
    background: #2563eb;
    color: white;
    cursor: pointer;
    transition: 0.2s;
}
.send-btn:hover {
    background: #1d4ed8;
    transform: translateY(-1px);
}

</style>

</head>
<body>

<header>
    <h1>the Surabaya iNews</h1>
</header>

<?php if ($success): ?>
    <div class="success-message">Terima kasih! Rating Anda berhasil dikirim ❤️</div>
<?php endif; ?>


<!-- ======== POPUP RATING ======== -->
<div class="rating-modal-container" id="ratingModal">

    <div class="rating-modal">

        <span class="close-btn" onclick="closeRating()">×</span>

        <p>DO YOU LIKE IT?</p>
        <p>RATE US!</p>

        <form method="POST">
            <div class="stars">
                
                <input type="radio" id="star5" name="rating" value="5">
                <label for="star5">&#9733;</label>

                <input type="radio" id="star4" name="rating" value="4">
                <label for="star4">&#9733;</label>

                <input type="radio" id="star3" name="rating" value="3">
                <label for="star3">&#9733;</label>

                <input type="radio" id="star2" name="rating" value="2">
                <label for="star2">&#9733;</label>

                <input type="radio" id="star1" name="rating" value="1">
                <label for="star1">&#9733;</label>

            </div>

            <button class="send-btn" type="submit">Kirim Rating</button>

        </form>

    </div>

</div>


<script>
function closeRating() {
    document.getElementById("ratingModal").style.display = "none";
}
</script>

</body>
</html>
