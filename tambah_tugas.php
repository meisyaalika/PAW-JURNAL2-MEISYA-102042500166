<?php
require 'koneksi.php';
$error = "";
$list_mk = $conn->query("SELECT id, kode, nama FROM mata_kuliah ORDER BY nama ASC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mata_kuliah_id = intval($_POST['mata_kuliah_id']);
    $judul = trim($_POST['judul']);
    $instruksi = trim($_POST['instruksi']);
    $tenggat = $_POST['tenggat'];
    $status = $_POST['status'];

    if ($mata_kuliah_id <= 0) {
        $error = "Pilih mata kuliah yang valid.";
    } elseif (!in_array($status, ['Draf', 'Aktif', 'Selesai'])) {
        $error = "Status harus Draf, Aktif, atau Selesai[cite: 8].";
    } else {
        $stmt = $conn->prepare("INSERT INTO tugas (mata_kuliah_id, judul, instruksi, tenggat, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $mata_kuliah_id, $judul, $instruksi, $tenggat, $status);
        if ($stmt->execute()) {
            header("Location: daftar_tugas.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Tambah Tugas</title><link rel="stylesheet" href="style.css"></head>
<body>
    <div style="padding: 20px; max-width: 500px;">
        <h2>Tambah Tugas</h2>
        <?php if(!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form action="" method="POST">
            <label>Mata Kuliah:</label><br>
            <select name="mata_kuliah_id" required>
                <option value="">Pilih Mata Kuliah</option>
                <?php while($mk = $list_mk->fetch_assoc()): ?>
                    <option value="<?= $mk['id']; ?>"><?= htmlspecialchars($mk['kode'] . ' - ' . $mk['nama']); ?></option>
                <?php endwhile; ?>
            </select><br><br>
            <label>Judul Tugas:</label><br><input type="text" name="judul" required><br><br>
            <label>Instruksi:</label><br><textarea name="instruksi" rows="4" required></textarea><br><br>
            <label>Tenggat:</label><br><input type="date" name="tenggat" required><br><br>
            <label>Status:</label><br>
            <select name="status" required>
                <option value="Draf">Draf</option>
                <option value="Aktif">Aktif</option>
                <option value="Selesai">Selesai</option>
            </select><br><br>
            <button type="submit">Simpan Data</button>
            <a href="daftar_tugas.php">Batal</a>
        </form>
    </div>
</body>
</html>