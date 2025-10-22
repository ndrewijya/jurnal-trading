<?php
// File: templates/header_auth.php (BARU)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Jurnal Trading</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>