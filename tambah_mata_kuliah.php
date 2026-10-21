<?php
require 'koneksi.php';
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode = trim($_POST['kode']);
    $nama = trim($_POST['nama']);
    $dosen = trim($_POST['dosen']);
    $semester = intval($_POST['semester']);

    if (!preg_match('/^[A-Z0-9\-]{3,20}$/', $kode)) {
        $error = "Kode mata kuliah harus 3-20 karakter (huruf kapital, angka, atau tanda -).";
    } elseif (strlen($nama) < 3 || strlen($nama) > 100) {
        $error = "Nama mata kuliah harus 3-100 karakter.";
    } elseif (strlen($dosen) < 3 || strlen($dosen) > 100) {
        $error = "Nama dosen harus 3-100 karakter.";
    } elseif ($semester < 1 || $semester > 14) {
        $error = "Semester harus berada di antara 1 sampai 14.";
    } else {
        $stmt_check = $conn->prepare("SELECT id FROM mata_kuliah WHERE kode = ?");
        $stmt_check->bind_param("s", $kode);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error = "Kode mata kuliah sudah digunakan[cite: 17].";
        } else {
            $stmt = $conn->prepare("INSERT INTO mata_kuliah (kode, nama, dosen, semester) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $kode, $nama, $dosen, $semester);
            if ($stmt->execute()) {
                header("Location: daftar_mata_kuliah.php");
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Mata Kuliah - LMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div style="padding: 20px; max-width: 500px;">
        <h2>Tambah Mata Kuliah</h2>
        <a href="daftar_mata_kuliah.php">&larr; Kembali</a><br><br>

        <?php if (!empty($error)): ?>
            <div style="color: red; margin-bottom: 15px;"><?= $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <label>Kode Mata Kuliah:</label><br>
            <input type="text" name="kode" required style="width: 100%; margin-bottom: 10px;" value="<?= htmlspecialchars($_POST['kode'] ?? ''); ?>"><br>

            <label>Nama Mata Kuliah:</label><br>
            <input type="text" name="nama" required style="width: 100%; margin-bottom: 10px;" value="<?= htmlspecialchars($_POST['nama'] ?? ''); ?>"><br>

            <label>Dosen Pengampu:</label><br>
            <input type="text" name="dosen" required style="width: 100%; margin-bottom: 10px;" value="<?= htmlspecialchars($_POST['dosen'] ?? ''); ?>"><br>

            <label>Semester (1-14):</label><br>
            <input type="number" name="semester" min="1" max="14" required style="width: 100%; margin-bottom: 15px;" value="<?= htmlspecialchars($_POST['semester'] ?? ''); ?>"><br>

            <button type="submit">Simpan Data</button>
        </form>
    </div>
</body>
</html>