<?php
require 'koneksi.php';

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$filter_mk = isset($_GET['mata_kuliah_id']) ? intval($_GET['mata_kuliah_id']) : 0;

$sql = "SELECT materi.*, mata_kuliah.nama AS nama_mk FROM materi JOIN mata_kuliah ON materi.mata_kuliah_id = mata_kuliah.id WHERE 1=1";
$params = [];
$types = "";

if (!empty($keyword)) {
    $sql .= " AND materi.judul LIKE ?";
    $params[] = "%" . $keyword . "%";
    $types .= "s";
}
if ($filter_mk > 0) {
    $sql .= " AND materi.mata_kuliah_id = ?";
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
<head><title>Daftar Materi</title><link rel="stylesheet" href="style.css"></head>
<body>
    <div style="padding: 20px;">
        <h2>Daftar Materi</h2>
        <a href="index.php">&larr; Kembali</a> | <a href="tambah_materi.php">+ Tambah Materi</a><br><br>

        <form action="" method="GET">
            <input type="text" name="keyword" placeholder="Cari judul..." value="<?= htmlspecialchars($keyword); ?>">
            <select name="mata_kuliah_id">
                <option value="0">Semua Mata Kuliah</option>
                <?php while($mk = $list_mk->fetch_assoc()): ?>
                    <option value="<?= $mk['id']; ?>" <?= ($filter_mk == $mk['id']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($mk['kode'] . ' - ' . $mk['nama']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Cari</button>
            <a href="daftar_materi.php">Reset</a>
        </form><br>

        <p>Menampilkan <?= $result->num_rows; ?> data.</p>
        <table border="1" cellpadding="8" cellspacing="0" width="100%">
            <tr><th>Judul Materi</th><th>Mata Kuliah</th><th>Aksi</th></tr>
            <?php if($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['judul']); ?></td>
                    <td><?= htmlspecialchars($row['nama_mk']); ?></td>
                    <td>
                        <a href="detail_materi.php?id=<?= $row['id']; ?>">Detail</a> | 
                        <a href="edit_materi.php?id=<?= $row['id']; ?>">Edit</a> | 
                        <a href="hapus_materi.php?id=<?= $row['id']; ?>" onclick="return confirm('Hapus materi ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="3" style="text-align:center; color:red;">Data tidak ditemukan.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>