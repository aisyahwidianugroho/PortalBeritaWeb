<?php
session_start();
session_unset();
session_destroy();

// pakai path absolut (diawali /) supaya tidak tergantung folder pemanggil
header('Location: /PortalBeritaWeb/Admin/login.php');
exit;
