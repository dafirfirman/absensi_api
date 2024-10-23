<?php
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);

    if (isset($input['id'])) {
        $jadwal_id = $input['id'];

        $query = "DELETE FROM jadwal WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $jadwal_id);

        if ($stmt->execute()) {
            echo json_encode(array("status" => "success", "message" => "Jadwal berhasil dihapus."));
        } else {
            echo json_encode(array("status" => "error", "message" => "Gagal menghapus jadwal: " . $conn->error));
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
