<?php
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Menerima input JSON dari request
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);

    // Mengambil nip/ngta dari input JSON
    $nip_ngta = $input['nip_ngta'];

    // Debugging: Log nip/ngta yang diterima
    file_put_contents('debug_log.txt', "NIP/NGTA yang diterima: $nip_ngta\n", FILE_APPEND);

    // Cek apakah NIP/NGTA valid dan ada di database
    $select_stmt = $conn->prepare("SELECT * FROM data_guru WHERE nip_ngta = ?");
    $select_stmt->bind_param("s", $nip_ngta);
    $select_stmt->execute();
    $result = $select_stmt->get_result();

    if ($result->num_rows > 0) {
        // Mengambil data pengguna dari hasil query
        $userData = $result->fetch_assoc();

        // Mengembalikan respons JSON dengan data pengguna
        echo json_encode([
            "status" => "success",
            "message" => "Data ditemukan.",
            "user" => [
                "nama_guru" => $userData['nama_guru'],
                "nip_ngta" => $userData['nip_ngta'],
                "email" => $userData['email'],
                "telepon" => $userData['telepon'],
                "alamat" => $userData['alamat'],
                "jabatan" => $userData['jabatan'],
                "tanggal_lahir" => $userData['tanggal_lahir'],
                "tempat_lahir" => $userData['tempat_lahir'],
                "agama" => $userData['agama'],
                "pendidikan" => $userData['pendidikan'],
                "pangkat" => $userData['pangkat']
            ]
        ]);
    } else {
        // Mengembalikan respons jika NIP/NGTA tidak ditemukan
        echo json_encode([
            "status" => "error",
            "message" => "NIP/NGTA tidak ditemukan."
        ]);
    }

    $select_stmt->close();
    $conn->close();
}
?>
