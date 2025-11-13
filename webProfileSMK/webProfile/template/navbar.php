<?php 
  $BASE_URL = 'http://localhost/webProfileSMK/webProfile';
  $TNTG_URL = 'http://localhost/webProfileSMK/webProfile/tentang.php';
  $DATA_URL = 'http://localhost/webProfileSMK/webProfile/data-smk.php';
  $KNTK_URL = 'http://localhost/webProfileSMK/webProfile/kontak.php';

?>

<!DOCTYPE html>
<html lang="id">
<!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-map-marked-alt me-2"></i>
                SIG SMK Padang
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= $BASE_URL?>">
                            <i class="fas fa-home me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $TNTG_URL?>">
                            <i class="fas fa-info-circle me-1"></i> Tentang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $DATA_URL?>">
                            <i class="fas fa-database me-1"></i> Data SMK
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $KNTK_URL?>">
                            <i class="fas fa-envelope me-1"></i> Kontak
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/login.php">
                            <i class="fas fa-user-shield me-1"></i> Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</html>