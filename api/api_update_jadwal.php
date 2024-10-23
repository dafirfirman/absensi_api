<?php
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);

    if (isset($input['id'], $input['user_id'], $input['mata_pelajaran'], $input['hari'], $input['waktu_mulai'], $input['waktu_selesai'], $input['kelas_id'])) {
        $jadwal_id = $input['id'];
        $user_id = $input['user_id'];
        $mata_pelajaran = $conn->real_escape_string($input['mata_pelajaran']);
        $hari = $conn->real_escape_string($input['hari']);
        $waktu_mulai = $conn->real_escape_string($input['waktu_mulai']);
        $waktu_selesai = $conn->real_escape_string($input['waktu_selesai']);
        $kelas_id = $input['kelas_id'];

        $query = "UPDATE jadwal SET user_id = ?, mata_pelajaran = ?, hari = ?, waktu_mulai = ?, waktu_selesai = ?, kelas_id = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("issssii", $user_id, $mata_pelajaran, $hari, $waktu_mulai, $waktu_selesai, $kelas_id, $jadwal_id);

        if ($stmt->execute()) {
            echo json_encode(array("status" => "success", "message" => "Jadwal berhasil diperbarui."));
        } else {
            echo json_encode(array("status" => "error", "message" => "Gagal memperbarui jadwal: " . $conn->error));
        }

        $stmt->close();
    } else {
        echo json_encode(array("status" => "error", "message" => "Data tidak lengkap."));
    }
    $conn->close();
} else {
    echo json_encode(array("status" => "error", "message" => "Metode request tidak valid."));
}
?>
