<?php
include '../config/koneksi.php';

// Mengecek apakah metode yang digunakan adalah GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $query = "SELECT jadwal.*, kelas.nama_kelas 
              FROM jadwal 
              JOIN kelas ON jadwal.kelas_id = kelas.id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $jadwalList = array();
        while ($row = $result->fetch_assoc()) {
            $jadwalList[] = $row;
        }
        echo json_encode(array("status" => "success", "data" => $jadwalList));
    } else {
        echo json_encode(array("status" => "success", "data" => []));
    }

    // Menutup koneksi
    $conn->close();
} else {
    echo json_encode(array("status" => "error", "message" => "Metode request tidak valid."));
}
?>
