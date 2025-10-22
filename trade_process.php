<?php
require_once 'core/auth.php';
require_once 'config/database.php';

// PROSES CREATE & UPDATE (METHOD POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Aksi CREATE (tidak berubah)
    if (isset($_POST['action']) && $_POST['action'] === 'create') {
        $user_id = $_SESSION['user_id'];
        $entry_price = $_POST['entry_price'];
        $stop_loss = $_POST['stop_loss'];
        $take_profit = $_POST['take_profit'];
        $position_type = $_POST['position_type'];

        $is_valid = false;
        if ($position_type == 'Buy') {
            if ($stop_loss < $entry_price && $take_profit > $entry_price) $is_valid = true;
        } elseif ($position_type == 'Sell') {
            if ($take_profit < $entry_price && $stop_loss > $entry_price) $is_valid = true;
        }

        if (!$is_valid) {
            die("Error: Data tidak valid. Untuk posisi <strong>{$position_type}</strong>, urutan harga tidak sesuai. <br><br> <a href='javascript:history.back()'>Kembali ke Form</a>");
        }

        $stmt = mysqli_prepare($koneksi, "INSERT INTO trades (user_id, currency_pair, position_type, entry_price, stop_loss, take_profit, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "issddds", $user_id, $_POST['currency_pair'], $position_type, $entry_price, $stop_loss, $take_profit, $_POST['notes']);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: dashboard.php");
            exit();
        }
    
    // Aksi UPDATE (BAGIAN YANG DIPERBARUI)
    } elseif (isset($_POST['action']) && $_POST['action'] === 'update') {
        $user_id = $_SESSION['user_id'];
        $hasil = $_POST['hasil'];
        $profit_loss_input = $_POST['profit_loss'];
        $profit_loss_final = null;

        // --- LOGIKA BARU UNTUK OTOMATISASI +/- ---
        if (!empty($profit_loss_input)) {
            if ($hasil == 'TP') {
                // Jika hasilnya TP, pastikan angkanya positif
                $profit_loss_final = abs($profit_loss_input);
            } elseif ($hasil == 'SL') {
                // Jika hasilnya SL, paksa angkanya menjadi negatif
                $profit_loss_final = -abs($profit_loss_input);
            } else { // Jika masih Pending
                $profit_loss_final = null;
            }
        }

        $stmt = mysqli_prepare($koneksi, "UPDATE trades SET hasil = ?, profit_loss = ?, notes = ? WHERE id = ? AND user_id = ?");
        mysqli_stmt_bind_param($stmt, "sdsii", 
            $hasil, 
            $profit_loss_final, 
            $_POST['notes'], 
            $_POST['trade_id'], 
            $user_id
        );
        if (mysqli_stmt_execute($stmt)) {
            header("Location: dashboard.php");
            exit();
        }
    }
}

// PROSES DELETE (METHOD GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete') {
    $user_id = $_SESSION['user_id'];
    $trade_id = $_GET['id'];
    
    if (!empty($trade_id)) {
        $stmt = mysqli_prepare($koneksi, "DELETE FROM trades WHERE id = ? AND user_id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $trade_id, $user_id);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: dashboard.php");
            exit();
        }
    }
}
?>