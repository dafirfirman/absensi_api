<?php
// Termasuk file koneksi.php untuk koneksi ke database
include '../config/koneksi.php';

// Buat query untuk mengambil data laporan dari tabel laporan
$query = "SELECT nip_ngta, nama, id_sekolah, status, tanggal FROM laporan";

// Eksekusi query
$result = $conn->query($query);

// Cek apakah ada data yang diambil
if ($result->num_rows > 0) {
    $laporan = array();

    // Loop setiap baris data dan masukkan ke array
    while ($row = $result->fetch_assoc()) {
        $laporan[] = array(
            "nip_ngta" => $row['nip_ngta'],
            "nama" => $row['nama'],
            "id_sekolah" => $row['id_sekolah'],
            "status" => $row['status'],
            "tanggal" => $row['tanggal']
        );
    }

    // Mengembalikan data dalam format JSON
    header('Content-Type: application/json');
    echo json_encode($laporan);
} else {
    // Jika tidak ada data, kembalikan pesan kosong
    echo json_encode(array("message" => "Tidak ada data laporan."));
}

// Tutup koneksi database
$conn->close();
?>
