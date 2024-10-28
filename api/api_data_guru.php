<?php
include '../config/koneksi.php';

// Mengatur header agar API mengembalikan data dalam format JSON
header('Content-Type: application/json');

// Membuat query untuk mengambil semua data guru
$query = "SELECT * FROM data_guru";
$result = $conn->query($query);

// Membuat array untuk menyimpan hasil
$guruList = array();

if ($result->num_rows > 0) {
    // Mengambil setiap baris data guru
    while($row = $result->fetch_assoc()) {
        $guruList[] = $row; // Menambahkan data guru ke dalam array
    }
    // Mengembalikan data guru dalam format JSON
    echo json_encode($guruList);
} else {
    // Jika tidak ada data guru, mengembalikan pesan error
    echo json_encode(array("status" => "error", "message" => "Tidak ada data guru."));
}

// Menutup koneksi database
$conn->close();
?>
