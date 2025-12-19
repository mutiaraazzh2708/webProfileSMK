<?php 
include 'config/koneksi.php';

// Ambil ID dari URL
$id_smk = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_smk <= 0) {
    header("Location: index.php");
    exit;
}

// Fungsi untuk mengambil data profil SMK
function getProfilSMK($koneksi, $id_smk) {
    $sql = "SELECT * FROM tb_smk WHERE id_smk = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_smk);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

// Fungsi untuk mengambil data kejuruan
function getKejuruanSMK($koneksi, $id_smk) {
    $sql = "SELECT * FROM tb_kejuruan WHERE id_smk = ? ORDER BY nama_kejuruan ASC";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_smk);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $kejuruan = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $kejuruan[] = $row;
        }
    }
    return $kejuruan;
}

// Fungsi untuk mengambil foto galeri
function getGaleriSMK($koneksi, $id_smk) {
    $sql = "SELECT * FROM tb_galeri WHERE id_smk = ? ORDER BY urutan ASC LIMIT 5";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_smk);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $galeri = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $galeri[] = $row;
        }
    }
    
    // Jika tidak ada galeri, gunakan placeholder
    if (empty($galeri)) {
        for ($i = 1; $i <= 5; $i++) {
            $galeri[] = [
                'foto' => "https://picsum.photos/800/600?random=$i",
                'keterangan' => "Foto Sekolah $i"
            ];
        }
    }
    
    return $galeri;
}

// Ambil data
$profil = getProfilSMK($koneksi, $id_smk);

if (!$profil) {
    mysqli_close($koneksi);
    header("Location: index.php");
    exit;
}

$kejuruan = getKejuruanSMK($koneksi, $id_smk);
$galeri = getGaleriSMK($koneksi, $id_smk);

// Tutup koneksi
mysqli_close($koneksi);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($profil['nama_sekolah']); ?> - SIG ESEMKA Padang</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        /* Header Profil */
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0 60px 0;
            margin-bottom: -40px;
        }

        .profile-header h1 {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .profile-header .subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: white;
            text-decoration: none;
            margin-bottom: 20px;
            font-weight: 500;
            transition: gap 0.3s;
        }

        .back-button:hover {
            color: white;
            gap: 12px;
        }

        /* Carousel Section */
        .carousel-container {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .carousel-item img {
            height: 500px;
            object-fit: cover;
            width: 100%;
        }

        .carousel-caption {
            background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
            bottom: 0;
            left: 0;
            right: 0;
            padding: 30px;
        }

        /* Stats Card */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stat-icon.blue {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .stat-icon.green {
            background: linear-gradient(135deg, #11998e, #38ef7d);
            color: white;
        }

        .stat-icon.orange {
            background: linear-gradient(135deg, #f093fb, #f5576c);
            color: white;
        }

        .stat-icon.purple {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: white;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            line-height: 1;
        }

        /* Info Section */
        .info-section {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .info-section h3 {
            color: #333;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-section h3 i {
            color: #667eea;
        }

        .info-item {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .info-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .info-content h6 {
            margin: 0 0 5px 0;
            color: #6c757d;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .info-content p {
            margin: 0;
            color: #333;
            font-size: 1rem;
        }

        /* Kejuruan Cards */
        .kejuruan-section {
            margin-bottom: 30px;
        }

        .section-title {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-title h2 {
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .section-title p {
            color: #6c757d;
        }

        .kejuruan-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }

        .kejuruan-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
            position: relative;
        }

        .kejuruan-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
        }

        .kejuruan-icon {
            height: 160px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .kejuruan-icon::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255,255,255,0.1);
            transform: rotate(45deg);
        }

        .kejuruan-icon i {
            position: relative;
            z-index: 1;
        }

        .kejuruan-body {
            padding: 25px;
        }

        .kejuruan-body h5 {
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
            font-size: 1.2rem;
        }

        .kejuruan-body p {
            color: #6c757d;
            margin-bottom: 15px;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .kejuruan-meta {
            display: flex;
            justify-content: space-between;
            padding-top: 15px;
            border-top: 1px solid #f0f0f0;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #667eea;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Map Section */
        .map-section {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .map-section-header {
            padding: 20px 30px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .map-section-header h3 {
            margin: 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #mapProfil {
            width: 100%;
            height: 400px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .profile-header h1 {
                font-size: 1.8rem;
            }

            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }

            .carousel-item img {
                height: 300px;
            }

            .kejuruan-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    
    <?php include 'template/navbar.php'; ?>

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="container">
            <a href="index.php" class="back-button">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
            <h1><?php echo htmlspecialchars($profil['nama_sekolah']); ?></h1>
            <p class="subtitle">
                <i class="fas fa-map-marker-alt me-2"></i>
                <?php echo htmlspecialchars($profil['alamat']); ?>
            </p>
        </div>
    </div>

    <div class="container" style="margin-top: 40px;">
        
        <!-- Carousel Galeri -->
        <div class="carousel-container">
            <div id="galeriCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <?php foreach ($galeri as $index => $foto): ?>
                        <button type="button" data-bs-target="#galeriCarousel" data-bs-slide-to="<?php echo $index; ?>" 
                                class="<?php echo $index === 0 ? 'active' : ''; ?>"></button>
                    <?php endforeach; ?>
                </div>
                <div class="carousel-inner">
                    <?php foreach ($galeri as $index => $foto): ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                            <img src="<?php echo htmlspecialchars($foto['foto'] ?? 'https://picsum.photos/800/600'); ?>" 
                                 class="d-block w-100" 
                                 alt="<?php echo htmlspecialchars($foto['keterangan'] ?? 'Foto Sekolah'); ?>">
                            <div class="carousel-caption">
                                <h5><?php echo htmlspecialchars($foto['keterangan'] ?? 'Foto Sekolah'); ?></h5>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#galeriCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#galeriCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-label">Jumlah Siswa</div>
                <div class="stat-value"><?php echo number_format($profil['jumlah_siswa'] ?? 0); ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-label">Jumlah Guru</div>
                <div class="stat-value"><?php echo number_format($profil['jumlah_guru'] ?? 0); ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-book-reader"></i>
                </div>
                <div class="stat-label">Program Keahlian</div>
                <div class="stat-value"><?php echo count($kejuruan); ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-label">Status Akreditasi</div>
                <div class="stat-value"><?php echo htmlspecialchars($profil['akreditasi'] ?? '-'); ?></div>
            </div>
        </div>

        <!-- Informasi Sekolah -->
        <div class="info-section">
            <h3>
                <i class="fas fa-info-circle"></i>
                Informasi Sekolah
            </h3>
            
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-school"></i>
                </div>
                <div class="info-content">
                    <h6>Nama Sekolah</h6>
                    <p><?php echo htmlspecialchars($profil['nama_sekolah']); ?></p>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-id-card"></i>
                </div>
                <div class="info-content">
                    <h6>NPSN</h6>
                    <p><?php echo htmlspecialchars($profil['npsn'] ?? '-'); ?></p>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="info-content">
                    <h6>Alamat Lengkap</h6>
                    <p><?php echo htmlspecialchars($profil['alamat']); ?></p>
                </div>
            </div>

            <?php if (!empty($profil['telepon'])): ?>
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="info-content">
                    <h6>Telepon</h6>
                    <p><?php echo htmlspecialchars($profil['telepon']); ?></p>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($profil['email'])): ?>
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="info-content">
                    <h6>Email</h6>
                    <p><?php echo htmlspecialchars($profil['email']); ?></p>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($profil['website'])): ?>
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-globe"></i>
                </div>
                <div class="info-content">
                    <h6>Website</h6>
                    <p><a href="<?php echo htmlspecialchars($profil['website']); ?>" target="_blank">
                        <?php echo htmlspecialchars($profil['website']); ?>
                    </a></p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Program Keahlian -->
        <div class="kejuruan-section">
            <div class="section-title">
                <h2>Program Keahlian</h2>
                <p>Berbagai pilihan kompetensi keahlian yang tersedia</p>
            </div>

            <?php if (empty($kejuruan)): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>
                    Data program keahlian belum tersedia
                </div>
            <?php else: ?>
                <div class="kejuruan-grid">
                    <?php 
                    // Icon mapping untuk kejuruan
                    $iconMap = [
                        'RPL' => 'fa-laptop-code',
                        'TKJ' => 'fa-network-wired',
                        'MM' => 'fa-film',
                        'DKV' => 'fa-palette',
                        'AKL' => 'fa-calculator',
                        'OTKP' => 'fa-briefcase',
                        'BDP' => 'fa-shopping-cart',
                        'TKRO' => 'fa-car',
                        'TBSM' => 'fa-motorcycle',
                        'TPM' => 'fa-cogs',
                        'default' => 'fa-graduation-cap'
                    ];

                    $gradients = [
                        'linear-gradient(135deg, #667eea, #764ba2)',
                        'linear-gradient(135deg, #f093fb, #f5576c)',
                        'linear-gradient(135deg, #4facfe, #00f2fe)',
                        'linear-gradient(135deg, #43e97b, #38f9d7)',
                        'linear-gradient(135deg, #fa709a, #fee140)',
                        'linear-gradient(135deg, #30cfd0, #330867)',
                    ];

                    foreach ($kejuruan as $index => $k): 
                        $kode = strtoupper(substr($k['kode_kejuruan'] ?? '', 0, 5));
                        $icon = $iconMap[$kode] ?? $iconMap['default'];
                        $gradient = $gradients[$index % count($gradients)];
                    ?>
                        <div class="kejuruan-card">
                            <div class="kejuruan-icon" style="background: <?php echo $gradient; ?>">
                                <i class="fas <?php echo $icon; ?>"></i>
                            </div>
                            <div class="kejuruan-body">
                                <h5><?php echo htmlspecialchars($k['nama_kejuruan']); ?></h5>
                                <p><?php echo htmlspecialchars($k['deskripsi'] ?? 'Program keahlian yang mempersiapkan siswa dengan kompetensi profesional.'); ?></p>
                                <div class="kejuruan-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-users"></i>
                                        <span><?php echo number_format($k['jumlah_siswa'] ?? 0); ?> Siswa</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-door-open"></i>
                                        <span><?php echo $k['jumlah_rombel'] ?? '-'; ?> Rombel</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Map Section -->
        <?php if (!empty($profil['latitude']) && !empty($profil['longitude'])): ?>
        <div class="map-section">
            <div class="map-section-header">
                <h3>
                    <i class="fas fa-map-marked-alt"></i>
                    Lokasi Sekolah
                </h3>
            </div>
            <div id="mapProfil"></div>
        </div>
        <?php endif; ?>

    </div>

    <?php include 'template/footer.php'; ?>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <?php if (!empty($profil['latitude']) && !empty($profil['longitude'])): ?>
    <script>
        // Data lokasi dari PHP
        const latitude = <?php echo floatval($profil['latitude']); ?>;
        const longitude = <?php echo floatval($profil['longitude']); ?>;
        const namaSekolah = <?php echo json_encode($profil['nama_sekolah']); ?>;

        // Inisialisasi map
        const mapProfil = L.map('mapProfil').setView([latitude, longitude], 16);

        // Tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(mapProfil);

        // Custom marker icon
        const customIcon = L.divIcon({
            className: 'custom-marker',
            html: '<div style="background: linear-gradient(135deg, #667eea, #764ba2); width: 40px; height: 40px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; color: white; font-size: 20px;"><i class="fas fa-school"></i></div>',
            iconSize: [40, 40],
            iconAnchor: [20, 20],
            popupAnchor: [0, -20]
        });

        // Tambahkan marker
        const marker = L.marker([latitude, longitude], { icon: customIcon }).addTo(mapProfil);
        
        marker.bindPopup(`
            <div style="text-align: center; padding: 10px;">
                <h6 style="margin: 0 0 8px 0; font-weight: 600;">${namaSekolah}</h6>
                <a href="https://www.google.com/maps?q=${latitude},${longitude}" 
                   target="_blank" 
                   style="color: #667eea; text-decoration: none; font-size: 14px;">
                    <i class="fas fa-external-link-alt me-1"></i>
                    Buka di Google Maps
                </a>
            </div>
        `).openPopup();
    </script>
    <?php endif; ?>

</body>
</html>