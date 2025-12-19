<?php
// Base URL Configuration
$BASE_URL = "http://localhost/webProfileSMK/webPrwebProfile/";

// Function untuk cek login
function cekLogin() {
    global $BASE_URL;
    
    // Start session jika belum
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Cek apakah sudah login
    if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        // Redirect ke login
        header('Location: ' . $BASE_URL . 'admin/login.php');
        exit();
    }
}
?>