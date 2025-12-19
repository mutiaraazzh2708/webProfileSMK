<?php 
  $BASE_URL = "http://localhost/webProfileSMK/webProfileSMK/webProfile/admin";



?>
<!DOCTYPE html>
<html lang="id">
      <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../assets/css/style.css">
<!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-map-marked-alt me-2"></i>
                DASHBOARD ADMIN
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= $BASE_URL?>">
                            <i class="fas fa-home me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $BASE_URL?>./smk/data-smk.php">
                            <i class="fas fa-info-database me-1"></i> Data SMK
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $BASE_URL?>./kejuruan/data-kejuruan.php">
                            <i class="fas fa-database me-1"></i> Data Kejuruan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $BASE_URL?>./guru/data-guru.php">
                            <i class="fas fa-envelope me-1"></i> Data Guru
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $BASE_URL?>./siswa/siswa.php">
                            <i class="fas fa-user-shield me-1"></i> Data Siswa
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</html>