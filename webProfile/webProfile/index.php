<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIG SMK Negeri Kota Padang</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        /* Navbar Styles */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .nav-link {
            color: #333 !important;
            font-weight: 500;
            margin: 0 10px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link:hover {
            color: #667eea !important;
            transform: translateY(-2px);
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-link:hover::after {
            width: 80%;
        }
        
        /* Search Section */
        .search-section {
            background: white;
            padding: 2rem;
            margin: 2rem auto;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
        }
        
        .search-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .search-box {
            position: relative;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .search-input {
            width: 100%;
            padding: 15px 50px 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 50px;
            font-size: 1rem;
            transition: all 0.3s ease;
            outline: none;
        }
        
        .search-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .search-btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 10px 25px;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .search-btn:hover {
            transform: translateY(-50%) scale(1.05);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 15px;
            margin-top: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            max-height: 300px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
        }
        
        .search-results.active {
            display: block;
        }
        
        .search-result-item {
            padding: 15px 20px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.2s ease;
        }
        
        .search-result-item:hover {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            padding-left: 25px;
        }
        
        .search-result-item:last-child {
            border-bottom: none;
        }
        
        .result-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        
        .result-address {
            font-size: 0.85rem;
            color: #666;
        }
        
        /* Map Container */
        .map-container {
            background: white;
            padding: 2rem;
            margin: 2rem auto;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
        }
        
        .map-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .map-title {
            font-weight: 600;
            color: #333;
        }
        
        .map-info {
            display: flex;
            gap: 20px;
        }
        
        .info-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        #map {
            height: 600px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Custom Popup Styles */
        .custom-popup .leaflet-popup-content-wrapper {
            border-radius: 15px;
            padding: 0;
            overflow: hidden;
        }
        
        .custom-popup .leaflet-popup-content {
            margin: 0;
            width: 280px !important;
        }
        
        .popup-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            text-align: center;
        }
        
        .popup-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .popup-body {
            padding: 15px;
        }
        
        .popup-info {
            margin-bottom: 10px;
            display: flex;
            align-items: start;
            gap: 10px;
        }
        
        .popup-info i {
            color: #667eea;
            margin-top: 3px;
        }
        
        .popup-info-text {
            flex: 1;
            font-size: 0.9rem;
            color: #666;
        }
        
        .popup-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            width: 100%;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        .popup-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        /* Custom Marker */
        .custom-marker {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            width: 40px;
            height: 40px;
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            border: 3px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .custom-marker::after {
            content: '🏫';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(45deg);
            font-size: 20px;
        }
        
        /* Footer */
        footer {
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem 0;
            margin-top: 3rem;
            text-align: center;
        }
        
        footer p {
            margin: 0;
            color: #666;
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .search-section, .map-container {
            animation: fadeInUp 0.6s ease;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .search-section, .map-container {
                margin: 1rem;
                padding: 1.5rem;
            }
            
            #map {
                height: 400px;
            }
            
            .map-header {
                flex-direction: column;
                gap: 15px;
            }
            
            .info-badge {
                font-size: 0.8rem;
                padding: 6px 15px;
            }
        }
    </style>
</head>
<body>
    
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
                        <a class="nav-link active" href="#">
                            <i class="fas fa-home me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang">
                            <i class="fas fa-info-circle me-1"></i> Tentang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#data">
                            <i class="fas fa-database me-1"></i> Data SMK
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontak">
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

    <div class="container-fluid">
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
                        <span id="totalSekolah">5</span> Sekolah
                    </span>
                </div>
            </div>
            <div id="map"></div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2024 SIG SMK Kota Padang. Dikembangkan dengan <i class="fas fa-heart text-danger"></i> untuk pendidikan Indonesia.</p>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        // Data SMK (nanti akan diambil dari database via PHP/AJAX)
        const dataSekolah = [
            {
                id: 1,
                nama: "SMKN 1 Padang",
                alamat: "Jl. Belanti Raya No.11, Lolong Belanti, Kec. Padang Utara",
                lat: -0.9290,
                lng: 100.3543,
                jumlahSiswa: 1200,
                programKeahlian: "Teknik Komputer Jaringan, Rekayasa Perangkat Lunak, Multimedia"
            },
            {
                id: 2,
                nama: "SMKN 2 Padang",
                alamat: "Jl. Prof. M. Yamin SH No.167, Ulak Karang Utara",
                lat: -0.9471,
                lng: 100.3672,
                jumlahSiswa: 1100,
                programKeahlian: "Akuntansi, Administrasi Perkantoran, Pemasaran"
            },
            {
                id: 3,
                nama: "SMKN 3 Padang",
                alamat: "Jl. S. Parman No.2, Ulak Karang, Kec. Padang Utara",
                lat: -0.9380,
                lng: 100.3590,
                jumlahSiswa: 950,
                programKeahlian: "Tata Boga, Tata Busana, Perhotelan"
            },
            {
                id: 4,
                nama: "SMKN 4 Padang",
                alamat: "Jl. Teluk Bayur, Air Pacah, Kec. Koto Tangah",
                lat: -0.9800,
                lng: 100.3800,
                jumlahSiswa: 800,
                programKeahlian: "Teknik Mesin, Teknik Otomotif, Teknik Listrik"
            },
            {
                id: 5,
                nama: "SMKN 5 Padang",
                alamat: "Jl. Khatib Sulaiman, Alai Parak Kopi, Kec. Padang Utara",
                lat: -0.9200,
                lng: 100.3620,
                jumlahSiswa: 1050,
                programKeahlian: "Teknik Konstruksi Gedung, Teknik Gambar Bangunan"
            }
        ];

        // Inisialisasi Peta
        const map = L.map('map').setView([-0.9471, 100.3672], 13);

        // Tile Layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Custom Icon
        const customIcon = L.divIcon({
            className: 'custom-marker',
            iconSize: [40, 40],
            iconAnchor: [20, 40],
            popupAnchor: [0, -40]
        });

        // Tambahkan Marker
        dataSekolah.forEach(sekolah => {
            const marker = L.marker([sekolah.lat, sekolah.lng], { icon: customIcon }).addTo(map);
            
            const popupContent = `
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
                        <div class="popup-info-text"><strong>${sekolah.jumlahSiswa}</strong> Siswa</div>
                    </div>
                    <div class="popup-info">
                        <i class="fas fa-graduation-cap"></i>
                        <div class="popup-info-text">${sekolah.programKeahlian}</div>
                    </div>
                    <button class="popup-btn" onclick="lihatProfil(${sekolah.id})">
                        <i class="fas fa-eye me-2"></i>Lihat Profil Lengkap
                    </button>
                </div>
            `;
            
            marker.bindPopup(popupContent, {
                className: 'custom-popup',
                maxWidth: 280
            });
        });

        // Fungsi Lihat Profil
        function lihatProfil(id) {
            // Redirect ke halaman profil (nanti buat file profil.php?id=...)
            window.location.href = `profil.php?id=${id}`;
        }

        // Search Functionality
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        const searchBtn = document.getElementById('searchBtn');

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            
            if (query.length < 2) {
                searchResults.classList.remove('active');
                return;
            }

            const filtered = dataSekolah.filter(sekolah => 
                sekolah.nama.toLowerCase().includes(query) || 
                sekolah.alamat.toLowerCase().includes(query)
            );

            if (filtered.length > 0) {
                searchResults.innerHTML = filtered.map(sekolah => `
                    <div class="search-result-item" onclick="selectSekolah(${sekolah.lat}, ${sekolah.lng}, '${sekolah.nama}')">
                        <div class="result-name">${sekolah.nama}</div>
                        <div class="result-address">${sekolah.alamat}</div>
                    </div>
                `).join('');
                searchResults.classList.add('active');
            } else {
                searchResults.innerHTML = `
                    <div class="search-result-item" style="text-align: center; color: #999;">
                        Tidak ada hasil ditemukan
                    </div>
                `;
                searchResults.classList.add('active');
            }
        });

        function selectSekolah(lat, lng, nama) {
            map.setView([lat, lng], 16);
            searchResults.classList.remove('active');
            searchInput.value = nama;
            
            // Buka popup marker yang dipilih
            map.eachLayer(layer => {
                if (layer instanceof L.Marker) {
                    const markerLatLng = layer.getLatLng();
                    if (markerLatLng.lat === lat && markerLatLng.lng === lng) {
                        layer.openPopup();
                    }
                }
            });
        }

        // Close search results ketika klik di luar
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.search-box')) {
                searchResults.classList.remove('active');
            }
        });

        // Search button
        searchBtn.addEventListener('click', function() {
            const query = searchInput.value.toLowerCase().trim();
            if (query) {
                const found = dataSekolah.find(sekolah => 
                    sekolah.nama.toLowerCase().includes(query)
                );
                if (found) {
                    selectSekolah(found.lat, found.lng, found.nama);
                }
            }
        });

        // Enter key untuk search
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchBtn.click();
            }
        });
    </script>
</body>
</html>