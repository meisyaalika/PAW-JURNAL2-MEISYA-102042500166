<?php
require 'koneksi.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("SELECT tugas.*, mata_kuliah.nama AS nama_mk, mata_kuliah.kode AS kode_mk FROM tugas JOIN mata_kuliah ON tugas.mata_kuliah_id = mata_kuliah.id WHERE tugas.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) { header("Location: daftar_tugas.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Detail Tugas</title><link rel="stylesheet" href="style.css"></head>
<body>
    <div style="padding: 20px;">
        <h2>Detail Tugas</h2>
        <p><b>Mata Kuliah:</b> <?= htmlspecialchars($data['kode_mk'] . ' - ' . $data['nama_mk']); ?></p>
        <p><b>Judul:</b> <?= htmlspecialchars($data['judul']); ?></p>
        <p><b>Instruksi:</b><br><?= nl2br(htmlspecialchars($data['instruksi'])); ?></p>
        <p><b>Tenggat:</b> <?= $data['tenggat']; ?></p>
        <p><b>Status:</b> <?= $data['status']; ?></p>
        <br>
        <a href="edit_tugas.php?id=<?= $data['id']; ?>">Edit data</a> | <a href="daftar_tugas.php">Kembali</a>
    </div>
</body>
</html>