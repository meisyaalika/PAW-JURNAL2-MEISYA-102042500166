<?php
require 'koneksi.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$error = "";

$stmt = $conn->prepare("SELECT * FROM materi WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) { header("Location: daftar_materi.php"); exit; }
$list_mk = $conn->query("SELECT id, kode, nama FROM mata_kuliah ORDER BY nama ASC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mata_kuliah_id = intval($_POST['mata_kuliah_id']);
    $judul = trim($_POST['judul']);
    $isi = trim($_POST['isi']);
    $tautan = trim($_POST['tautan']);

    if (!preg_match('/^https?:\/\/.+$/i', $tautan)) {
        $error = "Tautan materi tidak valid.";
    } else {
        $up = $conn->prepare("UPDATE materi SET mata_kuliah_id=?, judul=?, isi=?, tautan=? WHERE id=?");
        $up->bind_param("isssi", $mata_kuliah_id, $judul, $isi, $tautan, $id);
        if ($up->execute()) { header("Location: daftar_materi.php"); exit; }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Edit Materi</title><link rel="stylesheet" href="style.css"></head>
<body>
    <div style="padding: 20px; max-width: 500px;">
        <h2>Edit Materi</h2>
        <?php if(!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
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
            <label>Isi:</label><br><textarea name="isi" rows="4" required><?= htmlspecialchars($data['isi']); ?></textarea><br><br>
            <label>Tautan:</label><br><input type="text" name="tautan" value="<?= htmlspecialchars($data['tautan']); ?>" required><br><br>
            <button type="submit">Simpan Perubahan</button>
            <a href="daftar_materi.php">Batal</a>
        </form>
    </div>
</body>
</html>