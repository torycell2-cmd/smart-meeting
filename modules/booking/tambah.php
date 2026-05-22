<?php
require_once '../../config/database.php';

// PROSES SIMPAN DATA
if (isset($_POST['simpan'])) {
    $tanggal     = $_POST['tanggal_rapat'];
    $jam_mulai   = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $id_ruang    = $_POST['id_ruang'];
    $id_karyawan = $_POST['id_karyawan'];
    $agenda      = $_POST['agenda'];

    // 1. Validasi Jam Terbalik
    if (strtotime($jam_mulai) >= strtotime($jam_selesai)) {
        echo "<script>alert('Error: Jam Selesai harus lebih besar dari Jam Mulai!'); history.back();</script>";
        exit;
    }

    // 2. Validasi Double Booking (Cek Irisan Waktu Anti-Bentrok)
    $cek_bentrok = mysqli_query($koneksi, "
        SELECT b.*, k.Divisi, k.Nama_karyawan 
        FROM t_booking b
        JOIN m_karyawan k ON b.id_karyawan = k.Id_karyawan
        WHERE b.id_ruang = '$id_ruang' 
        AND b.tanggal_rapat = '$tanggal'
        AND (
            ('$jam_mulai' >= b.jam_mulai AND '$jam_mulai' < b.jam_selesai) OR
            ('$jam_selesai' > b.jam_mulai AND '$jam_selesai' <= b.jam_selesai) OR
            ('$jam_mulai' <= b.jam_mulai AND '$jam_selesai' >= b.jam_selesai)
        )
    ") or die(mysqli_error($koneksi));

    if (mysqli_num_rows($cek_bentrok) > 0) {
        $data_bentrok = mysqli_fetch_assoc($cek_bentrok);
        // Mengantisipasi perbedaan huruf besar/kecil pada kolom divisi & agenda
        $divisi_terpakai = isset($data_bentrok['Divisi']) ? $data_bentrok['Divisi'] : $data_bentrok['divisi'];
        $agenda_terpakai = isset($data_bentrok['Agenda']) ? $data_bentrok['Agenda'] : $data_bentrok['agenda'];
        
        echo "<script>alert('Maaf, ruangan sudah digunakan oleh Divisi {$divisi_terpakai} untuk agenda {$agenda_terpakai}!'); history.back();</script>";
        exit;
    }

    // 3. Simpan ke Database
    $query_simpan = "INSERT INTO t_booking (id_karyawan, id_ruang, tanggal_rapat, jam_mulai, jam_selesai, agenda, status) 
                     VALUES ('$id_karyawan', '$id_ruang', '$tanggal', '$jam_mulai', '$jam_selesai', '$agenda', 'Pinjam')";
    
    if (mysqli_query($koneksi, $query_simpan)) {
        // PERBAIKAN: Menggunakan path absolut '/' agar pasti kembali ke dashboard utama
        echo "<script>alert('Peminjaman berhasil disimpan!'); window.location.href='/index.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Peminjaman</title>
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
        
        .btn-simpan { background-color: #007bff; color: #fff; }
        .btn-simpan:hover { background-color: #0056b3; }
        
        .btn-cancel { background-color: #dc3545; color: #fff; }
        .btn-cancel:hover { background-color: #c82333; }
    </style>
</head>
<body>

<div class="modal-card">
    <div class="modal-header">
        <h2>Tambah Peminjaman</h2>
        <a href="/index.php" class="close-btn">&times;</a>
    </div>
    
    <hr>

    <form action="" method="POST">
        <div class="form-group">
            <label>Pilih Tanggal Rapat</label>
            <input type="date" name="tanggal_rapat" class="form-control" required>
        </div>

        <div class="row-2">
            <div class="form-group">
                <label>Pilih Ruangan</label>
                <select name="id_ruang" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <?php
                    $ruangan = mysqli_query($koneksi, "SELECT * FROM m_ruangan");
                    while($r = mysqli_fetch_assoc($ruangan)) {
                        echo "<option value='{$r['Id_ruangan']}'>{$r['Nama_ruangan']}</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Pilih Jam</label>
                <div class="time-inputs">
                    <input type="time" name="jam_mulai" class="form-control" required>
                    <span>-</span>
                    <input type="time" name="jam_selesai" class="form-control" required>
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
                    
                    echo "<option value='{$id_k}'>{$nama_k} / {$divisi}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Agenda Rapat</label>
            <input type="text" name="agenda" class="form-control" placeholder="Contoh: Meeting Bulanan" required>
        </div>

        <div class="modal-footer">
            <button type="submit" name="simpan" class="btn btn-simpan">Simpan</button>
            <a href="/index.php" class="btn btn-cancel">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>