<?php
// Termasuk file koneksi database
include '../config/koneksi.php';

// Mengatur header agar API merespon dengan format JSON
header('Content-Type: application/json');

// Mengecek apakah metode request POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari body request
    $nip_ngta = isset($_POST['nip_ngta']) ? $_POST['nip_ngta'] : '';
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $id_sekolah = isset($_POST['id_sekolah']) ? $_POST['id_sekolah'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    // Validasi apakah semua data sudah diisi
    if (empty($nip_ngta) || empty($nama) || empty($id_sekolah) || empty($status)) {
        echo json_encode(array(
            "status" => "error",
            "message" => "Semua data harus diisi."
        ));
        exit();
    }

    // Query untuk menyimpan data ke tabel laporan
    $query = "INSERT INTO laporan (nip_ngta, nama, id_sekolah, status, tanggal, created_at) 
              VALUES (?, ?, ?, ?, CURDATE(), NOW())";

    // Menggunakan prepared statement untuk mencegah SQL Injection
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ssss", $nip_ngta, $nama, $id_sekolah, $status);
        
        // Menjalankan query
        if ($stmt->execute()) {
            // Jika berhasil disimpan
            echo json_encode(array(
                "status" => "success",
                "message" => "Absensi berhasil disimpan."
            ));
        } else {
            // Jika terjadi kesalahan saat menyimpan
            echo json_encode(array(
                "status" => "error",
                "message" => "Gagal menyimpan absensi."
            ));
        }

        // Menutup statement
        $stmt->close();
    } else {
        // Jika terjadi kesalahan pada prepare statement
        echo json_encode(array(
            "status" => "error",
            "message" => "Gagal menyiapkan statement."
        ));
    }

    // Menutup koneksi
    $conn->close();
} else {
    // Jika request bukan POST
    echo json_encode(array(
        "status" => "error",
        "message" => "Metode request tidak valid."
    ));
}
?>
