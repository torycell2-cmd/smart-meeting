<?php
require_once '../config/database.php';
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Data Ruangan</title>
</head>
<body>
    <div class="container">
    <h2>Daftar Ruangan</h2>
    <a href="tambah.php" class="btn-tambah">+ Tambah Ruangan</a>

    <div class="grid-ruangan">
        <?php
        $query = mysqli_query($koneksi, "SELECT * FROM m_ruangan");
        while($r = mysqli_fetch_assoc($query)) {
        ?>
            <div class="card-ruangan">
                <h3><?= $r['nama_ruangan']; ?></h3>
                <p><strong>Kapasitas:</strong> <?= $r['kapasitas']; ?> orang</p>
                <p><strong>Fasilitas:</strong> <?= $r['fasilitas']; ?></p>
                <p><strong>Status:</strong> <?= $r['keterangan']; ?></p>
                
                <div style="margin-top: 15px;">
                    <a href="edit.php?id=<?= $r['id_ruangan']; ?>" class="btn-edit">Edit</a>
                    <a href="hapus.php?id=<?= $r['id_ruangan']; ?>" class="btn-hapus">Hapus</a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
</body>
</html>
