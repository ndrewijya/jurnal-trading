<?php
// File: templates/header.php (FINAL)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnal Trading</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="navbar">
        <a href="dashboard.php">Dashboard</a>
        <a href="trade_add.php">Tambah Trade</a>
        <div class="right">
            <?php if (isset($_SESSION['username'])): ?>
                <a href="#">Halo, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
                <a href="logout.php">Logout</a>
            <?php endif; ?>
        </div>
    </div>