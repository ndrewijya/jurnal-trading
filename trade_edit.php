<?php
require_once 'core/auth.php';
require_once 'config/database.php';
require_once 'templates/header.php';

$trade_id = $_GET['id'];
$current_user_id = $_SESSION['user_id'];

$query = "SELECT * FROM trades WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "ii", $trade_id, $current_user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$trade = mysqli_fetch_assoc($result);

if (!$trade) {
    echo "<p>Data tidak ditemukan.</p>";
    require_once 'templates/footer.php';
    exit();
}
?>

<h3>Update Hasil Trade</h3><hr>

<form action="trade_process.php" method="POST">
    <input type="hidden" name="action" value="update">
    <input type="hidden" name="trade_id" value="<?php echo $trade['id']; ?>">

    <p><strong>Pasangan Mata Uang:</strong> <?php echo htmlspecialchars($trade['currency_pair']); ?></p>
    <p><strong>Entry Price:</strong> <?php echo (float)$trade['entry_price']; ?></p>
    
    <div style="margin-bottom: 15px;">
        <label>Hasil Trade</label><br>
        <select name="hasil" required style="width: 318px; padding: 8px;">
            <option value="Pending" <?php if ($trade['hasil'] == 'Pending') echo 'selected'; ?>>Pending</option>
            <option value="TP" <?php if ($trade['hasil'] == 'TP') echo 'selected'; ?>>Take Profit (TP)</option>
            <option value="SL" <?php if ($trade['hasil'] == 'SL') echo 'selected'; ?>>Stop Loss (SL)</option>
        </select>
    </div>
    <div style="margin-bottom: 15px;">
        <label>Profit / Loss (isi jika TP atau SL)</label><br>
        <input type="text" inputmode="decimal" name="profit_loss" value="<?php echo (float)$trade['profit_loss']; ?>" style="width: 300px; padding: 8px;" placeholder="Contoh: 250.50">
    </div>
    <div style="margin-bottom: 15px;">
        <label>Catatan</label><br>
        <textarea name="notes" rows="4" style="width: 308px; padding: 8px;"><?php echo htmlspecialchars($trade['notes']); ?></textarea>
    </div>

    <button type="submit" style="padding: 10px 20px;">Update Hasil</button>
</form>

<?php require_once 'templates/footer.php'; ?>