<?php
require 'koneksi.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("SELECT * FROM tugas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) { header("Location: daftar_tugas.php"); exit; }
$list_mk = $conn->query("SELECT id, kode, nama FROM mata_kuliah ORDER BY nama ASC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mata_kuliah_id = intval($_POST['mata_kuliah_id']);
    $judul = trim($_POST['judul']);
    $instruksi = trim($_POST['instruksi']);
    $tenggat = $_POST['tenggat'];
    $status = $_POST['status'];

    $up = $conn->prepare("UPDATE tugas SET mata_kuliah_id=?, judul=?, instruksi=?, tenggat=?, status=? WHERE id=?");
    $up->bind_param("issssi", $mata_kuliah_id, $judul, $instruksi, $tenggat, $status, $id);
    if ($up->execute()) { header("Location: daftar_tugas.php"); exit; }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Edit Tugas</title><link rel="stylesheet" href="style.css"></head>
<body>
    <div style="padding: 20px; max-width: 500px;">
        <h2>Edit Tugas</h2>
        <form action="" method="POST">
            <label>Mata Kuliah:</label><br>
            <select name="mata_kuliah_id" required>
                <?php while($mk = $list_mk->fetch_assoc()): ?>
                    <option value="<?= $mk['id']; ?>" <?= ($data['mata_kuliah_id'] == $mk['id']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($mk['kode'] . ' - ' . $mk['nama']); ?>
                    </option>
                <?php endwhile; ?>
            </select><br><br>
            <label>Judul:</label><br><input type="text" name="judul" value="<?= htmlspecialchars($data['judul']); ?>" required><br><br>
            <label>Instruksi:</label><br><textarea name="instruksi" rows="4" required><?= htmlspecialchars($data['instruksi']); ?></textarea><br><br>
            <label>Tenggat:</label><br><input type="date" name="tenggat" value="<?= $data['tenggat']; ?>" required><br><br>
            <label>Status:</label><br>
            <select name="status" required>
                <option value="Draf" <?= ($data['status'] == 'Draf') ? 'selected' : ''; ?>>Draf</option>
                <option value="Aktif" <?= ($data['status'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                <option value="Selesai" <?= ($data['status'] == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
            </select><br><br>
            <button type="submit">Simpan Perubahan</button>
            <a href="daftar_tugas.php">Batal</a>
        </form>
    </div>
</body>
</html>