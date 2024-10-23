<?php
// Termasuk file koneksi.php untuk koneksi database
include '../config/koneksi.php';

// Mengecek apakah data POST diterima
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data JSON dari body request
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true); // Mengubah JSON menjadi array asosiatif

    // Ambil data dari permintaan POST
    $nama_sekolah = $input['nama_sekolah'] ?? '';
    $latitude = $input['latitude'] ?? '';
    $longitude = $input['longitude'] ?? '';
    $alamat_sekolah = $input['alamat_sekolah'] ?? '';

    // Mengecek apakah inputan kosong
    if (empty($nama_sekolah) || empty($latitude) || empty($longitude)) {
        $response = array("status" => "error", "message" => "Nama sekolah, latitude, dan longitude harus diisi.");
    } else {
        // Menggunakan prepared statement untuk mencegah SQL Injection
        $stmt = $conn->prepare("INSERT INTO lokasi (nama_sekolah, alamat_sekolah, latitude, longitude) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nama_sekolah, $alamat_sekolah, $latitude, $longitude);

        if ($stmt->execute()) {
            $response = array("status" => "success", "message" => "Data lokasi berhasil ditambahkan.");
        } else {
            $response = array("status" => "error", "message" => "Gagal menambahkan data lokasi.");
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
