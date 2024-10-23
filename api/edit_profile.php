<?php
// Sertakan file koneksi
include '../config/koneksi.php';

// Cek apakah metode permintaan adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data yang dikirim dari permintaan
    $nip_ngta = $_POST['nip_ngta'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $agama = $_POST['agama'];
    $alamat = $_POST['alamat'];
    $jabatan = $_POST['jabatan'];
    $pendidikan_terakhir = $_POST['pendidikan_terakhir'];
    $pangkat = $_POST['pangkat'];

    // Validasi NIP/NGTA
    if (empty($nip_ngta)) {
        echo json_encode(array('status' => 'error', 'message' => 'NIP/NGTA tidak boleh kosong'));
        exit();
    }

    // Buat query untuk mengupdate data
    $query = "UPDATE data_guru SET 
                nama_lengkap = '$nama_lengkap',
                tanggal_lahir = '$tanggal_lahir',
                agama = '$agama',
                alamat = '$alamat',
                jabatan = '$jabatan',
                pendidikan_terakhir = '$pendidikan_terakhir',
                pangkat = '$pangkat'
              WHERE nip_ngta = '$nip_ngta'";

    // Jalankan query
    if (mysqli_query($koneksi, $query)) {
        echo json_encode(array('status' => 'success', 'message' => 'Profil berhasil diperbarui'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Gagal memperbarui profil'));
    }
} else {
    // Jika bukan metode POST, tampilkan pesan error
    echo json_encode(array('status' => 'error', 'message' => 'Permintaan tidak valid'));
}
?>
