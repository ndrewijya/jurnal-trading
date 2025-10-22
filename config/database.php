<?php
// Informasi koneksi ke database
$host = 'localhost';
$username = 'root';
$password = '';      
$dbname = 'db_jurnal_trading';

// Membuat koneksi ke database
$koneksi = mysqli_connect($host, $username, $password, $dbname);

// Memeriksa apakah koneksi berhasil atau gagal
if (!$koneksi) {
    // Jika gagal, hentikan script dan tampilkan pesan error
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>