<?php
require_once '../../config/database.php';

// Menyelaraskan nama variabel koneksi agar anti-error
if (!isset($koneksi) && isset($conn)) {
    $koneksi = $conn;
}

// 1. Cek apakah ada parameter 'id' yang dikirim melalui URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // --- TAMBAHAN FITUR LOGGING (RIWAYAT AKTIVITAS) ---
    date_default_timezone_set('Asia/Jakarta');
    $waktu = date('d-M-Y H:i:s');
    $pesan_log = "Menghapus data karyawan dengan ID: " . $id . " pada " . $waktu;

    // Simpan ke tabel log (menggunakan kolom 'Keterangan')
    mysqli_query($koneksi, "INSERT INTO t_log_aktivitas (Keterangan) VALUES ('$pesan_log')");
    // ------------------------------------------------

    // 2. Lakukan proses hapus utama
    $hapus = mysqli_query($koneksi, "DELETE FROM m_karyawan WHERE Id_karyawan = '$id'");
    
    // Fallback jika primary key menggunakan huruf kecil
    if (!$hapus) {
        $hapus = mysqli_query($koneksi, "DELETE FROM m_karyawan WHERE id_karyawan = '$id'");
    }
    
    if ($hapus) {
        // Berhasil, kembali ke dashboard
        echo "<script>alert('Data Karyawan berhasil dihapus!'); window.location.href='/index.php';</script>";
    } else {
        // Gagal query (biasanya karena ID karyawan tersebut masih nyangkut/direlasikan di tabel peminjaman)
        echo "<script>alert('Gagal menghapus! Pastikan karyawan ini tidak memiliki jadwal peminjaman aktif.'); history.back();</script>";
    }
} else {
    // Jika tidak ada ID, jangan eksekusi delete, langsung balik ke index
    header("Location: /index.php");
    exit;
}
?>