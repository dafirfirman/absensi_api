<?php
// Termasuk file koneksi.php untuk koneksi database
include '../config/koneksi.php';

// Mengecek apakah data POST diterima
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data JSON dari body request
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true); // Mengubah JSON menjadi array asosiatif

    // Ambil data dari permintaan POST (JSON)
    $nip_ngta = isset($input['nip_ngta']) ? $input['nip_ngta'] : '';
    $password = isset($input['password']) ? $input['password'] : '';

    // Mengecek apakah inputan kosong
    if (empty($nip_ngta) || empty($password)) {
        $response = array("status" => "error", "message" => "NIP dan Password harus diisi.");
    } else {
        // Query untuk memeriksa apakah NIP ada di database Admin
        $sql = "SELECT * FROM admin WHERE nip_ngta = '$nip_ngta'";
        $result = $conn->query($sql);

        // Jika NIP ditemukan
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Cek apakah password benar
            if ($row['password'] === $password) {
                // Tambahkan nama pengguna dan NIP ke response
                $response = array(
                    "status" => "success",
                    "message" => "Login berhasil.",
                    "user" => array(
                        "nip_ngta" => $row['nip_ngta'],
                        "nama_lengkap" => $row['nama_lengkap'] // Ambil nama pengguna dari database
                    )
                );
            } else {
                // Password salah
                $response = array("status" => "error", "message" => "Password salah.");
            }
        } else {
            // NIP tidak ditemukan
            $response = array("status" => "error", "message" => "NIP tidak ditemukan.");
        }
    }

    // Menutup koneksi
    $conn->close();

    // Mengirimkan response sebagai JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
