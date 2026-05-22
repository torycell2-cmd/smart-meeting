<?php
require_once '../../config/database.php';

if (!isset($koneksi) && isset($conn)) {
    $koneksi = $conn;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $query = mysqli_query($koneksi, "SELECT * FROM m_karyawan WHERE Id_karyawan='$id'");
    if (!$query) {
        $query = mysqli_query($koneksi, "SELECT * FROM m_karyawan WHERE id_karyawan='$id'") or die(mysqli_error($koneksi));
    }
    
    $data = mysqli_fetch_assoc($query);
    
    // Menarik data NIK, Nama, dan Divisi
    $nik_lama = isset($data['NIK']) ? $data['NIK'] : (isset($data['nik']) ? $data['nik'] : '');
    $nama_k   = isset($data['Nama_karyawan']) ? $data['Nama_karyawan'] : (isset($data['nama_karyawan']) ? $data['nama_karyawan'] : '');
    $divisi   = isset($data['Divisi']) ? $data['Divisi'] : (isset($data['divisi']) ? $data['divisi'] : '');
} else {
    header("Location: /index.php");
    exit;
}

if (isset($_POST['update'])) {
    $nik_baru    = $_POST['nik'];
    $nama_baru   = $_POST['nama_karyawan'];
    $divisi_baru = $_POST['divisi'];

    // Update NIK beserta data lainnya
    $update = mysqli_query($koneksi, "UPDATE m_karyawan SET NIK='$nik_baru', nama_karyawan='$nama_baru', divisi='$divisi_baru' WHERE Id_karyawan='$id'");
    if (!$update) {
        $update = mysqli_query($koneksi, "UPDATE m_karyawan SET NIK='$nik_baru', Nama_karyawan='$nama_baru', Divisi='$divisi_baru' WHERE id_karyawan='$id'") or die(mysqli_error($koneksi));
    }
    
    if ($update) {
        echo "<script>alert('Data Karyawan berhasil diupdate!'); window.location.href='/index.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Karyawan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #f4f6f9; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .modal-card { background: #fff; width: 100%; max-width: 500px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 25px 30px; border: 1px solid #eaeaea; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .modal-header h2 { font-size: 18px; color: #333; font-weight: 600; }
        .close-btn { text-decoration: none; font-size: 24px; color: #999; line-height: 1; }
        hr { border: none; border-top: 1px solid #eee; margin-bottom: 20px; margin-left: -30px; margin-right: -30px; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500; color: #555; }
        .form-control { width: 100%; padding: 10px 12px; border: 1px solid #dcdcdc; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; }
        .form-control:focus { border-color: #007bff; }
        .modal-footer { display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px; }
        .btn { padding: 9px 25px; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none; }
        .btn-simpan { background-color: #28a745; color: #fff; }
        .btn-cancel { background-color: #dc3545; color: #fff; }
    </style>
</head>
<body>
<div class="modal-card">
    <div class="modal-header">
        <h2>Edit Karyawan</h2>
        <a href="/index.php" class="close-btn">&times;</a>
    </div>
    <hr>
    <form method="POST">
        <div class="form-group">
            <label>NIK (Nomor Induk Karyawan)</label>
            <input type="text" name="nik" class="form-control" value="<?= $nik_lama; ?>" required>
        </div>
        <div class="form-group">
            <label>Nama Karyawan</label>
            <input type="text" name="nama_karyawan" class="form-control" value="<?= $nama_k; ?>" required>
        </div>
        <div class="form-group">
            <label>Divisi</label>
            <input type="text" name="divisi" class="form-control" value="<?= $divisi; ?>" required>
        </div>
        <div class="modal-footer">
            <button type="submit" name="update" class="btn btn-simpan">Simpan Perubahan</button>
            <a href="/index.php" class="btn btn-cancel">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>