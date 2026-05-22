<?php
require_once 'config/database.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Log Aktivitas - Smart-Meeting</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; box-sizing: border-box; }
        body { background: #f4f6f9; padding: 20px; }
        .container { max-width: 1000px; margin: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        th { background: #343a40; color: white; padding: 12px; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #dee2e6; line-height: 1.6; }
        tr:hover { background: #fdfdfd; }
        
        .btn-back { background: #6c757d; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: 500; }
        .btn-back:hover { background: #5a6268; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Riwayat Aktivitas (Logs)</h1>
        <a href="index.php" class="btn-back">&larr; Kembali ke Dashboard</a>
    </div>

    <table>
        <thead>
            <tr>
                <th width="10%">ID Log</th>
                <th>Detail Aktivitas & Waktu Kejadian</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Mengambil data berdasarkan struktur tabel Anda yang asli
            $query_log = mysqli_query($koneksi, "SELECT * FROM t_log_aktivitas ORDER BY Id_log DESC") or die(mysqli_error($koneksi));
            
            if(mysqli_num_rows($query_log) > 0) {
                while($log = mysqli_fetch_assoc($query_log)) { ?>
                    <tr>
                        <td><strong>#<?= $log['Id_log']; ?></strong></td>
                        <td><?= $log['Log']; ?></td>
                    </tr>
                <?php }
            } else {
                echo "<tr><td colspan='2' style='text-align:center; padding: 20px; color: #666;'>Belum ada riwayat aktivitas di dalam sistem.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>