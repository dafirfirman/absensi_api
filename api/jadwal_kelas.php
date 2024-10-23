<?php
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Query untuk mengambil semua data dari tabel jadwal termasuk latitude dan longitude dari tabel kelas
    $stmt = $conn->prepare("
        SELECT jadwal.mata_pelajaran, jadwal.hari, jadwal.waktu_mulai, jadwal.waktu_selesai, 
               kelas.nama_kelas, kelas.latitude, kelas.longitude, kelas.id as kelas_id 
        FROM jadwal 
        JOIN kelas ON jadwal.kelas_id = kelas.id
    ");
    
    $stmt->execute();
    $result = $stmt->get_result();

    $jadwal = array();
    while ($row = $result->fetch_assoc()) {
        // Memastikan latitude dan longitude tersedia, jika tidak, berikan nilai default
        $row['latitude'] = $row['latitude'] ?? '0.0';
        $row['longitude'] = $row['longitude'] ?? '0.0';
        $row['kelas_id'] = $row['kelas_id'] ?? '0';

        $jadwal[] = $row;
    }

    $response = array("status" => "success", "jadwal" => $jadwal);

    $stmt->close();
    $conn->close();

    // Mengirim respon dalam format JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
