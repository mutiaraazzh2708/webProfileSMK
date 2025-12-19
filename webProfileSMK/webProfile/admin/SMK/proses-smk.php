<?php
// bisa gunakan include,require
require "../../config/koneksi.php";
if(isset ($_POST["create"])){
   $nama = $_POST["nama"];
    $npsn = $_POST["npsn"];
    $alamat = $_POST["alamat"];
    $kelurahan = $_POST["kelurahan"];
      $kecamatan = $_POST["kecamatan"];
     $kode_pos = $_POST["kode_pos"];
      $latitude = $_POST["latitude"];
      $longitude = $_POST["longitude"];
      $telepon = $_POST["telepon"];    
    $email = $_POST["email"];
    $website = $_POST["website"];
    $kepsek = $_POST["kepsek"];
     $siswa = $_POST["siswa"];
    $guru = $_POST["guru"];
   
    
    //* cek apakah npsn sudah ada
    $cek_duplikat = "SELECT * from tb_smk where npsn = '$npsn' || email = '$email'" ;
    $hasil = $koneksi->query($cek_duplikat);
    if($hasil->num_rows > 0){
       echo "<script>
            alert('npsn sudah terdaftar!');
            window.location.href = 'tambah-smk.php';
        </script>";
        exit();
    }
    $sql = "INSERT INTO tb_smk (npsn, nama_sekolah,alamat,kelurahan,
    kode_pos,latitude,longitude,telepon,email,website,nama_kepsek,jumlah_siswa,jumlah_guru) VALUES ('$npsn', '$nama','$alamat','$kelurahan',
    '$kode_pos','$latitude','$longitude','$telepon','$email','$website','$kepsek','$siswa','$guru')";
    $query = $koneksi->query($sql); 
    if($query){
        
        header("Location: data-smk.php");
    } else {
        echo "Gagal menyimpan data: " ;
    }

}   

if (isset($_GET["hapus"])){
    $id_smk = $_GET["hapus"];
    $query = $koneksi -> query("DELETE FROM tb_smk WHERE id_smk=$id_smk");
   if($query){
        // echo "Berhasl menyimpan data mahasiswa";
        header("Location: data-smk.php");
    } else {
        echo "Gagal menghapus data data: " ;
    }
}

if (isset ($_POST["update"])){
   
        $id_smk =  $_POST['id_smk'];

   $nama = $_POST["nama"];
    $npsn = $_POST["npsn"];
    $alamat = $_POST["alamat"];
    $kelurahan = $_POST["kelurahan"];
      $kecamatan = $_POST["kecamatan"];
    $kode_pos = $_POST["kode_pos"];
      $latitude = $_POST["latitude"];
      $longitude = $_POST["longitude"];
      $telepon = $_POST["telepon"];    
    $email = $_POST["email"];
    $website = $_POST["website"];
    $kepsek = $_POST["kepsek"];
     $siswa = $_POST["siswa"];
    $guru = $_POST["guru"];

    $sql = "UPDATE tb_smk SET nama_sekolah='$nama', npsn='$npsn', alamat='$alamat', alamat='$alamat',
    kelurahan = '$kelurahan',kode_pos = '$kode_pos',latitude = '$latitude',telepon = '$telepon',email = '$email',
    website = '$website',jumlah_siswa='$siswa',jumlah_guru='$guru',nama_kepsek = '$kepsek' WHERE id_smk=$id_smk";
    $query = $koneksi->query($sql); 
    if($query){
        header("Location: data-smk.php");
    } else {
        echo "Gagal menyimpan data: " ;
    }

}   
?>