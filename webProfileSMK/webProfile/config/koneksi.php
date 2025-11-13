<?php 
$HOST = "localhost";
$USER = "root"; 
$PASS = "";
$DB   = "db_smk";

$koneksi = mysqli_connect($HOST, $USER, $PASS, $DB);
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}           
    
?>