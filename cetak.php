<?php
require_once 'core/auth.php';
require_once 'config/database.php';

$current_user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$query = "SELECT * FROM trades WHERE user_id = ? ORDER BY id DESC";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $current_user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapan Trading</title>
    <style>
        body { font-family: "Times New Roman", serif; font-size: 12pt; color: #000; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        h2, h3, p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; font-size: 11pt; }
        th { background-color: #f0f0f0; }
        .profit { color: #000; }
        .loss { color: #000; }
        
        @media print {
            @page { margin: 2cm; }
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;         
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h2>LAPORAN REKAPAN JURNAL TRADING</h2>
        <h3>User: <?php echo htmlspecialchars($username); ?></h3>
        <p>Dicetak pada: <?php echo date("d F Y, H:i"); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Pasangan</th>
                <th>Posisi</th>
                <th>Entry Price</th>
                <th>Stop Loss</th>
                <th>Take Profit</th>
                <th>Hasil</th>
                <th>P/L</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $nomor = 1;
            $total_profit = 0;
            if (mysqli_num_rows($result) > 0): 
                while ($trade = mysqli_fetch_assoc($result)): 
                    // Menjumlahkan profit/loss (jika ada)
                    $pl_value = $trade['profit_loss'] ? $trade['profit_loss'] : 0;
                    $total_profit += $pl_value;
            ?>
                <tr>
                    <td><?php echo $nomor++; ?></td>
                    <td><?php echo htmlspecialchars($trade['currency_pair']); ?></td>
                    <td><?php echo htmlspecialchars($trade['position_type']); ?></td>
                    <td><?php echo (float)$trade['entry_price']; ?></td>
                    <td><?php echo (float)$trade['stop_loss']; ?></td>
                    <td><?php echo (float)$trade['take_profit']; ?></td>
                    <td><?php echo $trade['hasil']; ?></td>
                    <td><?php echo $trade['profit_loss'] ? number_format($trade['profit_loss'], 2) : '-'; ?></td>
                    <td><?php echo htmlspecialchars($trade['notes']); ?></td>
                </tr>
            <?php endwhile; ?>
                <tr style="font-weight: bold; background-color: #f0f0f0;">
                    <td colspan="7" style="text-align: right;">TOTAL NET PROFIT:</td>
                    <td colspan="2"><?php echo number_format($total_profit, 2); ?></td>
                </tr>
            <?php else: ?>
                <tr><td colspan="9" style="text-align: center;">Tidak ada data.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: right;">
        <p>Mengetahui,</p>
        <br><br><br>
        <p>( <?php echo htmlspecialchars($username); ?> )</p>
    </div>

</body>
</html>