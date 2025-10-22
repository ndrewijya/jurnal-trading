<?php
// Selalu mulai session di awal
session_start();

// Jika pengguna sudah login, langsung arahkan ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Panggil file koneksi database
require_once 'config/database.php';

// Variabel untuk menyimpan pesan
$error = '';
$success = '';

// Cek apakah ada pesan sukses dari halaman registrasi
if (isset($_SESSION['register_success'])) {
    $success = $_SESSION['register_success'];
    // Hapus pesan setelah ditampilkan agar tidak muncul lagi
    unset($_SESSION['register_success']);
}

// Cek apakah form sudah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validasi dasar
    if (empty($username) || empty($password)) {
        $error = "Username dan password harus diisi!";
    } else {
        // Siapkan query untuk mencari user berdasarkan username
        $query = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Cek apakah user ditemukan
        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            
            // Verifikasi password yang diinput dengan hash di database
            if (password_verify($password, $user['password'])) {
                // Jika password cocok, login berhasil!
                
                // Simpan informasi pengguna ke dalam session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                // Arahkan ke halaman dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                // Jika password tidak cocok
                $error = "Username atau password salah!";
            }
        } else {
            // Jika username tidak ditemukan
            $error = "Username atau password salah!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jurnal Trading</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f4f4f4; }
        .container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 300px; }
        h2 { text-align: center; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { width: 100%; padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #218838; }
        .error { color: red; text-align: center; margin-bottom: 1rem; }
        .success { color: green; text-align: center; margin-bottom: 1rem; }
        .register-link { text-align: center; margin-top: 1rem; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login Jurnal Trading</h2>
        
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <div class="register-link">
            <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
        </div>
    </div>
</body>
</html>