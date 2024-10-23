<?php
// Termasuk file koneksi.php untuk koneksi database
include '../config/koneksi.php';

// Mengecek apakah metode yang digunakan adalah GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $query = "SELECT * FROM kelas";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $kelas = array();
        while ($row = $result->fetch_assoc()) {
            $kelas[] = $row;
        }
        echo json_encode(array("status" => "success", "data" => $kelas));
    } else {
        echo json_encode(array("status" => "success", "data" => []));
    }

    // Menutup koneksi
    $conn->close();
} else {
    echo json_encode(array("status" => "error", "message" => "Metode request tidak valid."));
}
?>
