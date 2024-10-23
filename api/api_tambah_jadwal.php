<?php
// Menghubungkan ke database
include '../config/koneksi.php';

// Memeriksa apakah metode yang digunakan adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data JSON dari body request
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);

    // Mengecek apakah semua data yang diperlukan tersedia
    if (isset($input['user_id'], $input['mata_pelajaran'], $input['hari'], $input['waktu_mulai'], $input['waktu_selesai'], $input['kelas_id'])) {
        $user_id = $input['user_id'];
        $mata_pelajaran = $conn->real_escape_string($input['mata_pelajaran']);
        $hari = $conn->real_escape_string($input['hari']);
        $waktu_mulai = $conn->real_escape_string($input['waktu_mulai']);
        $waktu_selesai = $conn->real_escape_string($input['waktu_selesai']);
        $kelas_id = $input['kelas_id'];

        // Verifikasi apakah kelas_id ada di tabel kelas
        $cekKelas = "SELECT id FROM kelas WHERE id = '$kelas_id'";
        $resultKelas = $conn->query($cekKelas);

        if ($resultKelas->num_rows > 0) {
            // Jika kelas ditemukan, tambahkan jadwal ke tabel jadwal
            $query = "INSERT INTO jadwal (user_id, mata_pelajaran, hari, waktu_mulai, waktu_selesai, kelas_id, created_at) 
                      VALUES ('$user_id', '$mata_pelajaran', '$hari', '$waktu_mulai', '$waktu_selesai', '$kelas_id', NOW())";

            if ($conn->query($query) === TRUE) {
                // Mengirimkan respons sukses
                echo json_encode(array("status" => "success", "message" => "Jadwal berhasil ditambahkan."));
            } else {
                // Mengirimkan respons jika terjadi kesalahan saat memasukkan data
                echo json_encode(array("status" => "error", "message" => "Gagal menambahkan jadwal: " . $conn->error));
            }
        } else {
            // Mengirimkan respons jika kelas_id tidak ditemukan
            echo json_encode(array("status" => "error", "message" => "Kelas tidak ditemukan."));
        }
    } else {
        // Mengirimkan respons jika data tidak lengkap
        echo json_encode(array("status" => "error", "message" => "Data tidak lengkap."));
    }

    // Menutup koneksi database
    $conn->close();
} else {
    // Mengirimkan respons jika metode yang digunakan bukan POST
    echo json_encode(array("status" => "error", "message" => "Metode request tidak valid."));
}
?>
