<?php
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Menerima input JSON dari request
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);

    // Mengambil nip/ngta dari input JSON
    $nip_ngta = $input['nip_ngta'];
    $nama_guru = $input['nama_guru'];
    $email = $input['email'];
    $telepon = $input['telepon'];
    $alamat = $input['alamat'];
    $jabatan = $input['jabatan'];
    $tanggal_lahir = $input['tanggal_lahir'];
    $tempat_lahir = $input['tempat_lahir'];
    $agama = $input['agama'];
    $pendidikan = $input['pendidikan'];
    $pangkat = $input['pangkat'];

    // Debugging: Log nip/ngta yang diterima
    file_put_contents('debug_log.txt', "NIP/NGTA yang diterima: $nip_ngta\n", FILE_APPEND);

    // Cek apakah NIP/NGTA valid dan ada di database
    $select_stmt = $conn->prepare("SELECT * FROM data_guru WHERE nip_ngta=?");
    $select_stmt->bind_param("s", $nip_ngta);
    $select_stmt->execute();
    $result = $select_stmt->get_result();

    if ($result->num_rows > 0) {
        // Data lama sebelum update
        $oldData = $result->fetch_assoc();

        // Menyiapkan query update
        $stmt = $conn->prepare("UPDATE data_guru SET nama_guru=?, email=?, telepon=?, alamat=?, jabatan=?, tanggal_lahir=?, tempat_lahir=?, agama=?, pendidikan=?, pangkat=? WHERE nip_ngta=?");
        $stmt->bind_param("sssssssssss", $nama_guru, $email, $telepon, $alamat, $jabatan, $tanggal_lahir, $tempat_lahir, $agama, $pendidikan, $pangkat, $nip_ngta);

        if ($stmt->execute()) {
            // Cek apakah ada baris yang terpengaruh
            if ($stmt->affected_rows > 0) {
                echo json_encode(["status" => "success", "message" => "Data berhasil diperbarui."]);
            } else {
                echo json_encode(["status" => "success", "message" => "Data disimpan tanpa perubahan."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal memperbarui data.", "error" => $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "NIP/NGTA tidak ditemukan."]);
    }

    $select_stmt->close();
    $conn->close();
}
?>
