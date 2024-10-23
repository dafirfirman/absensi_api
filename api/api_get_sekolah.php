<?php
// Termasuk file koneksi.php untuk menghubungkan ke database
include '../config/koneksi.php';

// Mengatur header agar API mengembalikan data dalam format JSON
header('Content-Type: application/json');

// Membuat query untuk mengambil semua data sekolah
$query = "SELECT * FROM lokasi";
$result = $conn->query($query);

// Membuat array untuk menyimpan hasil
$schoolList = array();

if ($result->num_rows > 0) {
    // Mengambil setiap baris data sekolah
    while($row = $result->fetch_assoc()) {
        $schoolList[] = $row;
    }
    // Mengembalikan data sekolah dalam format JSON
    echo json_encode($schoolList);
} else {
    // Jika tidak ada data sekolah, mengembalikan pesan kosong
    echo json_encode([]);
}

// Menutup koneksi database
$conn->close();
?>
