<?php
require_once 'core/auth.php';
require_once 'templates/header.php';
?>

<h3>Tambah Rencana Trade Baru</h3><hr>

<form action="trade_process.php" method="POST">
    <input type="hidden" name="action" value="create">

    <div style="margin-bottom: 15px;">
        <label>Pasangan Mata Uang</label><br>
        <input type="text" name="currency_pair" required style="width: 300px; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label>Jenis Posisi</label><br>
        <select name="position_type" required style="width: 318px; padding: 8px;">
            <option value="Buy">Buy</option>
            <option value="Sell">Sell</option>
        </select>
    </div>
    <div style="margin-bottom: 15px;">
        <label>Harga Masuk (Entry Price)</label><br>
        <input type="text" inputmode="decimal" name="entry_price" required style="width: 300px; padding: 8px;" placeholder="Contoh: 1.2345">
    </div>
    <div style="margin-bottom: 15px;">
        <label>Stop Loss</label><br>
        <input type="text" inputmode="decimal" name="stop_loss" required style="width: 300px; padding: 8px;" placeholder="Contoh: 1.2300">
    </div>
    <div style="margin-bottom: 15px;">
        <label>Take Profit</label><br>
        <input type="text" inputmode="decimal" name="take_profit" required style="width: 300px; padding: 8px;" placeholder="Contoh: 1.2400">
    </div>
    <div style="margin-bottom: 15px;">
        <label>Catatan</label><br>
        <textarea name="notes" rows="4" style="width: 308px; padding: 8px;"></textarea>
    </div>
    <button type="submit" style="padding: 10px 20px;">Simpan Rencana</button>
</form>

<?php require_once 'templates/footer.php'; ?>