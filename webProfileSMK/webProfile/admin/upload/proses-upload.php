<?php
require "../../config/koneksi.php";

header('Content-Type: application/json');

try {
    // Validasi input
    if (!isset($_POST['id_smk']) || empty($_POST['id_smk'])) {
        throw new Exception('ID SMK tidak valid');
    }

    if (!isset($_FILES['photos']) || empty($_FILES['photos']['name'][0])) {
        throw new Exception('Tidak ada foto yang dipilih');
    }

    $id_smk = (int)$_POST['id_smk'];
    $keterangan = isset($_POST['keterangan']) ? trim($_POST['keterangan']) : 'Foto Sekolah';

    // Buat folder upload jika belum ada
    $base_upload_dir = "../../uploads/";
    $galeri_dir = $base_upload_dir . "galeri/";
    $smk_dir = $galeri_dir . "smk_" . $id_smk . "/";

    // Create directories if not exist
    if (!file_exists($base_upload_dir)) {
        mkdir($base_upload_dir, 0755, true);
    }
    if (!file_exists($galeri_dir)) {
        mkdir($galeri_dir, 0755, true);
    }
    if (!file_exists($smk_dir)) {
        mkdir($smk_dir, 0755, true);
    }

    // Cek folder writable
    if (!is_writable($smk_dir)) {
        throw new Exception('Folder upload tidak dapat ditulis. Periksa permission.');
    }

    // Ambil urutan terakhir untuk SMK ini
    $urutan_query = "SELECT COALESCE(MAX(urutan), 0) + 1 as next_urutan FROM tb_galeri WHERE id_smk = $id_smk";
    $urutan_result = mysqli_query($koneksi, $urutan_query);
    $urutan_row = mysqli_fetch_assoc($urutan_result);
    $urutan = $urutan_row['next_urutan'];

    $uploaded_files = [];
    $errors = [];

    // Loop setiap file yang diupload
    foreach ($_FILES['photos']['name'] as $key => $filename) {
        $tmp_name = $_FILES['photos']['tmp_name'][$key];
        $file_size = $_FILES['photos']['size'][$key];
        $file_error = $_FILES['photos']['error'][$key];

        // Validasi error upload
        if ($file_error !== UPLOAD_ERR_OK) {
            $error_messages = [
                UPLOAD_ERR_INI_SIZE => 'File terlalu besar (melebihi upload_max_filesize)',
                UPLOAD_ERR_FORM_SIZE => 'File terlalu besar (melebihi MAX_FILE_SIZE)',
                UPLOAD_ERR_PARTIAL => 'File hanya terupload sebagian',
                UPLOAD_ERR_NO_FILE => 'Tidak ada file yang diupload',
                UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary tidak ada',
                UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk',
                UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh extension'
            ];
            $errors[] = "Error upload $filename: " . ($error_messages[$file_error] ?? "Unknown error");
            continue;
        }

        // Validasi ukuran file (max 5MB)
        if ($file_size > 5 * 1024 * 1024) {
            $errors[] = "File $filename terlalu besar (max 5MB)";
            continue;
        }

        // Validasi tipe file menggunakan getimagesize (lebih aman)
        $image_info = @getimagesize($tmp_name);
        if ($image_info === false) {
            $errors[] = "File $filename bukan gambar yang valid";
            continue;
        }

        // Validasi MIME type
        $allowed_types = [IMAGETYPE_JPEG, IMAGETYPE_PNG];
        if (!in_array($image_info[2], $allowed_types)) {
            $errors[] = "File $filename harus JPG atau PNG";
            continue;
        }

        // Generate nama file unik
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $new_filename = 'foto_' . time() . '_' . uniqid() . '.' . $ext;
        $file_path = $smk_dir . $new_filename;

        // Upload file
        if (move_uploaded_file($tmp_name, $file_path)) {
            // Path relatif untuk database (tanpa ../../)
            $db_path = "uploads/galeri/smk_" . $id_smk . "/" . $new_filename;
            
            // Simpan ke database
            $sql = "INSERT INTO tb_galeri (id_smk, foto, keterangan, urutan) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "issi", $id_smk, $db_path, $keterangan, $urutan);
            
            if (mysqli_stmt_execute($stmt)) {
                $uploaded_files[] = [
                    'filename' => $new_filename,
                    'path' => $db_path,
                    'size' => $file_size
                ];
                $urutan++;
            } else {
                $errors[] = "Gagal menyimpan $filename ke database: " . mysqli_error($koneksi);
                // Hapus file jika gagal insert
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
        } else {
            $errors[] = "Gagal mengupload $filename ke server";
        }
    }

    // Response
    if (!empty($uploaded_files)) {
        $total_uploaded = count($uploaded_files);
        $total_errors = count($errors);
        
        $message = "$total_uploaded foto berhasil diupload!";
        if ($total_errors > 0) {
            $message .= " ($total_errors foto gagal)";
        }

        echo json_encode([
            'success' => true,
            'message' => $message,
            'uploaded' => $uploaded_files,
            'errors' => $errors,
            'total_uploaded' => $total_uploaded,
            'total_errors' => $total_errors
        ]);
    } else {
        throw new Exception('Semua foto gagal diupload: ' . implode(', ', $errors));
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'errors' => []
    ]);
}

mysqli_close($koneksi);
?>
