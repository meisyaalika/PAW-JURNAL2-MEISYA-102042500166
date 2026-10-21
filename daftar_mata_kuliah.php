<?php
require 'koneksi.php';

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

if (!empty($keyword)) {
    $stmt = $conn->prepare("SELECT * FROM mata_kuliah WHERE kode LIKE ? OR nama LIKE ? ORDER BY nama ASC");
    $searchTerm = "%" . $keyword . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM mata_kuliah ORDER BY nama ASC");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Mata Kuliah - LMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div style="padding: 20px;">
        <h2>Daftar Mata Kuliah</h2>
        <a href="index.php">&larr; Kembali ke Beranda</a> | 
        <a href="tambah_mata_kuliah.php" class="btn">+ Tambah Mata Kuliah</a>
        <br><br>

        <form action="" method="GET" style="margin-bottom: 20px;">
            <input type="text" name="keyword" placeholder="Cari kode atau nama..." value="<?= htmlspecialchars($keyword); ?>">
            <button type="submit">Cari</button>
            <a href="daftar_mata_kuliah.php">Reset</a>
        </form>

        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <tr>
                <th>Kode & Nama</th>
                <th>Dosen / Semester</th>
                <th>Aksi</th>
            </tr>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <b><?= htmlspecialchars($row['kode']); ?></b><br>
                        <?= htmlspecialchars($row['nama']); ?>
                    </td>
                    <td><?= htmlspecialchars($row['dosen']); ?> / Semester <?= $row['semester']; ?></td>
                    <td>
                        <a href="edit_mata_kuliah.php?id=<?= $row['id']; ?>">Edit</a> | 
                        <a href="hapus_mata_kuliah.php?id=<?= $row['id']; ?>">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="3" style="text-align: center;">Data tidak ditemukan.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>