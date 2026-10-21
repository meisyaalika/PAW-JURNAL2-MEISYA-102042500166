<?php
require 'koneksi.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("DELETE FROM materi WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: daftar_materi.php");
exit;
?>