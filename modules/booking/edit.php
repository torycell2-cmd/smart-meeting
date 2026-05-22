<?php
require_once '../../config/database.php';

// 1. AMBIL DATA LAMA
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query_data = mysqli_query($koneksi, "SELECT * FROM t_booking WHERE id_booking='$id'");
    $data = mysqli_fetch_assoc($query_data);
    
    // Fallback huruf besar/kecil dari database
    $agenda_lama = isset($data['Agenda']) ? $data['Agenda'] : $data['agenda'];
} else {
    header("Location: /index.php");
    exit;
}

// 2. PROSES UPDATE DATA
if (isset($_POST['update'])) {
    $tanggal     = $_POST['tanggal_rapat'];
    $jam_mulai   = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $id_ruang    = $_POST['id_ruang'];
    $id_karyawan = $_POST['id_karyawan'];
    $agenda      = $_POST['agenda'];

    if (strtotime($jam_mulai) >= strtotime($jam_selesai)) {
        echo "<script>alert('Error: Jam Selesai harus lebih besar dari Jam Mulai!'); history.back();</script>";
        exit;
    }

    $update = mysqli_query($koneksi, "UPDATE t_booking SET id_ruang='$id_ruang', id_karyawan='$id_karyawan', tanggal_rapat='$tanggal', jam_mulai='$jam_mulai', jam_selesai='$jam_selesai', agenda='$agenda' WHERE id_booking='$id'") or die(mysqli_error($koneksi));
    
    if($update) {
        echo "<script>alert('Data berhasil diupdate!'); window.location.href='/index.php';</script>";
    } else {
        echo "<script>alert('Gagal update!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Peminjaman</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #f4f6f9; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        
        .modal-card { background: #fff; width: 100%; max-width: 550px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 25px 30px; border: 1px solid #eaeaea; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .modal-header h2 { font-size: 18px; color: #333; font-weight: 600; }
        
        .close-btn { text-decoration: none; font-size: 24px; color: #999; line-height: 1; }
        .close-btn:hover { color: #333; }
        hr { border: none; border-top: 1px solid #eee; margin-bottom: 20px; margin-left: -30px; margin-right: -30px; }
        
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500; color: #555; }
        
        .form-control { width: 100%; padding: 10px 12px; border: 1px solid #dcdcdc; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; color: #333; }
        .form-control:focus { border-color: #007bff; }
        
        .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .time-inputs { display: flex; gap: 8px; align-items: center; }
        .time-inputs span { color: #888; font-weight: 500; }
        
        .modal-footer { display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px; }
        .btn { padding: 9px 25px; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none; transition: 0.2s; }
        
        .btn-simpan { background-color: #28a745; color: #fff; }
        .btn-simpan:hover { background-color: #218838; }
        
        .btn-cancel { background-color: #dc3545; color: #fff; }
        .btn-cancel:hover { background-color: #c82333; }
    </style>
</head>
<body>

<div class="modal-card">
    <div class="modal-header">
        <h2>Edit Peminjaman</h2>
        <a href="/index.php" class="close-btn">&times;</a>
    </div>
    
    <hr>

    <form method="POST">
        <div class="form-group">
            <label>Pilih Tanggal Rapat</label>
            <input type="date" name="tanggal_rapat" class="form-control" value="<?= $data['tanggal_rapat']; ?>" required>
        </div>

        <div class="row-2">
            <div class="form-group">
                <label>Pilih Ruangan</label>
                <select name="id_ruang" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <?php
                    $ruangan = mysqli_query($koneksi, "SELECT * FROM m_ruangan");
                    while($r = mysqli_fetch_assoc($ruangan)) {
                        $selected = ($r['Id_ruangan'] == $data['id_ruang']) ? 'selected' : '';
                        echo "<option value='{$r['Id_ruangan']}' $selected>{$r['Nama_ruangan']}</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Pilih Jam</label>
                <div class="time-inputs">
                    <input type="time" name="jam_mulai" class="form-control" value="<?= substr($data['jam_mulai'], 0, 5); ?>" required>
                    <span>-</span>
                    <input type="time" name="jam_selesai" class="form-control" value="<?= substr($data['jam_selesai'], 0, 5); ?>" required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Nama Peminjam / Divisi</label>
            <select name="id_karyawan" class="form-control" required>
                <option value="">-- Pilih --</option>
                <?php
                $karyawan = mysqli_query($koneksi, "SELECT * FROM m_karyawan");
                while($k = mysqli_fetch_assoc($karyawan)) {
                    $id_k = isset($k['Id_karyawan']) ? $k['Id_karyawan'] : $k['id_karyawan'];
                    $nama_k = isset($k['Nama_karyawan']) ? $k['Nama_karyawan'] : $k['nama_karyawan'];
                    $divisi = isset($k['Divisi']) ? $k['Divisi'] : $k['divisi'];
                    
                    $selected = ($id_k == $data['id_karyawan']) ? 'selected' : '';
                    echo "<option value='{$id_k}' $selected>{$nama_k} / {$divisi}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Agenda Rapat</label>
            <input type="text" name="agenda" class="form-control" value="<?= $agenda_lama; ?>" required>
        </div>

        <div class="modal-footer">
            <button type="submit" name="update" class="btn btn-simpan">Simpan Perubahan</button>
            <a href="/index.php" class="btn btn-cancel">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>