<?php
require_once 'core/auth.php';
require_once 'config/database.php';
require_once 'templates/header.php';

$current_user_id = $_SESSION['user_id'];

// --- LOGIKA STATISTIK DASHBOARD (tidak berubah, tetap menghitung total) ---
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
// --- LOGIKA STATISTIK SELESAI ---

// --- LOGIKA PENCARIAN UNTUK TABEL DIMULAI ---
$search_term = $_GET['search'] ?? ''; // Ambil kata kunci pencarian

if (!empty($search_term)) {
    // Jika ada pencarian, modifikasi query untuk tabel
    $query = "SELECT * FROM trades WHERE user_id = ? AND currency_pair LIKE ? ORDER BY id DESC";
    $stmt = mysqli_prepare($koneksi, $query);
    $search_like = "%" . $search_term . "%"; // Tambahkan wildcard
    mysqli_stmt_bind_param($stmt, "is", $current_user_id, $search_like);
} else {
    // Query default jika tidak ada pencarian
    $query = "SELECT * FROM trades WHERE user_id = ? ORDER BY id DESC";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $current_user_id);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
// --- LOGIKA PENCARIAN SELESAI ---
?>

<div class="welcome-msg" style="margin-bottom: 20px;">
    <h2>Ringkasan Performa</h2>
</div>

<div class="stats-container" style="display: flex; gap: 20px; margin-bottom: 40px; flex-wrap: wrap;">
    <div class="stat-card" style="flex: 1; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); min-width: 200px;">
        <h4 style="margin-top: 0;">Total Net Profit</h4>
        <p style="font-size: 24px; font-weight: bold; color: <?php echo ($total_pl >= 0) ? 'green' : 'red'; ?>;"><?php echo number_format($total_pl ?? 0, 2); ?></p>
    </div>
    <div class="stat-card" style="flex: 1; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); min-width: 200px;">
        <h4 style="margin-top: 0;">Total Trade</h4>
        <p style="font-size: 24px; font-weight: bold;"><?php echo $total_trades; ?></p>
    </div>
    <div class="stat-card" style="flex: 1; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); min-width: 200px;">
        <h4 style="margin-top: 0;">Posisi Terbuka</h4>
        <p style="font-size: 24px; font-weight: bold;"><?php echo $total_open; ?></p>
    </div>
    <div class="stat-card" style="flex: 1; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); min-width: 200px;">
        <h4 style="margin-top: 0;">Profit Beruntun</h4>
        <p style="font-size: 24px; font-weight: bold; color: green;"><?php echo $max_win_streak; ?> Kali</p>
    </div>
    <div class="stat-card" style="flex: 1; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); min-width: 200px;">
        <h4 style="margin-top: 0;">Loss Beruntun</h4>
        <p style="font-size: 24px; font-weight: bold; color: red;"><?php echo $max_loss_streak; ?> Kali</p>
    </div>
</div>

<hr style="margin-bottom: 40px;">

<div class="search-container" style="margin-bottom: 20px;">
    <form action="dashboard.php" method="GET">
        <label for="search" style="font-weight: bold;">Cari Pasangan Mata Uang:</label>
        <input type="text" id="search" name="search" placeholder="Contoh: EUR/USD" 
               style="padding: 8px; width: 250px;" 
               value="<?php echo htmlspecialchars($search_term); ?>">
        <button type="submit" style="padding: 9px 15px; cursor: pointer;">Cari</button>
        <a href="dashboard.php" style="padding: 9px 15px; text-decoration: none; background-color: #6c757d; color: white; border-radius: 4px; font-size: 0.9em; margin-left: 5px;">Reset</a>
    </form>
</div>

<div class="welcome-msg" style="margin-bottom: 20px;">
    <h2>Riwayat Trading Anda</h2>
</div>
<table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th style="padding: 8px;">No.</th>
            <th style="padding: 8px;">Pasangan Mata Uang</th>
            <th style="padding: 8px;">Posisi</th>
            <th style="padding: 8px;">Entry Price</th>
            <th style="padding: 8px;">Stop Loss</th>
            <th style="padding: 8px;">Take Profit</th>
            <th style="padding: 8px;">Hasil</th>
            <th style="padding: 8px;">Profit/Loss</th>
            <th style="padding: 8px;">Catatan</th>
            <th style="padding: 8px;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php $nomor = 1; ?>
            <?php while ($trade = mysqli_fetch_assoc($result)): ?>
                <?php
                    $hasil_color = 'blue';
                    if ($trade['hasil'] == 'TP') $hasil_color = 'green';
                    if ($trade['hasil'] == 'SL') $hasil_color = 'red';
                ?>
                <tr>
                    <td style="padding: 8px;"><?php echo $nomor++; ?></td>
                    <td style="padding: 8px;"><?php echo htmlspecialchars($trade['currency_pair']); ?></td>
                    <td style="padding: 8px;"><?php echo htmlspecialchars($trade['position_type']); ?></td>
                    <td style="padding: 8px;"><?php echo (float)$trade['entry_price']; ?></td>
                    <td style="padding: 8px;"><?php echo (float)$trade['stop_loss']; ?></td>
                    <td style="padding: 8px;"><?php echo (float)$trade['take_profit']; ?></td>
                    <td style="padding: 8px; font-weight: bold; color: <?php echo $hasil_color; ?>;"><?php echo $trade['hasil']; ?></td>
                    <td style="padding: 8px;"><?php echo $trade['profit_loss'] ? (float)$trade['profit_loss'] : '-'; ?></td>
                    <td style="padding: 8px;"><?php echo htmlspecialchars($trade['notes']); ?></td>
                    <td style="padding: 8px;">
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

<?php require_once 'templates/footer.php'; ?>