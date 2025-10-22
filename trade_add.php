<?php
require_once 'core/auth.php';
require_once 'templates/header.php'; // Muat header
?>

<div class="container">
    <div class="form-card">
        <h3>Tambah Rencana Trade Baru</h3>

        <form action="trade_process.php" method="POST">
            <input type="hidden" name="action" value="create">

            <div class="form-group">
                <label for="currency_pair">Pasangan Mata Uang</label>
                <input type="text" id="currency_pair" name="currency_pair" required placeholder="Contoh: EUR/USD">
            </div>
            <div class="form-group">
                <label for="position_type">Jenis Posisi</label>
                <select id="position_type" name="position_type" required>
                    <option value="Buy">Buy</option>
                    <option value="Sell">Sell</option>
                </select>
            </div>
            <div class="form-group">
                <label for="entry_price">Harga Masuk (Entry Price)</label>
                <input type="text" id="entry_price" inputmode="decimal" name="entry_price" required placeholder="Contoh: 1.2345">
            </div>
            <div class="form-group">
                <label for="stop_loss">Stop Loss</label>
                <input type="text" id="stop_loss" inputmode="decimal" name="stop_loss" required placeholder="Contoh: 1.2300">
            </div>
            <div class="form-group">
                <label for="take_profit">Take Profit</label>
                <input type="text" id="take_profit" inputmode="decimal" name="take_profit" required placeholder="Contoh: 1.2400">
            </div>
            <div class="form-group">
                <label for="notes">Catatan</label>
                <textarea id="notes" name="notes" placeholder="Tulis alasan atau strategi Anda..."></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Simpan Rencana</button>
        </form>
    </div> </div> <?php require_once 'templates/footer.php'; // Muat footer ?>