<?php 
// Sertakan header agar desain konsisten
include '../header.php'; 
require '../config/database.php'; 
?>

<div class="header-section" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Data Karyawan</h2>
    <a href="tambah.php" class="btn-tambah" style="text-decoration: none; padding: 10px 20px; background: #007bff; color: white; border-radius: 6px;">+ Tambah Karyawan</a>
</div>

<table border="1" width="100%" cellspacing="0" cellpadding="10">
    <thead>
        <tr style="background: #f4f4f4;">
            <th>No</th>
            <th>NIK</th>
            <th>Nama Karyawan</th>
            <th>Divisi</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Mengambil data dari tabel m_karyawan (Pastikan nama kolom sesuai database)
        // Saya asumsikan nama kolom: Id_karyawan, NIK, Nama_karyawan, Divisi
        $query = mysqli_query($koneksi, "SELECT * FROM m_karyawan");
        $no = 1;
        
        if (mysqli_num_rows($query) > 0) {
            while($k = mysqli_fetch_assoc($query)) { ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $k['NIK']; ?></td>
                    <td><?= $k['Nama_karyawan']; ?></td>
                    <td><?= $k['Divisi']; ?></td>
                    <td>
                        <a href="edit.php?id=<?= $k['Id_karyawan']; ?>">Edit</a> | 
                        <a href="hapus.php?id=<?= $k['Id_karyawan']; ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
                    </td>
                </tr>
            <?php } 
        } else {
            echo "<tr><td colspan='5' align='center'>Belum ada data karyawan</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php include '../footer.php'; ?>