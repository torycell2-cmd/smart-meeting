<?php
require_once '../../config/database.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Jika ingin menambah log aktivitas hapus ruangan, tambahkan kodenya di sini seperti sebelumnya

    $hapus = mysqli_query($koneksi, "DELETE FROM m_ruangan WHERE Id_ruangan = '$id'");
    
    if ($hapus) {
        echo "<script>alert('Data Ruangan berhasil dihapus!'); window.location.href='/index.php';</script>";
    } else {
        // Cek jika ruangan sedang dipakai di t_booking, tidak bisa dihapus
        echo "<script>alert('Gagal menghapus! Pastikan ruangan ini tidak sedang direlasikan dengan jadwal peminjaman.'); history.back();</script>";
    }
} else {
    header("Location: /index.php");
}
?>