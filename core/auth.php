<?php
// Selalu mulai session di setiap halaman yang diproteksi
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user_id TIDAK ADA di dalam session
if (!isset($_SESSION['user_id'])) {
    // Jika tidak ada, artinya belum login.
    // Arahkan paksa ke halaman login.
    header("Location: login.php");
    exit();
}
?>