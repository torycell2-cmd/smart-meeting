<?php
require_once '../../config/database.php';

if (!isset($koneksi) && isset($conn)) {
    $koneksi = $conn;
}

if (isset($_POST['simpan'])) {
    $nik           = $_POST['nik'];
    $nama_karyawan = $_POST['nama_karyawan'];
    $divisi        = $_POST['divisi'];

    // Menambahkan NIK ke dalam query
    $query_simpan = "INSERT INTO m_karyawan (NIK, nama_karyawan, divisi) VALUES ('$nik', '$nama_karyawan', '$divisi')";
    $simpan = mysqli_query($koneksi, $query_simpan);
    
    if (!$simpan) {
        $query_alt = "INSERT INTO m_karyawan (NIK, Nama_karyawan, Divisi) VALUES ('$nik', '$nama_karyawan', '$divisi')";
        $simpan = mysqli_query($koneksi, $query_alt) or die(mysqli_error($koneksi));
    }
    
    if ($simpan) {
        echo "<script>alert('Karyawan berhasil ditambahkan!'); window.location.href='/index.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Karyawan</title>
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
        .btn-simpan { background-color: #007bff; color: #fff; }
        .btn-cancel { background-color: #dc3545; color: #fff; }
    </style>
</head>
<body>
<div class="modal-card">
    <div class="modal-header">
        <h2>Tambah Karyawan</h2>
        <a href="/index.php" class="close-btn">&times;</a>
    </div>
    <hr>
    <form method="POST">
        <div class="form-group">
            <label>NIK (Nomor Induk Karyawan)</label>
            <input type="text" name="nik" class="form-control" placeholder="Contoh: 12345678" required>
        </div>
        <div class="form-group">
            <label>Nama Karyawan</label>
            <input type="text" name="nama_karyawan" class="form-control" placeholder="Nama Lengkap Karyawan" required>
        </div>
        <div class="form-group">
            <label>Divisi / Bagian</label>
            <input type="text" name="divisi" class="form-control" placeholder="Contoh: IT, HRD, NOC" required>
        </div>
        <div class="modal-footer">
            <button type="submit" name="simpan" class="btn btn-simpan">Simpan</button>
            <a href="/index.php" class="btn btn-cancel">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>