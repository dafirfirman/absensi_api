<?php
include '../config/koneksi.php';

if (isset($_FILES['image']) && isset($_POST['nip_ngta'])) {
    $nip_ngta = $_POST['nip_ngta'];
    $file_name = $_FILES['image']['name'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $upload_dir = "../uploads/";

    // Buat direktori jika belum ada
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Pindahkan file ke folder uploads/
    $file_path = $upload_dir . $file_name;
    if (move_uploaded_file($file_tmp, $file_path)) {
        // Update nama file di tabel data_guru
        $sql = "UPDATE data_guru SET profile_image = '$file_name' WHERE nip_ngta = '$nip_ngta'";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['status' => 'success', 'message' => 'Foto profil berhasil diunggah']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui foto profil di database']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengunggah gambar']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Permintaan tidak valid']);
}

$conn->close();
?>
