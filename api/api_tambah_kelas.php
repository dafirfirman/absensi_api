<?php
// Termasuk file koneksi.php untuk koneksi database
include '../config/koneksi.php';

// Mengecek apakah data POST diterima
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data JSON dari body request
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true); // Mengubah JSON menjadi array asosiatif

    // Ambil data dari permintaan POST
    $nama_kelas = $input['nama_kelas'] ?? '';
    $tingkat = $input['tingkat'] ?? '';
    $latitude = $input['latitude'] ?? '';
    $longitude = $input['longitude'] ?? '';

    // Mengecek apakah inputan kosong
    if (empty($nama_kelas) || empty($tingkat) || empty($latitude) || empty($longitude)) {
        $response = array("status" => "error", "message" => "Nama kelas, tingkat, latitude, dan longitude harus diisi.");
    } else {
        // Menggunakan prepared statement untuk mencegah SQL Injection
        $stmt = $conn->prepare("INSERT INTO kelas (nama_kelas, tingkat, latitude, longitude) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sidd", $nama_kelas, $tingkat, $latitude, $longitude);

        if ($stmt->execute()) {
            $response = array("status" => "success", "message" => "Kelas berhasil ditambahkan.");
        } else {
            $response = array("status" => "error", "message" => "Gagal menambahkan kelas.");
        }

        // Menutup statement
        $stmt->close();
    }

    // Menutup koneksi
    $conn->close();

    // Mengirimkan response sebagai JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
