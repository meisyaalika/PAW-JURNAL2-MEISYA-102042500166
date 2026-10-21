<?php
require 'koneksi.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("SELECT materi.*, mata_kuliah.nama AS nama_mk, mata_kuliah.kode AS kode_mk FROM materi JOIN mata_kuliah ON materi.mata_kuliah_id = mata_kuliah.id WHERE materi.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) { header("Location: daftar_materi.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Detail Materi</title><link rel="stylesheet" href="style.css"></head>
<body>
    <div style="padding: 20px;">
        <h2>Detail Materi</h2>
        <p><b>Mata Kuliah:</b> <?= htmlspecialchars($data['kode_mk'] . ' - ' . $data['nama_mk']); ?></p>
        <p><b>Judul:</b> <?= htmlspecialchars($data['judul']); ?></p>
        <p><b>Isi:</b><br><?= nl2br(htmlspecialchars($data['isi'])); ?></p>
        <p><b>Tautan Referensi:</b> <a href="<?= htmlspecialchars($data['tautan']); ?>" target="_blank"><?= htmlspecialchars($data['tautan']); ?></a></p>
        <br>
        <a href="edit_materi.php?id=<?= $data['id']; ?>">Edit data</a> | <a href="daftar_materi.php">Kembali</a>
    </div>
</body>
</html>