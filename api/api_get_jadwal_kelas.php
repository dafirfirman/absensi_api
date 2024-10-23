<?php
// Menghubungkan ke database
include '../config/koneksi.php';

// Memeriksa apakah metode yang digunakan adalah GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Query untuk mengambil semua data dari tabel kelas
    $query = "SELECT * FROM kelas";
    $result = $conn->query($query);

    // Cek apakah hasil dari query memiliki data
    if ($result->num_rows > 0) {
        $kelasList = array();
        
        // Mengambil data satu per satu dan menambahkannya ke dalam array
        while ($row = $result->fetch_assoc()) {
            $kelasList[] = $row;
        }
        
        // Mengirimkan respons JSON dengan data kelas
        echo json_encode(array("status" => "success", "data" => $kelasList));
    } else {
        // Mengirimkan respons JSON jika tidak ada data kelas
        echo json_encode(array("status" => "success", "data" => []));
    }

    // Menutup koneksi
    $conn->close();
} else {
    // Mengirimkan respons jika metode yang digunakan bukan GET
    echo json_encode(array("status" => "error", "message" => "Metode request tidak valid."));
}
?>
