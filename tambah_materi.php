<?php
require 'koneksi.php';
$error = "";
$list_mk = $conn->query("SELECT id, kode, nama FROM mata_kuliah ORDER BY nama ASC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mata_kuliah_id = intval($_POST['mata_kuliah_id']);
    $judul = trim($_POST['judul']);
    $isi = trim($_POST['isi']);
    $tautan = trim($_POST['tautan']);

    if ($mata_kuliah_id <= 0) {
        $error = "Pilih mata kuliah yang valid.";
    } elseif (strlen($judul) < 3 || strlen($judul) > 150) {
        $error = "Judul materi harus 3-150 karakter.";
    } elseif (!preg_match('/^https?:\/\/.+$/i', $tautan)) {
        $error = "ERROR J01: Tautan materi tidak valid. Gunakan tautan yang diawali http:// atau https://[cite: 16]";
    } else {
        $stmt = $conn->prepare("INSERT INTO materi (mata_kuliah_id, judul, isi, tautan) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $mata_kuliah_id, $judul, $isi, $tautan);
        if ($stmt->execute()) {
            header("Location: daftar_materi.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Tambah Materi</title><link rel="stylesheet" href="style.css"></head>
<body>
    <div style="padding: 20px; max-width: 500px;">
        <h2>Tambah Materi</h2>
        <?php if(!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form action="" method="POST">
            <label>Mata Kuliah:</label><br>
            <select name="mata_kuliah_id" required>
                <option value="">Pilih Mata Kuliah</option>
                <?php while($mk = $list_mk->fetch_assoc()): ?>
                    <option value="<?= $mk['id']; ?>"><?= htmlspecialchars($mk['kode'] . ' - ' . $mk['nama']); ?></option>
                <?php endwhile; ?>
            </select><br><br>
            <label>Judul Materi:</label><br><input type="text" name="judul" required><br><br>
            <label>Isi Materi:</label><br><textarea name="isi" rows="4" required></textarea><br><br>
            <label>Tautan Referensi:</label><br><input type="text" name="tautan" placeholder="https://..." required><br><br>
            <button type="submit">Simpan Data</button>
            <a href="daftar_materi.php">Batal</a>
        </form>
    </div>
</body>
</html>