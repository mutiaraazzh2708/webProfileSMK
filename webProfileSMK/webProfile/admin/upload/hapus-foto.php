<?php
require "../../config/koneksi.php";

header('Content-Type: application/json');

try {
    // Get JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!isset($data['id_galeri']) || empty($data['id_galeri'])) {
        throw new Exception('ID foto tidak valid');
    }

    $id_galeri = (int)$data['id_galeri'];

    // Ambil data foto dari database
    $sql = "SELECT foto FROM tb_galeri WHERE id_galeri = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_galeri);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        throw new Exception('Foto tidak ditemukan di database');
    }

    // Path file fisik (tambahkan ../../ karena relatif dari admin/galeri/)
    $file_path = "../../" . $row['foto'];

    // Hapus file fisik jika ada
    $file_deleted = false;
    if (file_exists($file_path)) {
        if (unlink($file_path)) {
            $file_deleted = true;
        } else {
            throw new Exception('Gagal menghapus file fisik. Periksa permission.');
        }
    } else {
        // File tidak ada di server, tapi tetap hapus dari database
        $file_deleted = true; // Anggap berhasil
    }

    // Hapus dari database
    $delete_sql = "DELETE FROM tb_galeri WHERE id_galeri = ?";
    $delete_stmt = mysqli_prepare($koneksi, $delete_sql);
    mysqli_stmt_bind_param($delete_stmt, "i", $id_galeri);
    
    if (mysqli_stmt_execute($delete_stmt)) {
        echo json_encode([
            'success' => true,
            'message' => 'Foto berhasil dihapus',
            'file_deleted' => $file_deleted
        ]);
    } else {
        throw new Exception('Gagal menghapus foto dari database: ' . mysqli_error($koneksi));
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

mysqli_close($koneksi);
?>