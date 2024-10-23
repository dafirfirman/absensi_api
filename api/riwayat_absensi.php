<?php
include '../config/koneksi.php';
header('Content-Type: application/json');

// Mengecek apakah ada parameter 'user_id' yang dikirim melalui POST
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // Query untuk mengambil data riwayat absensi
    $query = "SELECT status, DATE_FORMAT(waktu_absen, '%W, %d %M %Y %H:%i:%s') AS waktu_absen 
              FROM absensi 
              WHERE user_id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $absenList = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $absenList[] = $row;
        }
        echo json_encode($absenList);
    } else {
        echo json_encode(array("status" => "error", "message" => "Tidak ada riwayat absen."));
    }

    $stmt->close();
} else {
    echo json_encode(array("status" => "error", "message" => "Parameter 'user_id' tidak ditemukan."));
}

$conn->close();
?>
