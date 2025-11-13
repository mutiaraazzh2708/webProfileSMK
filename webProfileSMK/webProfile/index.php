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
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body>
    
    <?php 
        include 'template/navbar.php';
    ?>

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
                        <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1200&h=500&fit=crop" class="d-block w-100" alt="Pendidikan Berkualitas">
                        <div class="carousel-caption">
                            <h2>Sistem Informasi Geografis</h2>
                            <p>SMK Negeri Kota Padang - Menuju Pendidikan Vokasi Berkualitas</p>
                            <button class="carousel-btn">Jelajahi Sekarang</button>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?w=1200&h=500&fit=crop" alt="Fasilitas Modern">
                        <div class="carousel-caption">
                            <h2>Fasilitas Lengkap & Modern</h2>
                            <p>Laboratorium dan Workshop Berstandar Industri</p>
                            <button class="carousel-btn">Lihat Fasilitas</button>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?w=1200&h=500&fit=crop" alt="Prestasi Siswa">
                        <div class="carousel-caption">
                            <h2>Prestasi Membanggakan</h2>
                            <p>Siswa Berprestasi di Tingkat Nasional & Internasional</p>
                            <button class="carousel-btn">Lihat Prestasi</button>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?w=1200&h=500&fit=crop" alt="Program Keahlian">
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


    <?php 
        include 'template/footer.php';
    ?>
    
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