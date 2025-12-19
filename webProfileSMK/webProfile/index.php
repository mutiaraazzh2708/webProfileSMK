<?php 
include 'config/koneksi.php';

// Fungsi untuk mengambil data sekolah
function getDataSekolah($koneksi) {
    $dataSekolah = [];
    $sql = "SELECT id_smk, nama_sekolah, alamat, latitude, longitude, jumlah_siswa 
            FROM tb_smk 
            WHERE latitude IS NOT NULL 
            AND longitude IS NOT NULL
            AND TRIM(latitude) != ''
            AND TRIM(longitude) != ''
            AND latitude != 0
            AND longitude != 0";
    
    $result = mysqli_query($koneksi, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $lat = floatval($row['latitude']);
            $lng = floatval($row['longitude']);
            
            // Validasi koordinat untuk Kota Padang (sekitar -1.0 sampai -0.8 dan 100.3 sampai 100.5)
            if ($lat != 0 && $lng != 0 && $lat >= -1.2 && $lat <= -0.7 && $lng >= 100.2 && $lng <= 100.5) {
                $dataSekolah[] = [
                    'id' => (int)$row['id_smk'],
                    'nama' => trim($row['nama_sekolah'] ?? ''),
                    'alamat' => trim($row['alamat'] ?? ''),
                    'lat' => $lat,
                    'lng' => $lng,
                    'jumlah_siswa' => (int)($row['jumlah_siswa'] ?? 0)
                ];
            }
        }
    }
    
    return $dataSekolah;
}

// Ambil data sekolah
$dataSekolah = getDataSekolah($koneksi);
$totalSekolah = count($dataSekolah);

// Tutup koneksi DB
mysqli_close($koneksi);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIG ESEMKA Padang</title>
    
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
        .map-container { 
            width: 100%; 
            height: 520px; 
            margin-top: 20px; 
        }
        
        #map { 
            width: 100%; 
            height: 100%; 
            z-index: 1;
        }

        .custom-marker {
            background-color: #dc3545;
            border: 3px solid white;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }

        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            padding: 0;
        }

        .custom-popup .leaflet-popup-content {
            margin: 0;
            min-width: 280px;
        }

        .popup-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 12px 12px 0 0;
        }

        .popup-header h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }

        .popup-body {
            padding: 15px;
            font-size: 14px;
        }

        .popup-info {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .popup-info i {
            color: #667eea;
            margin-top: 2px;
        }

        .popup-info-text {
            line-height: 1.4;
            flex: 1;
        }

        .popup-btn {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            padding: 8px 16px;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 14px;
            font-weight: 500;
            transition: transform 0.2s;
            width: 100%;
            justify-content: center;
        }

        .popup-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            max-height: 400px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            margin-top: 8px;
        }

        .search-results.active {
            display: block;
        }

        .search-result-item {
            padding: 12px 16px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .search-result-item:hover {
            background-color: #f8f9fa;
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .result-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
        }

        .result-address {
            font-size: 13px;
            color: #666;
        }

        .search-box {
            position: relative;
        }

        .no-results {
            text-align: center;
            padding: 20px;
            color: #999;
        }
    </style>
</head>
<body>
    
    <?php include 'template/navbar.php'; ?>

    <div class="container-fluid">
        <!-- Hero Carousel Section -->
        <div class="carousel-section">
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="https://picsum.photos/1200/500?random=1" class="d-block w-100" alt="Pendidikan Berkualitas">
                        <div class="carousel-caption">
                            <h2>Sistem Informasi Geografis</h2>
                            <p>SMK Negeri Kota Padang - Menuju Pendidikan Vokasi Berkualitas</p>
                            <button class="carousel-btn">Jelajahi Sekarang</button>
                        </div>
                    </div>
                    
                    <div class="carousel-item">
                        <img src="https://picsum.photos/1200/500?random=2" class="d-block w-100" alt="Fasilitas Modern">
                        <div class="carousel-caption">
                            <h2>Fasilitas Lengkap & Modern</h2>
                            <p>Laboratorium dan Workshop Berstandar Industri</p>
                            <button class="carousel-btn">Lihat Fasilitas</button>
                        </div>
                    </div>
                    
                    <div class="carousel-item">
                        <img src="https://picsum.photos/1200/500?random=3" class="d-block w-100" alt="Prestasi Siswa">
                        <div class="carousel-caption">
                            <h2>Prestasi Membanggakan</h2>
                            <p>Siswa Berprestasi di Tingkat Nasional & Internasional</p>
                            <button class="carousel-btn">Lihat Prestasi</button>
                        </div>
                    </div>
                    
                    <div class="carousel-item">
                        <img src="https://picsum.photos/1200/500?random=4" class="d-block w-100" alt="Program Keahlian">
                        <div class="carousel-caption">
                            <h2>Beragam Program Keahlian</h2>
                            <p>Pilih Jurusan Sesuai Minat dan Bakat Anda</p>
                            <button class="carousel-btn">Lihat Program</button>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>

        <!-- Search Section -->
        <div class="search-section">
            <h3 class="search-title">
                <i class="fas fa-search me-2"></i>
                Cari SMK Negeri di Kota Padang
            </h3>
            <div class="search-box">
                <input 
                    type="text" 
                    class="search-input" 
                    id="searchInput" 
                    placeholder="Ketik nama sekolah atau alamat..."
                    autocomplete="off"
                >
                <button class="search-btn" id="searchBtn">
                    <i class="fas fa-search"></i> Cari
                </button>
                <div class="search-results" id="searchResults"></div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="map-container">
            <div class="map-header">
                <h4 class="map-title">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    Peta Persebaran SMK Negeri
                </h4>
                <div class="map-info">
                    <span class="info-badge">
                        <i class="fas fa-school me-1"></i>
                        <span id="totalSekolah"><?php echo $totalSekolah; ?></span> Sekolah
                    </span>
                </div>
            </div>
            <div id="map"></div>
        </div>
    </div>

    <?php include 'template/footer.php'; ?>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Data sekolah dari PHP
        const dataSekolah = <?php echo json_encode($dataSekolah, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>;
        
        console.log('Total SMK loaded:', dataSekolah.length);
        console.log('Data Sekolah:', dataSekolah);

        // Konfigurasi Peta
        const mapConfig = {
            center: [-0.9471, 100.3672],
            zoom: 13,
            maxZoom: 19
        };

        // Inisialisasi peta
        const map = L.map('map').setView(mapConfig.center, mapConfig.zoom);

        // Tile Layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: mapConfig.maxZoom
        }).addTo(map);

        // Custom Icon
        const customIcon = L.divIcon({
            className: 'custom-marker',
            iconSize: [30, 30],
            iconAnchor: [15, 15],
            popupAnchor: [0, -15]
        });

        // Simpan referensi marker
        const markers = {};

        // Fungsi untuk membuat popup content
        function createPopupContent(sekolah) {
            return `
                <div class="popup-header">
                    <h5>${sekolah.nama}</h5>
                </div>
                <div class="popup-body">
                    <div class="popup-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <div class="popup-info-text">${sekolah.alamat}</div>
                    </div>
                    <div class="popup-info">
                        <i class="fas fa-users"></i>
                        <div class="popup-info-text"><strong>${sekolah.jumlah_siswa}</strong> Siswa</div>
                    </div>
                    <button class="popup-btn" onclick="lihatProfil(${sekolah.id})">
                        <i class="fas fa-eye"></i>Lihat Profil Lengkap
                    </button>
                </div>
            `;
        }

        // Buat marker dari dataSekolah
        dataSekolah.forEach((sekolah, index) => {
            try {
                if (!sekolah.lat || !sekolah.lng) {
                    console.warn(`Sekolah ${sekolah.nama} tidak memiliki koordinat valid`);
                    return;
                }

                const marker = L.marker([sekolah.lat, sekolah.lng], { 
                    icon: customIcon,
                    title: sekolah.nama
                }).addTo(map);

                marker.bindPopup(createPopupContent(sekolah), { 
                    className: 'custom-popup', 
                    maxWidth: 320 
                });

                // Simpan referensi marker
                markers[sekolah.id] = marker;

                console.log(`Marker ${index + 1} created for:`, sekolah.nama);
            } catch (err) {
                console.error('Error creating marker for', sekolah.nama, err);
            }
        });

        // Fungsi Lihat Profil
        function lihatProfil(id) {
            window.location.href = `profil.php?id=${id}`;
        }

        // Search Functionality
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        const searchBtn = document.getElementById('searchBtn');

        // Fungsi untuk normalize string (remove diacritics, lowercase)
        function normalizeString(str) {
            return str.toLowerCase()
                .normalize("NFD")
                .replace(/[\u0300-\u036f]/g, "")
                .trim();
        }

        // Fungsi untuk melakukan pencarian
        function performSearch(query) {
            if (!query || query.length < 2) {
                searchResults.classList.remove('active');
                return;
            }

            const normalizedQuery = normalizeString(query);
            
            const filtered = dataSekolah.filter(s => {
                const nama = normalizeString(s.nama || '');
                const alamat = normalizeString(s.alamat || '');
                return nama.includes(normalizedQuery) || alamat.includes(normalizedQuery);
            });

            displaySearchResults(filtered);
        }

        // Fungsi untuk menampilkan hasil pencarian
        function displaySearchResults(results) {
            if (results.length > 0) {
                searchResults.innerHTML = results.map(s => `
                    <div class="search-result-item" data-id="${s.id}">
                        <div class="result-name">${s.nama}</div>
                        <div class="result-address">${s.alamat}</div>
                    </div>
                `).join('');
                searchResults.classList.add('active');

                // Add click listeners
                document.querySelectorAll('.search-result-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const id = parseInt(this.dataset.id);
                        const sekolah = dataSekolah.find(s => s.id === id);
                        if (sekolah) {
                            selectSekolah(sekolah);
                        }
                    });
                });
            } else {
                searchResults.innerHTML = '<div class="no-results">Tidak ada hasil ditemukan</div>';
                searchResults.classList.add('active');
            }
        }

        // Fungsi untuk memilih sekolah dari hasil pencarian
        function selectSekolah(sekolah) {
            map.setView([sekolah.lat, sekolah.lng], 16);
            searchResults.classList.remove('active');
            searchInput.value = sekolah.nama;

            // Buka popup marker yang dipilih
            if (markers[sekolah.id]) {
                markers[sekolah.id].openPopup();
            }
        }

        // Event listener untuk input search
        searchInput.addEventListener('input', function() {
            performSearch(this.value);
        });

        // Event listener untuk tombol search
        searchBtn.addEventListener('click', function() {
            const query = searchInput.value.trim();
            if (!query) return;

            const normalizedQuery = normalizeString(query);
            const found = dataSekolah.find(s => 
                normalizeString(s.nama || '').includes(normalizedQuery)
            );

            if (found) {
                selectSekolah(found);
            } else {
                performSearch(query);
            }
        });

        // Enter key untuk search
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchBtn.click();
            }
        });

        // Close search results ketika klik di luar
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.search-box')) {
                searchResults.classList.remove('active');
            }
        });

        // Resize map ketika window di-resize
        window.addEventListener('resize', function() {
            map.invalidateSize();
        });
    </script>
</body>
</html>