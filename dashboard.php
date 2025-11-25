<?php
require_once 'core/auth.php';
require_once 'config/database.php';
require_once 'templates/header.php'; // Memuat header

$current_user_id = $_SESSION['user_id'];

// --- LOGIKA STATISTIK ---
$stmt_total = mysqli_prepare($koneksi, "SELECT COUNT(id) as total FROM trades WHERE user_id = ?");
mysqli_stmt_bind_param($stmt_total, "i", $current_user_id);
mysqli_stmt_execute($stmt_total);
$total_trades = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_total))['total'];
$stmt_pl = mysqli_prepare($koneksi, "SELECT SUM(profit_loss) as total_pl FROM trades WHERE user_id = ?");
mysqli_stmt_bind_param($stmt_pl, "i", $current_user_id);
mysqli_stmt_execute($stmt_pl);
$total_pl = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_pl))['total_pl'];
$stmt_open = mysqli_prepare($koneksi, "SELECT COUNT(id) as total_open FROM trades WHERE user_id = ? AND hasil = 'Pending'");
mysqli_stmt_bind_param($stmt_open, "i", $current_user_id);
mysqli_stmt_execute($stmt_open);
$total_open = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_open))['total_open'];
$stmt_streaks = mysqli_prepare($koneksi, "SELECT hasil FROM trades WHERE user_id = ? AND hasil IN ('TP', 'SL') ORDER BY id ASC");
mysqli_stmt_bind_param($stmt_streaks, "i", $current_user_id);
mysqli_stmt_execute($stmt_streaks);
$result_streaks = mysqli_stmt_get_result($stmt_streaks);
$max_win_streak = 0; $current_win_streak = 0; $max_loss_streak = 0; $current_loss_streak = 0;
while ($trade = mysqli_fetch_assoc($result_streaks)) {
    if ($trade['hasil'] == 'TP') {
        $current_win_streak++; $current_loss_streak = 0;
        if ($current_win_streak > $max_win_streak) $max_win_streak = $current_win_streak;
    } elseif ($trade['hasil'] == 'SL') {
        $current_loss_streak++; $current_win_streak = 0;
        if ($current_loss_streak > $max_loss_streak) $max_loss_streak = $current_loss_streak;
    }
}

// --- LOGIKA PENCARIAN  ---
$search_term = $_GET['search'] ?? '';
if (!empty($search_term)) {
    $query = "SELECT * FROM trades WHERE user_id = ? AND currency_pair LIKE ? ORDER BY id DESC";
    $stmt = mysqli_prepare($koneksi, $query);
    $search_like = "%" . $search_term . "%"; 
    mysqli_stmt_bind_param($stmt, "is", $current_user_id, $search_like);
} else {
    $query = "SELECT * FROM trades WHERE user_id = ? ORDER BY id DESC";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $current_user_id);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<div class="container">
    <div class="welcome-msg">
        <h2>Ringkasan Performa</h2>
    </div>

    <div class="stats-container">
        <div class="stat-card">
            <h4>Total Net Profit</h4>
            <p class="stat-number <?php echo ($total_pl >= 0) ? 'profit' : 'loss'; ?>">
                <?php echo number_format($total_pl ?? 0, 2); ?>
            </p>
        </div>
        <div class="stat-card">
            <h4>Total Trade</h4>
            <p class="stat-number neutral"><?php echo $total_trades; ?></p>
        </div>
        <div class="stat-card">
            <h4>Posisi Terbuka</h4>
            <p class="stat-number blue"><?php echo $total_open; ?></p>
        </div>
        <div class="stat-card">
            <h4>Profit Beruntun</h4>
            <p class="stat-number profit"><?php echo $max_win_streak; ?> Kali</p>
        </div>
        <div class="stat-card">
            <h4>Loss Beruntun</h4>
            <p class="stat-number loss"><?php echo $max_loss_streak; ?> Kali</p>
        </div>
    </div>

    <div class="search-container" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
        
        <form action="dashboard.php" method="GET" style="display: flex; align-items: center;">
            <label for="search" style="margin-right: 10px;">Cari:</label>
            <input type="text" id="search" name="search" placeholder="Contoh: EUR/USD" value="<?php echo htmlspecialchars($search_term); ?>" style="margin-right: 5px;">
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="dashboard.php" class="btn btn-reset">Reset</a>
        </form>

        <a href="cetak.php" target="_blank" class="btn" style="background-color: #e67e22; color: white; text-decoration: none; padding: 10px 20px; margin-left: auto;">
            🖨️ Cetak Rekapan
        </a>
    </div>
    <div class="welcome-msg" style="margin-top: 40px;">
        <h2>Riwayat Trading Anda</h2>
    </div>

    <div class="table-wrapper">
        <table class="main-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Pasangan Mata Uang</th>
                    <th>Posisi</th>
                    <th>Entry Price</th>
                    <th>Stop Loss</th>
                    <th>Take Profit</th>
                    <th>Hasil</th>
                    <th>Profit/Loss</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php $nomor = 1; ?>
                    <?php while ($trade = mysqli_fetch_assoc($result)): ?>
                        <?php
                            $hasil_class = 'pending';
                            if ($trade['hasil'] == 'TP') $hasil_class = 'tp';
                            if ($trade['hasil'] == 'SL') $hasil_class = 'sl';
                        ?>
                        <tr>
                            <td><?php echo $nomor++; ?></td>
                            <td><?php echo htmlspecialchars($trade['currency_pair']); ?></td>
                            <td><?php echo htmlspecialchars($trade['position_type']); ?></td>
                            <td><?php echo (float)$trade['entry_price']; ?></td>
                            <td><?php echo (float)$trade['stop_loss']; ?></td>
                            <td><?php echo (float)$trade['take_profit']; ?></td>
                            <td><span class="status-badge <?php echo $hasil_class; ?>"><?php echo $trade['hasil']; ?></span></td>
                            <td><?php echo $trade['profit_loss'] ? (float)$trade['profit_loss'] : '-'; ?></td>
                            <td><?php echo htmlspecialchars($trade['notes']); ?></td>
                            <td>
                                <a href="trade_edit.php?id=<?php echo $trade['id']; ?>">Edit</a> | 
                                <a href="trade_process.php?action=delete&id=<?php echo $trade['id']; ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 20px;">
                            <?php if (!empty($search_term)): ?>
                                Tidak ada trade yang ditemukan untuk "<?php echo htmlspecialchars($search_term); ?>".
                            <?php else: ?>
                                Anda belum memiliki catatan trade.
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div> </div> <?php require_once 'templates/footer.php'; // Memuat footer ?>