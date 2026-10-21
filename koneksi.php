<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "paw_jurnal2_meisya_102042500166_lms";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>