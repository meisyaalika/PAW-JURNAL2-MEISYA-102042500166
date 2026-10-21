<?php
require 'koneksi.php';

$mk_count = $conn->query("SELECT COUNT(*) as total FROM mata_kuliah")->fetch_assoc()['total'];
$materi_count = $conn->query("SELECT COUNT(*) as total FROM materi")->fetch_assoc()['total'];
$tugas_count = $conn->query("SELECT COUNT(*) as total FROM tugas")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda - LMS Sederhana</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="card" style="margin: 20px;">
        <h1>Learning Management System</h1>
        <p>Pengelolaan mata kuliah, materi pembelajaran, dan tugas[cite: 4].</p>
        
        <div style="display: flex; gap: 20px; margin: 20px 0;">
            <div class="card" style="flex: 1; text-align: center;">
                <h3>Mata Kuliah</h3>
                <h2><?= $mk_count; ?></h2>
            </div>
            <div class="card" style="flex: 1; text-align: center;">
                <h3>Materi</h3>
                <h2><?= $materi_count; ?></h2>
            </div>
            <div class="card" style="flex: 1; text-align: center;">
                <h3>Tugas</h3>
                <h2><?= $tugas_count; ?></h2>
            </div>
        </div>

        <hr><br>
        <h3>Menu Pengelolaan</h3>
        <ul>
            <li><a href="daftar_mata_kuliah.php">Kelola Mata Kuliah</a></li>
            <li><a href="daftar_materi.php">Kelola Materi</a></li>
            <li><a href="daftar_tugas.php">Kelola Tugas</a></li>
        </ul>
    </div>
</body>
</html>