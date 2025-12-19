<?php
include "../includes/header.php";
require "../../config/koneksi.php";

if (isset($_POST['tambah'])) {
    $id_smk = (int)$_POST['id_smk'];
    $kode_kejuruan = mysqli_real_escape_string($koneksi, strtoupper(trim($_POST['kode_kejuruan'])));
    $nama_kejuruan = mysqli_real_escape_string($koneksi, trim($_POST['nama_kejuruan']));
    $deskripsi = mysqli_real_escape_string($koneksi, trim($_POST['deskripsi']));
   
    // Validasi
    if (empty($id_smk) || empty($kode_kejuruan) || empty($nama_kejuruan)) {
        header("Location: tambah-kejuruan.php?error=empty");
        exit;
    }

    // Cek duplikat
    $check = "SELECT * FROM tb_kejuruan WHERE id_smk = $id_smk AND kode_kejuruan = '$kode_kejuruan'";
    $check_result = mysqli_query($koneksi, $check);
    
    if (mysqli_num_rows($check_result) > 0) {
        header("Location: tambah-kejuruan.php?error=duplicate");
        exit;
    }

    // Insert data
    $sql = "INSERT INTO tb_kejuruan (id_smk, kode_kejuruan, nama_kejuruan, deskripsi) 
            VALUES ($id_smk, '$kode_kejuruan', '$nama_kejuruan', '$deskripsi')";

    if (mysqli_query($koneksi, $sql)) {
        header("Location: data-kejuruan.php?success=tambah");
    } else {
        header("Location: tambah-kejuruan.php?error=query");
    }
    exit;
}

// ===========================
// EDIT KEJURUAN
// ===========================
if (isset($_POST['edit'])) {
    $id_kejuruan = (int)$_POST['id_kejuruan'];
    $id_smk = (int)$_POST['id_smk'];
    $kode_kejuruan = mysqli_real_escape_string($koneksi, strtoupper(trim($_POST['kode_kejuruan'])));
    $nama_kejuruan = mysqli_real_escape_string($koneksi, trim($_POST['nama_kejuruan']));
    $deskripsi = mysqli_real_escape_string($koneksi, trim($_POST['deskripsi']));
    $jumlah_siswa = (int)$_POST['jumlah_siswa'];
    $jumlah_rombel = (int)$_POST['jumlah_rombel'];

    $sql = "UPDATE tb_kejuruan SET 
            id_smk = $id_smk,
            kode_kejuruan = '$kode_kejuruan',
            nama_kejuruan = '$nama_kejuruan',
            deskripsi = '$deskripsi',
            jumlah_siswa = $jumlah_siswa,
            jumlah_rombel = $jumlah_rombel
            WHERE id_kejuruan = $id_kejuruan";

    if (mysqli_query($koneksi, $sql)) {
        header("Location: data-kejuruan.php?success=edit");
    } else {
        header("Location: edit-kejuruan.php?id=$id_kejuruan&error=query");
    }
    exit;
}

// ===========================
// HAPUS KEJURUAN
// ===========================
if (isset($_GET['hapus'])) {
    $id_kejuruan = (int)$_GET['hapus'];

    $sql = "DELETE FROM tb_kejuruan WHERE id_kejuruan = $id_kejuruan";

    if (mysqli_query($koneksi, $sql)) {
        header("Location: data-kejuruan.php?success=hapus");
    } else {
        header("Location: data-kejuruan.php?error=hapus");
    }
    exit;
}

// Redirect jika akses langsung
header("Location: data-kejuruan.php");
exit;
?>