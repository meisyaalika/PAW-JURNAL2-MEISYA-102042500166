<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id_tugas = $_GET['id'];

    $query = "DELETE FROM tugas WHERE id_tugas = '$id_tugas'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: daftar_tugas.php");
        exit();
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }
} else {
    header("Location: daftar_tugas.php");
    exit();
}
?>