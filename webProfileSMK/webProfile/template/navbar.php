<?php
// Include base URL jika belum ada
if (!isset($BASE_URL)) {
    $BASE_URL = "http://localhost/webProfileSMK/webProfileSMK/webProfile/";
}
?>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?= $BASE_URL ?>">
            <i class="fas fa-map-marked-alt me-2"></i>
             ESEMKA 
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $BASE_URL ?>index.php">
                        <i class="fas fa-home me-1"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $BASE_URL ?>tentang.php">
                        <i class="fas fa-info-circle me-1"></i> Tentang
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $BASE_URL ?>kontak.php">
                        <i class="fas fa-envelope me-1"></i> Kontak
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://localhost/webProfileSMK/webProfileSMK/webProfile/admin/login.php">
                        <i class="fas fa-user-shield me-1"></i> Admin
                    </a>
                </li>
            </ul>
        </div>
    </nav>