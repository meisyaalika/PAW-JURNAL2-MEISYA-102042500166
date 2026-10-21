<?php
require 'koneksi.php';

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$filter_mk = isset($_GET['mata_kuliah_id']) ? intval($_GET['mata_kuliah_id']) : 0;

$sql = "SELECT tugas.*, mata_kuliah.nama AS nama_mk FROM tugas JOIN mata_kuliah ON tugas.mata_kuliah_id = mata_kuliah.id WHERE 1=1";
$params = [];
$types = "";

if (!empty($keyword)) {
    $sql .= " AND tugas.judul LIKE ?";
    $params[] = "%" . $keyword . "%";
    $types .= "s";
}
if ($filter_mk > 0) {
    $sql .= " AND tugas.mata_kuliah_id = ?";
    $params[] = $filter_mk;
    $types .= "i";
}

$stmt = $conn->prepare($sql);
if (!empty($params)) { $stmt->bind_param($types, ...$params); }
$stmt->execute();
$result = $stmt->get_result();

$list_mk = $conn->query("SELECT id, kode, nama FROM mata_kuliah ORDER BY nama ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Daftar Tugas</title><link rel="stylesheet" href="style.css"></head>
<body>
    <div style="padding: 20px;">
        <h2>Daftar Tugas</h2>
        <a href="index.php">&larr; Kembali</a> | <a href="tambah_tugas.php">+ Tambah Tugas</a><br><br>

        <form action="" method="GET">
            <input type="text" name="keyword" placeholder="Cari tugas..." value="<?= htmlspecialchars($keyword); ?>">
            <select name="mata_kuliah_id">
                <option value="0">Semua Mata Kuliah</option>
                <?php while($mk = $list_mk->fetch_assoc()): ?>
                    <option value="<?= $mk['id']; ?>" <?= ($filter_mk == $mk['id']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($mk['kode'] . ' - ' . $mk['nama']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Cari</button>
            <a href="daftar_tugas.php">Reset</a>
        </form><br>

        <table border="1" cellpadding="8" cellspacing="0" width="100%">
            <tr><th>Judul Tugas</th><th>Mata Kuliah</th><th>Tenggat</th><th>Status</th><th>Aksi</th></tr>
            <?php if($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['judul']); ?></td>
                    <td><?= htmlspecialchars($row['nama_mk']); ?></td>
                    <td><?= $row['tenggat']; ?></td>
                    <td><b><?= $row['status']; ?></b></td>
                    <td>
                        <a href="detail_tugas.php?id=<?= $row['id']; ?>">Detail</a> | 
                        <a href="edit_tugas.php?id=<?= $row['id']; ?>">Edit</a> | 
                        <a href="hapus_tugas.php?id=<?= $row['id']; ?>" onclick="return confirm('Hapus tugas ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center; color:red;">Data tidak ditemukan.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>