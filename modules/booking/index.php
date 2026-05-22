<?php 
include '../header.php'; 
require '../config/database.php'; 

// Menggunakan JOIN agar nama ruangan dan karyawan muncul
$query = mysqli_query($koneksi, "
    SELECT b.*, r.Nama_ruangan, k.nama_karyawan 
    FROM t_booking b 
    JOIN m_ruangan r ON b.id_ruang = r.Id_ruangan 
    JOIN m_karyawan k ON b.id_karyawan = k.Id_karyawan
    ORDER BY b.tanggal_rapat DESC
");
?>

<h2>Daftar Peminjaman</h2>
<a href="tambah.php" class="btn-tambah">+ Tambah Peminjaman</a>

<table border="1" width="100%" cellpadding="10" cellspacing="0">
    <thead>
        <tr style="background: #f4f4f4;">
            <th>No</th>
            <th>Tanggal</th>
            <th>Ruangan</th>
            <th>Peminjam</th>
            <th>Agenda</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=1; while($b = mysqli_fetch_assoc($query)) { ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $b['tanggal_rapat']; ?></td>
            <td><?= $b['Nama_ruangan']; ?></td>
            <td><?= $b['nama_karyawan']; ?></td>
            <td><?= $b['Agenda']; ?></td>
            <td>
                <a href="edit.php?id=<?= $b['id_booking']; ?>">Edit</a> | 
                <a href="hapus.php?id=<?= $b['id_booking']; ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<?php include '../footer.php'; ?>