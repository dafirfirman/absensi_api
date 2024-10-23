<?php
// Termasuk file koneksi.php untuk koneksi database
include '../config/koneksi.php';

// Mengecek apakah data POST diterima
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data JSON dari body request
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true); // Mengubah JSON menjadi array asosiatif

    // Ambil data dari permintaan POST
    $nama_guru = $input['nama_guru'] ?? '';
    $nip = $input['nip'] ?? '';
    $email = $input['email'] ?? '';
    $telepon = $input['telepon'] ?? '';
    $alamat = $input['alamat'] ?? '';
    $jabatan = $input['jabatan'] ?? '';

    // Mengecek apakah inputan kosong
    if (empty($nama_guru) || empty($nip) || empty($email) || empty($telepon) || empty($alamat) || empty($jabatan)) {
        $response = array("status" => "error", "message" => "Semua data harus diisi.");
    } else {
        // Menggunakan prepared statement untuk mencegah SQL Injection
        $stmt = $conn->prepare("INSERT INTO data_guru (nama_guru, nip, email, telepon, alamat, jabatan) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nama_guru, $nip, $email, $telepon, $alamat, $jabatan);

        if ($stmt->execute()) {
            $response = array("status" => "success", "message" => "Data guru berhasil ditambahkan.");
        } else {
            $response = array("status" => "error", "message" => "Gagal menambahkan data guru.");
        }

        // Menutup statement
        $stmt->close();
    }

    // Menutup koneksi
    $conn->close();

    // Mengirimkan response sebagai JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
