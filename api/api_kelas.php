<?php
// Termasuk file koneksi.php untuk menghubungkan ke database
include '../config/koneksi.php';

// Mengatur header agar API mengembalikan data dalam format JSON
header('Content-Type: application/json');

// Membuat query untuk mengambil semua data kelas
$query = "SELECT id, nama_kelas FROM kelas";
$result = $conn->query($query);

// Membuat array untuk menyimpan hasil
$kelasList = array();

if ($result->num_rows > 0) {
    // Mengambil setiap baris data kelas
    while($row = $result->fetch_assoc()) {
        $kelasList[] = $row; // Menambahkan data kelas ke dalam array
    }
    // Mengembalikan data kelas dalam format JSON
    echo json_encode($kelasList);
} else {
    // Jika tidak ada data kelas, mengembalikan pesan error
    echo json_encode(array("status" => "error", "message" => "Tidak ada data kelas."));
}

// Menutup koneksi database
$conn->close();
?>
