<?php
$host = "localhost";
$username = "root";
$password = "";
$db_name = "absensi";

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $db_name);

// Check koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset untuk koneksi
$conn->set_charset("utf8");
?>
