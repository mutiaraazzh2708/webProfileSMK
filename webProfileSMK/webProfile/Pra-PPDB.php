<?php
// Koneksi Database

include 'config/koneksi.php';
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Fungsi Hitung Jarak (Haversine Formula)
function hitungJarak($lat1, $lon1, $lat2, $lon2) {
    $earth_radius = 6371; // Radius bumi dalam km
    
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    
    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon/2) * sin($dLon/2);
    
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    $jarak = $earth_radius * $c;
    
    return round($jarak, 2);
}

// Inisialisasi variabel
$hasil_pencarian = [];
$jurusan_dipilih = "";
$user_lat = "";
$user_lng = "";
$error_message = "";

// Proses pencarian jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jurusan_dipilih = mysqli_real_escape_string($koneksi, $_POST['jurusan']);
    $user_lat = floatval($_POST['latitude']);
    $user_lng = floatval($_POST['longitude']);
    
    // Validasi input
    if (empty($jurusan_dipilih)) {
        $error_message = "Silakan pilih jurusan terlebih dahulu";
    } elseif (empty($user_lat) || empty($user_lng)) {
        $error_message = "Silakan isi koordinat lokasi Anda";
    } elseif ($user_lat < -90 || $user_lat > 90 || $user_lng < -180 || $user_lng > 180) {
        $error_message = "Koordinat tidak valid";
    } else {
        // Query untuk mencari SMK dengan jurusan yang dipilih
        $query = "SELECT s.*, j.nama_jurusan, j.jumlah_siswa, j.jumlah_rombel 
                  FROM sekolah s 
                  INNER JOIN jurusan j ON s.id_sekolah = j.id_sekolah 
                  WHERE j.nama_jurusan = '$jurusan_dipilih'";
        
        $search_result = mysqli_query($koneksi, $query);
        
        if ($search_result && mysqli_num_rows($search_result) > 0) {
            while ($row = mysqli_fetch_assoc($search_result)) {
                $smk_lat = floatval($row['latitude']);
                $smk_lng = floatval($row['longitude']);
                
                // Hitung jarak
                $jarak = hitungJarak($user_lat, $user_lng, $smk_lat, $smk_lng);
                $row['jarak'] = $jarak;
                $hasil_pencarian[] = $row;
            }
            
            // Sort berdasarkan jarak terdekat
            usort($hasil_pencarian, function($a, $b) {
                return $a['jarak'] <=> $b['jarak'];
            });
        }
    }
}

// Query untuk mendapatkan daftar jurusan
$query_jurusan = "SELECT DISTINCT nama_kejuruan FROM tb_kejuruan ORDER BY nama_jurusan ASC";
$result_jurusan = mysqli_query($koneksi, $query_jurusan);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pra-PPDB SMK - Cari SMK Terdekat</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px;
        }
        
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
        }
        
        .info-box i {
            color: #667eea;
            margin-right: 10px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group label .required {
            color: #e74c3c;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-error {
            background: #fee;
            border-left: 4px solid #e74c3c;
            color: #c0392b;
        }
        
        .results-header {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0 20px 0;
            text-align: center;
        }
        
        .results-header h2 {
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .results-count {
            color: #666;
            font-size: 1.1em;
        }
        
        .school-card {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            transition: all 0.3s;
            position: relative;
        }
        
        .school-card:hover {
            border-color: #667eea;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.2);
            transform: translateY(-2px);
        }
        
        .school-rank {
            position: absolute;
            top: -15px;
            left: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2em;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }
        
        .school-name {
            color: #2c3e50;
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 10px;
            padding-left: 10px;
        }
        
        .school-distance {
            color: #e74c3c;
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 15px;
            padding-left: 10px;
        }
        
        .school-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        
        .info-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .info-item i {
            color: #667eea;
            margin-right: 8px;
        }
        
        .info-label {
            font-size: 0.9em;
            color: #666;
            display: block;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-weight: bold;
            color: #333;
            font-size: 1.1em;
        }
        
        .school-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .no-results {
            text-align: center;
            padding: 60px 20px;
        }
        
        .no-results i {
            font-size: 4em;
            color: #ccc;
            margin-bottom: 20px;
        }
        
        .no-results h3 {
            color: #666;
            margin-bottom: 10px;
        }
        
        #map {
            width: 100%;
            height: 400px;
            border-radius: 12px;
            margin-top: 30px;
            border: 2px solid #e0e0e0;
        }
        
        @media (max-width: 768px) {
            .header h1 {
                font-size: 1.8em;
            }
            
            .school-info {
                grid-template-columns: 1fr;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-school"></i> Pra-PPDB SMK</h1>
            <p>Temukan SMK terdekat dari rumah Anda sesuai dengan jurusan pilihan</p>
        </div>
        
        <div class="content">
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <strong>Cara Penggunaan:</strong> Pilih jurusan yang Anda minati, kemudian klik "Dapatkan Lokasi Saya" atau masukkan koordinat rumah Anda secara manual. Sistem akan menampilkan SMK terdekat yang memiliki jurusan pilihan Anda.
            </div>
            
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" id="searchForm">
                <div class="form-group">
                    <label>
                        <i class="fas fa-graduation-cap"></i> Pilih Jurusan / Program Keahlian 
                        <span class="required">*</span>
                    </label>
                    <select name="jurusan" class="form-control" required>
                        <option value="">-- Pilih Jurusan yang Diminati --</option>
                        <?php while ($jurusan = mysqli_fetch_assoc($result_jurusan)): ?>
                            <option value="<?php echo htmlspecialchars($jurusan['nama_jurusan']); ?>"
                                <?php echo ($jurusan_dipilih == $jurusan['nama_jurusan']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($jurusan['nama_jurusan']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>
                        <i class="fas fa-map-marker-alt"></i> Latitude (Koordinat Rumah) 
                        <span class="required">*</span>
                    </label>
                    <input type="text" name="latitude" id="latitude" class="form-control" 
                           placeholder="Klik tombol 'Dapatkan Lokasi Saya' di bawah" 
                           value="<?php echo htmlspecialchars($user_lat); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>
                        <i class="fas fa-map-marker-alt"></i> Longitude (Koordinat Rumah) 
                        <span class="required">*</span>
                    </label>
                    <input type="text" name="longitude" id="longitude" class="form-control" 
                           placeholder="Klik tombol 'Dapatkan Lokasi Saya' di bawah" 
                           value="<?php echo htmlspecialchars($user_lng); ?>" required>
                </div>
                
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="getLocation()">
                        <i class="fas fa-crosshairs"></i> Dapatkan Lokasi Saya
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Cari SMK Terdekat
                    </button>
                </div>
            </form>
            
            <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($error_message)): ?>
                <div class="results-header">
                    <h2><i class="fas fa-list"></i> Hasil Pencarian</h2>
                    <p class="results-count"><?php echo count($hasil_pencarian); ?> SMK Ditemukan</p>
                </div>
                
                <?php if (count($hasil_pencarian) > 0): ?>
                    <?php foreach ($hasil_pencarian as $index => $smk): ?>
                        <div class="school-card">
                            <div class="school-rank"><?php echo $index + 1; ?></div>
                            
                            <h3 class="school-name">
                                <?php echo htmlspecialchars($smk['nama_sekolah']); ?>
                            </h3>
                            
                            <div class="school-distance">
                                <i class="fas fa-route"></i> <?php echo $smk['jarak']; ?> km dari rumah Anda
                            </div>
                            
                            <div class="school-info">
                                <div class="info-item">
                                    <span class="info-label">Jurusan</span>
                                    <div class="info-value">
                                        <i class="fas fa-book"></i>
                                        <?php echo htmlspecialchars($smk['nama_jurusan']); ?>
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Siswa Jurusan Ini</span>
                                    <div class="info-value">
                                        <i class="fas fa-users"></i>
                                        <?php echo number_format($smk['jumlah_siswa']); ?> Siswa
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Rombongan Belajar</span>
                                    <div class="info-value">
                                        <i class="fas fa-door-open"></i>
                                        <?php echo $smk['jumlah_rombel']; ?> Rombel
                                    </div>
                                </div>
                                
                                <?php if (!empty($smk['telepon'])): ?>
                                <div class="info-item">
                                    <span class="info-label">Telepon</span>
                                    <div class="info-value">
                                        <i class="fas fa-phone"></i>
                                        <?php echo htmlspecialchars($smk['telepon']); ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="school-actions">
                                <a href="profil_sekolah.php?id=<?php echo $smk['id_sekolah']; ?>" 
                                   class="btn btn-primary" target="_blank">
                                    <i class="fas fa-eye"></i> Lihat Profil Lengkap
                                </a>
                                <a href="https://www.google.com/maps/dir/?api=1&origin=<?php echo $user_lat; ?>,<?php echo $user_lng; ?>&destination=<?php echo $smk['latitude']; ?>,<?php echo $smk['longitude']; ?>" 
                                   class="btn btn-secondary" target="_blank">
                                    <i class="fas fa-directions"></i> Petunjuk Arah (Google Maps)
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <div id="map"></div>
                <?php else: ?>
                    <div class="no-results">
                        <i class="fas fa-search"></i>
                        <h3>Tidak Ada SMK Ditemukan</h3>
                        <p>Maaf, tidak ada SMK yang memiliki jurusan <strong><?php echo htmlspecialchars($jurusan_dipilih); ?></strong> di sekitar lokasi Anda.</p>
                        <button onclick="location.reload()" class="btn btn-primary" style="margin-top: 20px;">
                            <i class="fas fa-redo"></i> Coba Lagi
                        </button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                alert("Geolocation tidak didukung oleh browser ini.");
            }
        }
        
        function showPosition(position) {
            document.getElementById("latitude").value = position.coords.latitude;
            document.getElementById("longitude").value = position.coords.longitude;
        }
        
        function showError(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    alert("Anda menolak permintaan untuk mendapatkan lokasi.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Informasi lokasi tidak tersedia.");
                    break;
                case error.TIMEOUT:
                    alert("Permintaan untuk mendapatkan lokasi timeout.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("Terjadi kesalahan yang tidak diketahui.");
                    break;
            }
        }
        
        <?php if (count($hasil_pencarian) > 0): ?>
        // Inisialisasi peta dengan Leaflet (alternatif gratis untuk Google Maps)
        document.addEventListener('DOMContentLoaded', function() {
            // Anda bisa menggunakan Google Maps API atau Leaflet
            // Contoh sederhana dengan iframe Google Maps
            var mapDiv = document.getElementById('map');
            var markers = '<?php echo $user_lat . ',' . $user_lng; ?>';
            
            <?php foreach ($hasil_pencarian as $smk): ?>
            markers += '|<?php echo $smk['latitude'] . ',' . $smk['longitude']; ?>';
            <?php endforeach; ?>
            
            mapDiv.innerHTML = '<iframe width="100%" height="400" frameborder="0" style="border:0; border-radius: 12px;" src="https://www.google.com/maps/embed/v1/directions?key=YOUR_API_KEY&origin=<?php echo $user_lat . ',' . $user_lng; ?>&destination=<?php echo $hasil_pencarian[0]['latitude'] . ',' . $hasil_pencarian[0]['longitude']; ?>&mode=driving" allowfullscreen></iframe>';
        });
        <?php endif; ?>
    </script>
</body>
</html>

<?php mysqli_close($koneksi); ?>