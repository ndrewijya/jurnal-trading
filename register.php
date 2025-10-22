<?php
// Mulai session untuk menyimpan pesan feedback
session_start();

// Panggil file koneksi database
require_once 'config/database.php';

// Variabel untuk menyimpan pesan error
$error = '';

// Cek apakah form sudah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form dan bersihkan dari potensi injection
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi sederhana
    if (empty($username) || empty($email) || empty($password)) {
        $error = "Semua kolom harus diisi!";
    } elseif ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        // Cek apakah username atau email sudah ada di database
        $query_check = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmt_check = mysqli_prepare($koneksi, $query_check);
        mysqli_stmt_bind_param($stmt_check, "ss", $username, $email);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            $error = "Username atau Email sudah terdaftar!";
        } else {
            // Hash password sebelum disimpan ke database untuk keamanan
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Siapkan query untuk memasukkan data pengguna baru
            $query_insert = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt_insert = mysqli_prepare($koneksi, $query_insert);
            mysqli_stmt_bind_param($stmt_insert, "sss", $username, $email, $hashed_password);

            // Eksekusi query
            if (mysqli_stmt_execute($stmt_insert)) {
                // Jika berhasil, arahkan ke halaman login
                $_SESSION['register_success'] = "Registrasi berhasil! Silakan login.";
                header("Location: login.php");
                exit();
            } else {
                $error = "Terjadi kesalahan. Silakan coba lagi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Jurnal Trading</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f4f4f4; }
        .container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 300px; }
        h2 { text-align: center; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .error { color: red; text-align: center; margin-bottom: 1rem; }
        .login-link { text-align: center; margin-top: 1rem; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Buat Akun Baru</h2>
        
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit">Daftar</button>
        </form>
        <div class="login-link">
            <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </div>
    </div>
</body>
</html>