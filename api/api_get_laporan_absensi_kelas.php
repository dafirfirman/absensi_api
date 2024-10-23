<?php
// Termasuk file koneksi.php untuk koneksi ke database
include '../config/koneksi.php';

// Buat query untuk mengambil data absensi kelas
$query = "SELECT absensi.id, absensi.kelas_id, absensi.user_id, absensi.status, absensi.latitude, absensi.longitude, absensi.waktu_absen, 
                 kelas.nama_kelas, jadwal.mata_pelajaran
          FROM absensi
          JOIN kelas ON absensi.kelas_id = kelas.id
          JOIN jadwal ON absensi.kelas_id = jadwal.kelas_id";

// Eksekusi query
$result = $conn->query($query);

// Cek apakah ada data yang diambil
if ($result->num_rows > 0) {
    $laporan_absensi_kelas = array();

    // Loop setiap baris data dan masukkan ke array
    while ($row = $result->fetch_assoc()) {
        $laporan_absensi_kelas[] = array(
            "id" => $row['id'],
            "kelas_id" => $row['kelas_id'],
            "user_id" => $row['user_id'],
            "status" => $row['status'],
            "latitude" => $row['latitude'],
            "longitude" => $row['longitude'],
            "waktu_absen" => $row['waktu_absen'],
            "nama_kelas" => $row['nama_kelas'],
            "mata_pelajaran" => $row['mata_pelajaran']
        );
    }

    // Mengembalikan data dalam format JSON
    header('Content-Type: application/json');
    echo json_encode(array("status" => "success", "laporan_absensi_kelas" => $laporan_absensi_kelas));
} else {
    // Jika tidak ada data, kembalikan pesan kosong
    header('Content-Type: application/json');
    echo json_encode(array("status" => "error", "message" => "Tidak ada data absensi kelas."));
}

// Tutup koneksi database
$conn->close();
?>
