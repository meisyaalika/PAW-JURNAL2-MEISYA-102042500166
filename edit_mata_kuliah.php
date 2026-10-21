<?php
require 'koneksi.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$error = "";

$stmt = $conn->prepare("SELECT * FROM mata_kuliah WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    header("Location: daftar_mata_kuliah.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode = trim($_POST['kode']);
    $nama = trim($_POST['nama']);
    $dosen = trim($_POST['dosen']);
    $semester = intval($_POST['semester']);

    if (!preg_match('/^[A-Z0-9\-]{3,20}$/', $kode)) {
        $error = "Kode mata kuliah harus 3-20 karakter.";
    } elseif (strlen($nama) < 3 || strlen($nama) > 100) {
        $error = "Nama mata kuliah harus 3-100 karakter.";
    } else {
        $stmt_up = $conn->prepare("UPDATE mata_kuliah SET kode=?, nama=?, dosen=?, semester=? WHERE id=?");
        $stmt_up->bind_param("sssii", $kode, $nama, $dosen, $semester, $id);
        if ($stmt_up->execute()) {
            header("Location: daftar_mata_kuliah.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Edit Mata Kuliah</title><link rel="stylesheet" href="style.css"></head>
<body>
    <div style="padding: 20px; max-width: 500px;">
        <h2>Edit Mata Kuliah</h2>
        <a href="daftar_mata_kuliah.php">&larr; Kembali</a><br><br>
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form action="" method="POST">
            <label>Kode:</label><br><input type="text" name="kode" value="<?= htmlspecialchars($data['kode']); ?>" required><br><br>
            <label>Nama:</label><br><input type="text" name="nama" value="<?= htmlspecialchars($data['nama']); ?>" required><br><br>
            <label>Dosen:</label><br><input type="text" name="dosen" value="<?= htmlspecialchars($data['dosen']); ?>" required><br><br>
            <label>Semester:</label><br><input type="number" name="semester" value="<?= $data['semester']; ?>" min="1" max="14" required><br><br>
            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>