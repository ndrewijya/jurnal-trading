<?php
// Pastikan session dimulai untuk bisa mengakses $_SESSION['username']
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
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f9f9f9; }
        .navbar { background-color: #333; overflow: hidden; }
        .navbar a { float: left; display: block; color: white; text-align: center; padding: 14px 20px; text-decoration: none; }
        .navbar a:hover { background-color: #ddd; color: black; }
        .navbar .right { float: right; }
        .container { padding: 20px; }
        .welcome-msg { font-size: 1.2em; }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="dashboard.php">Dashboard</a>
        <a href="trade_add.php">Tambah Trade</a>
        <div class="right">
            <a href="#">Halo, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="container">