<?php
include '../config/koneksi.php';

if(isset($_FILES['image']) && isset($_POST['nip_ngta'])){
    $nip_ngta = $_POST['nip_ngta'];
    $file_name = $_FILES['image']['name'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $upload_dir = "../uploads/";
    
    // Cek apakah direktori upload ada
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Buat folder jika belum ada
    }

    // Pindahkan file ke folder uploads/
    $file_path = $upload_dir . $file_name;
    if (move_uploaded_file($file_tmp, $file_path)) {
        // Update nama file di database
        $sql = "UPDATE kepala_sekolah SET profile_image = '$file_name' WHERE nip_ngta = '$nip_ngta'";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['status' => 'success', 'message' => 'Image uploaded successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update profile image']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to upload image']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

$conn->close();
?>
