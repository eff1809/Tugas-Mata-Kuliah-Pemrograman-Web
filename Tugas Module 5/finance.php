<?php
declare(strict_types=1);
session_start();
require_once 'Transaction.php';

if (!isset($_SESSION['balance'])) { $_SESSION['balance'] = 0.0; }
if (!isset($_SESSION['history'])) { $_SESSION['history'] = []; }
if (empty($_SESSION['csrf_token'])) { $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); }

$message = '';
$status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $message = 'Error: Token CSRF tidak valid. Permintaan ditolak!';
        $status = 'error';
    } else {
        $type = $_POST['type'] ?? '';
        $amountInput = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);

        if ($amountInput === false || $amountInput <= 0) {
            $message = 'Error: Jumlah transaksi wajib berupa angka desimal positif!';
            $status = 'error';
        } else {
            $id = uniqid('TRX-');
            $transaction = new Transaction($id, $type, $amountInput);
            $result = $transaction->process($_SESSION['balance']);

            if ($result === true) {
                $_SESSION['history'][] = [
                    'id' => $transaction->getId(),
                    'type' => match($transaction->getType()) { 'deposit' => 'Deposit', 'withdraw' => 'Penarikan', default => 'Tidak Diketahui' },
                    'amount' => $transaction->getAmount()
                ];
                $message = 'Transaksi berhasil diproses!';
                $status = 'success';
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            } else {
                $message = $result;
                $status = 'error';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Keuangan</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f4f6f8; padding: 20px; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .balance { font-size: 1.5rem; color: #0b3d63; font-weight: bold; }
        .alert { padding: 10px; border-radius: 4px; margin-bottom: 20px; }
        .alert.success { background: #d1fae5; color: #065f46; border: 1px solid #34d399; }
        .alert.error { background: #fee2e2; color: #991b1b; border: 1px solid #f87171; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 500; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background: #0b3d63; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; }
        button:hover { background: #082c48; }
        table { width: 100%; border-collapse: collapse; margin-top: 25px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f8f9fa; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sistem Keuangan Sederhana</h2>
        
        <?php if ($message): ?>
            <!-- Proteksi XSS pada pesan menggunakan htmlspecialchars -->
            <div class="alert <?= $status ?>"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <p>Saldo Saat Ini: <span class="balance">Rp <?= number_format($_SESSION['balance'], 2, ',', '.') ?></span></p>

        <form action="" method="POST">
            <!-- Proteksi CSRF -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
            
            <div class="form-group">
                <label for="type">Jenis Transaksi</label>
                <select name="type" id="type" required>
                    <option value="deposit">Deposit (Simpan)</option>
                    <option value="withdraw">Penarikan (Ambil)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="amount">Jumlah (Desimal Positif)</label>
                <input type="number" name="amount" id="amount" step="0.01" min="0.01" required placeholder="Contoh: 50000.50">
            </div>
            
            <button type="submit">Proses Transaksi</button>
        </form>

        <!-- Tabel Riwayat Transaksi -->
        <?php if (!empty($_SESSION['history'])): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Membalik urutan agar yang terbaru muncul di atas -->
                    <?php foreach(array_reverse($_SESSION['history']) as $trx): ?>
                    <tr>
                        <!-- Proteksi XSS (Cross-Site Scripting) ketat pada output riwayat transaksi -->
                        <td><?= htmlspecialchars($trx['id'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($trx['type'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>Rp <?= number_format($trx['amount'], 2, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>