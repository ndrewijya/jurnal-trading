<?php
session_start();
require_once 'config/database.php';
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password)) {
        $error = "Semua kolom harus diisi!";
    } elseif ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        $query_check = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmt_check = mysqli_prepare($koneksi, $query_check);
        mysqli_stmt_bind_param($stmt_check, "ss", $username, $email);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);
        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            $error = "Username atau Email sudah terdaftar!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $query_insert = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt_insert = mysqli_prepare($koneksi, $query_insert);
            mysqli_stmt_bind_param($stmt_insert, "sss", $username, $email, $hashed_password);
            if (mysqli_stmt_execute($stmt_insert)) {
                $_SESSION['register_success'] = "Registrasi berhasil! Silakan login.";
                header("Location: login.php");
                exit();
            } else {
                $error = "Terjadi kesalahan. Silakan coba lagi.";
            }
        }
    }
}
require_once 'templates/header.php'; // Muat header
?>

<div class="container auth-container"> <div class="auth-box">
        <h2>Buat Akun Baru</h2>
        
        <?php if (!empty($error)): ?><p class="message message-error"><?php echo $error; ?></p><?php endif; ?>

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
            <button type="submit" class="btn btn-full btn-primary">Daftar</button>
        </form>
        <div class="auth-link">
            <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </div>
    </div>
</div>

<?php require_once 'templates/footer.php'; // Muat footer ?>