<?php
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);

    // Ambil data dari input JSON
    $nip_ngta = isset($input['nip_ngta']) ? $input['nip_ngta'] : '';
    $password = isset($input['password']) ? $input['password'] : '';

    // Validasi input
    if (empty($nip_ngta) || empty($password)) {
        $response = array("status" => "error", "message" => "NIP/NGTA dan Password harus diisi.");
        echo json_encode($response);
        exit();
    }

    // Menggunakan prepared statement untuk mencegah SQL Injection
    $stmt = $conn->prepare("SELECT nip_ngta, nama_guru, password FROM data_guru WHERE nip_ngta = ?");
    $stmt->bind_param("s", $nip_ngta);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek apakah NIP/NGTA ditemukan
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verifikasi password dengan password_verify()
        if (password_verify($password, $row['password'])) {
            // Jika login berhasil, kembalikan data pengguna yang lengkap
            $response = array(
                "status" => "success",
                "message" => "Login berhasil.",
                "user" => array(
                    "nip_ngta" => $row['nip_ngta'],
                    "nama_lengkap" => $row['nama_guru']  // Gunakan nama_lengkap agar konsisten dengan Flutter
                )
            );
        } else {
            $response = array("status" => "error", "message" => "Password salah.");
        }
    } else {
        $response = array("status" => "error", "message" => "NIP/NGTA tidak ditemukan.");
    }

    // Tutup statement dan koneksi
    $stmt->close();
    $conn->close();

    // Mengirimkan response sebagai JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
