<?php
// Termasuk file koneksi.php untuk menghubungkan ke database
include '../config/koneksi.php';

// Mengatur header agar API mengembalikan data dalam format JSON
header('Content-Type: application/json');

// Cek apakah ada parameter POST (misalnya ID sekolah)
$id_sekolah = $_GET['id_sekolah'] ?? '';

// Cek apakah ID sekolah diberikan
if (empty($id_sekolah)) {
    echo json_encode(array("status" => "error", "message" => "ID sekolah tidak diberikan"));
    exit();
}

// Membuat query untuk mengambil lokasi sekolah berdasarkan ID
$query = "SELECT nama_sekolah, latitude, longitude, alamat_sekolah FROM lokasi WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $id_sekolah);
$stmt->execute();
$result = $stmt->get_result();

// Jika ada hasil, ambil data lokasi sekolah
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(array("status" => "error", "message" => "Data lokasi sekolah tidak ditemukan"));
}

// Menutup koneksi
$conn->close();
?>
