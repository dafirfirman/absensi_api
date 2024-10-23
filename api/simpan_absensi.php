<?php
include '../config/koneksi.php';

// Mengatur zona waktu ke Asia/Jakarta (WIB)
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data JSON yang dikirim dari Flutter
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    // Memastikan data yang dikirim lengkap
    if (isset($data['kelas_id']) && isset($data['user_id']) && isset($data['status']) && isset($data['latitude']) && isset($data['longitude'])) {
        $kelas_id = $data['kelas_id'];
        $user_id = $data['user_id'];
        $status = $data['status'];
        $latitude = $data['latitude'];
        $longitude = $data['longitude'];
        $waktu_absen = date('Y-m-d H:i:s'); // Mendapatkan waktu saat ini dalam zona WIB

        // Menyimpan data absensi ke database
        $stmt = $conn->prepare("INSERT INTO absensi (kelas_id, user_id, status, latitude, longitude, waktu_absen) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissss", $kelas_id, $user_id, $status, $latitude, $longitude, $waktu_absen);

        if ($stmt->execute()) {
            $response = array("status" => "success", "message" => "Absensi berhasil disimpan.");
        } else {
            $response = array("status" => "error", "message" => "Gagal menyimpan absensi.");
        }

        $stmt->close();
    } else {
        $response = array("status" => "error", "message" => "Data absensi tidak lengkap.");
    }

    $conn->close();

    // Mengirim respon dalam format JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
