<?php
// Konfigurasi koneksi database
include '../config/koneksi.php';

// Mengatur header untuk respons JSON
header('Content-Type: application/json');

// Memeriksa apakah permintaan menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Mendapatkan data JSON dari request POST
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Debug: Memeriksa apakah data JSON diterima
    if ($input === null) {
        echo json_encode(array("status" => "error", "message" => "Format JSON tidak valid"));
        exit();
    }

    // Mendapatkan NIP/NGTA dari data JSON
    $nip_ngta = isset($input['nip_ngta']) ? $input['nip_ngta'] : '';

    // Mengecek apakah NIP/NGTA kosong
    if (!empty($nip_ngta)) {
        
        // Menggunakan prepared statements untuk mencegah SQL injection
        $query = $conn->prepare("SELECT TRIM(nama_lengkap) AS nama_lengkap FROM users WHERE nip_ngta = ?");
        $query->bind_param("s", $nip_ngta);
        $query->execute();
        $result = $query->get_result();

        // Jika data pengguna ditemukan
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $response = array("status" => "success", "nama_lengkap" => $row['nama_lengkap']);
        } else {
            $response = array("status" => "error", "message" => "Pengguna tidak ditemukan");
        }

        // Menutup query
        $query->close();
    } else {
        $response = array("status" => "error", "message" => "NIP/NGTA tidak valid");
    }

    // Mengirimkan response dalam format JSON
    echo json_encode($response);

} else {
    $response = array("status" => "error", "message" => "Metode tidak diizinkan");
    echo json_encode($response);
}
?>
