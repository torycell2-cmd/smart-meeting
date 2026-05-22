<?php
$host     = "127.0.0.1";
$user     = "root"; 
$password = "";     // Sesuaikan jika Anda memberi password di Laragon
$database = "db_office_smart";
$port     = 3307;   // <--- INI PENTING: Menyesuaikan dengan port baru Laragon
?>
<?php
// Menambahkan parameter port ke dalam fungsi koneksi
$koneksi = mysqli_connect($host, $user, $password, $database, $port);

// Cek apakah koneksi berhasil
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>