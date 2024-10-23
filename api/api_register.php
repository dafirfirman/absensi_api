<?php
// Termasuk file koneksi.php untuk koneksi database
include '../config/koneksi.php';

// Mengecek apakah metode permintaan adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Mengambil dan menguraikan data JSON dari body request
    $input = json_decode(file_get_contents('php://input'), true);

    // Memeriksa apakah semua kunci ada dalam data input
    if (isset($input['nama_guru']) && isset($input['nip_ngta']) && isset($input['password'])) {

        // Ambil data dari input JSON
        $nama_guru = trim($input['nama_guru']);
        $nip_ngta = trim($input['nip_ngta']);
        $password = trim($input['password']);

        // Mengecek apakah inputan kosong
        if (empty($nama_guru) || empty($nip_ngta) || empty($password)) {
            $response = array("status" => "error", "message" => "Semua kolom harus diisi.");
        } else {
            // Pengecekan apakah NIP/NGTA sudah ada di database
            $check_sql = "SELECT * FROM data_guru WHERE nip_ngta = '$nip_ngta'";
            $result = $conn->query($check_sql);

            if ($result->num_rows > 0) {
                // Jika NIP/NGTA sudah ada, kirim pesan error
                $response = array("status" => "error", "message" => "NIP/NGTA sudah terdaftar.");
            } else {
                // Mengamankan kata sandi dengan hashing menggunakan bcrypt
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                // Query untuk menyimpan data ke dalam tabel `data_guru`
                $sql = "INSERT INTO data_guru (nama_guru, nip_ngta, password) 
                        VALUES ('$nama_guru', '$nip_ngta', '$hashed_password')";

                // Mengeksekusi query
                if ($conn->query($sql) === TRUE) {
                    $response = array("status" => "success", "message" => "Registrasi berhasil.");
                } else {
                    $response = array("status" => "error", "message" => "Error: " . $conn->error);
                }
            }
        }

    } else {
        $response = array("status" => "error", "message" => "Semua kolom harus diisi.");
    }

    // Mengirimkan response sebagai JSON
    header('Content-Type: application/json');
    echo json_encode($response);

    // Menutup koneksi
    $conn->close();

} else {
    // Jika bukan metode POST, kirim respons error
    $response = array("status" => "error", "message" => "Invalid request method.");
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
