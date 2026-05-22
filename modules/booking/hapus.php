<?php
require_once '../../config/database.php';

// 1. Cek apakah ada parameter 'id' yang dikirim melalui URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // --- TAMBAHAN FITUR LOGGING ---
    // Catat Waktu dan Kalimat Log
    date_default_timezone_set('Asia/Jakarta');
    $waktu = date('d-M-Y H:i:s');
    $pesan_log = "Menghapus data peminjaman dengan ID Booking: " . $id . " pada " . $waktu;

    // Simpan ke tabel log (PERBAIKAN: Menggunakan nama kolom 'Keterangan')
    mysqli_query($koneksi, "INSERT INTO t_log_aktivitas (Keterangan) VALUES ('$pesan_log')");
    // ------------------------------------------------

    // 2. Lakukan proses hapus
    $hapus = mysqli_query($koneksi, "DELETE FROM t_booking WHERE id_booking = '$id'");
    
    if ($hapus) {
        // Berhasil, kembali ke dashboard
        echo "<script>alert('Data berhasil dihapus!'); window.location.href='/index.php';</script>";
    } else {
        // Gagal query
        echo "<script>alert('Gagal menghapus: " . mysqli_error($koneksi) . "'); history.back();</script>";
    }
} else {
    header("Location: /index.php");
    exit;
}
?>