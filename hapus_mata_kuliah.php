<?php
require 'koneksi.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("SELECT nama FROM mata_kuliah WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$mk = $stmt->get_result()->fetch_assoc();

if (!$mk) {
    header("Location: daftar_mata_kuliah.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $del = $conn->prepare("DELETE FROM mata_kuliah WHERE id = ?");
        $del->bind_param("i", $id);
        if ($del->execute()) {
            header("Location: daftar_mata_kuliah.php");
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        $error = "Data belum tersimpan. Mata kuliah masih memiliki materi atau tugas. Hapus data terkait terlebih dahulu[cite: 17].";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Hapus Mata Kuliah</title><link rel="stylesheet" href="style.css"></head>
<body>
    <div style="padding: 20px;">
        <h2>Hapus Mata Kuliah</h2>
        <?php if(!empty($error)) echo "<p style='color:red; font-weight:bold;'>$error</p>"; ?>
        <p>Apakah Anda yakin ingin menghapus <b><?= htmlspecialchars($mk['nama']); ?></b>?</p>
        <form action="" method="POST">
            <button type="submit" style="background:darkred; color:white;">Ya, hapus data</button>
            <a href="daftar_mata_kuliah.php">Batal</a>
        </form>
    </div>
</body>
</html>