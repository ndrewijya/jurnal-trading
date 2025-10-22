<?php
require_once 'core/auth.php';
require_once 'config/database.php';
require_once 'templates/header.php'; // Muat header

$trade_id = $_GET['id'];
$current_user_id = $_SESSION['user_id'];
$query = "SELECT * FROM trades WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "ii", $trade_id, $current_user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$trade = mysqli_fetch_assoc($result);

if (!$trade) {
    echo "<div class='container'><p>Data tidak ditemukan.</p></div>";
    require_once 'templates/footer.php';
    exit();
}
?>

<div class="container">
    <h3>Update Hasil Trade</h3>
    <hr style="margin: 20px 0;">

    <form action="trade_process.php" method="POST" style="max-width: 600px;">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="trade_id" value="<?php echo $trade['id']; ?>">

        <p><strong>Pasangan Mata Uang:</strong> <?php echo htmlspecialchars($trade['currency_pair']); ?></p>
        <p><strong>Entry Price:</strong> <?php echo (float)$trade['entry_price']; ?></p>
        
        <div class="form-group">
            <label for="hasil">Hasil Trade</label>
            <select id="hasil" name="hasil" required>
                <option value="Pending" <?php if ($trade['hasil'] == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="TP" <?php if ($trade['hasil'] == 'TP') echo 'selected'; ?>>Take Profit (TP)</option>
                <option value="SL" <?php if ($trade['hasil'] == 'SL') echo 'selected'; ?>>Stop Loss (SL)</option>
            </select>
        </div>
        <div class="form-group">
            <label for="profit_loss">Profit / Loss (isi jika TP atau SL)</label>
            <input type="text" id="profit_loss" inputmode="decimal" name="profit_loss" value="<?php echo (float)$trade['profit_loss']; ?>" placeholder="Contoh: 250.50">
        </div>
        <div class="form-group">
            <label for="notes">Catatan</label>
            <textarea id="notes" name="notes"><?php echo htmlspecialchars($trade['notes']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="width: auto;">Update Hasil</button>
    </form>
</div>

<?php require_once 'templates/footer.php'; // Muat footer ?>