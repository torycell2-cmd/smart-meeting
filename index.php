<?php
require_once 'config/database.php';
date_default_timezone_set('Asia/Jakarta');
$hari_ini = date('Y-m-d');
$jam_sekarang = date('H:i:s');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Smart-Meeting System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; box-sizing: border-box; }
        body { background: #f4f6f9; padding: 20px; }
        .container { max-width: 1000px; margin: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        
        /* Card Status Ruangan */
        .grid-ruangan { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 30px; }
        .card { background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .status-ready { color: #155724; background: #d4edda; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .status-used { color: #721c24; background: #f8d7da; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        
        /* Tabel */
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; }
        th { background: #343a40; color: white; padding: 12px; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #dee2e6; }
        .btn-add { background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; }
        .btn-del { color: #dc3545; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Dashboard Peminjaman</h1>
        <div>
            <a href="logs.php" class="btn-add" style="background:#6c757d; margin-right: 10px;">Lihat Logs</a>
            <a href="/modules/booking/tambah.php" class="btn-add">+ Tambah Peminjaman</a>
        </div>
    </div>

    <h3>Status Ruangan</h3>
    <div class="grid-ruangan">
        <?php
        $ruang = mysqli_query($koneksi, "SELECT * FROM m_ruangan");
        while($r = mysqli_fetch_assoc($ruang)) {
            $cek = mysqli_query($koneksi, "SELECT * FROM t_booking WHERE id_ruang = '{$r['Id_ruangan']}' AND tanggal_rapat = '$hari_ini' AND '$jam_sekarang' BETWEEN jam_mulai AND jam_selesai");
            $is_used = mysqli_num_rows($cek) > 0;
        ?>
            <div class="card">
                <strong><?= $r['Nama_ruangan']; ?></strong><br>
                <small>Kapasitas: <?= $r['Kapasitas']; ?></small><br>
                <span class="<?= $is_used ? 'status-used' : 'status-ready'; ?>">
                    <?= $is_used ? 'Digunakan' : 'Ready'; ?>
                </span>
            </div>
        <?php } ?>
    </div>

    <h3>Daftar Peminjaman</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Ruangan</th>
                <th>Waktu</th>
                <th>Peminjam</th>
                <th>Agenda</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $data = mysqli_query($koneksi, "SELECT b.*, r.Nama_ruangan, k.nama_karyawan FROM t_booking b JOIN m_ruangan r ON b.id_ruang = r.Id_ruangan JOIN m_karyawan k ON b.id_karyawan = k.Id_karyawan ORDER BY b.tanggal_rapat DESC");
            $no = 1;
            while($b = mysqli_fetch_assoc($data)) { ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $b['Nama_ruangan']; ?></td>
                    <td><?= $b['tanggal_rapat'] . ' (' . substr($b['jam_mulai'],0,5) . '-' . substr($b['jam_selesai'],0,5) . ')'; ?></td>
                    <td><?= $b['nama_karyawan']; ?></td>
                    <td><?= $b['Agenda']; ?></td>
                    <td>
                        <a href="/modules/booking/edit.php?id=<?= $b['id_booking']; ?>" style="margin-right:10px; color:#007bff; text-decoration:none; font-weight:bold;">Edit</a>
                        <a href="/modules/booking/hapus.php?id=<?= $b['id_booking']; ?>" class="btn-del" onclick="return confirm('Hapus data peminjaman ini?')">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="header" style="margin-top: 50px;">
        <h3>Daftar Ruangan</h3>
        <a href="/modules/ruangan/tambah.php" class="btn-add">+ Tambah Ruangan</a>
    </div>
    
    <table style="margin-bottom: 20px;">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Ruangan</th>
                <th>Kapasitas</th>
                <th width="15%">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $data_ruangan = mysqli_query($koneksi, "SELECT * FROM m_ruangan ORDER BY Id_ruangan ASC");
            $no_r = 1;
            while($r = mysqli_fetch_assoc($data_ruangan)) { ?>
                <tr>
                    <td><?= $no_r++; ?></td>
                    <td><?= $r['Nama_ruangan']; ?></td>
                    <td><?= $r['Kapasitas']; ?> Orang</td>
                    <td>
                        <a href="/modules/ruangan/edit.php?id=<?= $r['Id_ruangan']; ?>" style="margin-right:10px; text-decoration:none; color:#007bff; font-weight:bold;">Edit</a>
                        <a href="/modules/ruangan/hapus.php?id=<?= $r['Id_ruangan']; ?>" class="btn-del" onclick="return confirm('Yakin ingin menghapus ruangan ini?')">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="header" style="margin-top: 50px;">
        <h3>Daftar Karyawan</h3>
        <a href="/modules/karyawan/tambah.php" class="btn-add">+ Tambah Karyawan</a>
    </div>
    
    <table style="margin-bottom: 50px;">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Karyawan</th>
                <th>Divisi</th>
                <th width="15%">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $data_karyawan = mysqli_query($koneksi, "SELECT * FROM m_karyawan ORDER BY Id_karyawan ASC");
            $no_k = 1;
            while($k = mysqli_fetch_assoc($data_karyawan)) { 
                // Fallback untuk antisipasi huruf besar/kecil di database
                $nama_k = isset($k['Nama_karyawan']) ? $k['Nama_karyawan'] : $k['nama_karyawan'];
                $divisi = isset($k['Divisi']) ? $k['Divisi'] : $k['divisi'];
                $id_k   = isset($k['Id_karyawan']) ? $k['Id_karyawan'] : $k['id_karyawan'];
            ?>
                <tr>
                    <td><?= $no_k++; ?></td>
                    <td><?= $nama_k; ?></td>
                    <td><?= $divisi; ?></td>
                    <td>
                        <a href="/modules/karyawan/edit.php?id=<?= $id_k; ?>" style="margin-right:10px; text-decoration:none; color:#007bff; font-weight:bold;">Edit</a>
                        <a href="/modules/karyawan/hapus.php?id=<?= $id_k; ?>" class="btn-del" onclick="return confirm('Yakin ingin menghapus karyawan ini?')">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    </div>

</body>
</html>