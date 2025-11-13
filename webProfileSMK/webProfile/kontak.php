<?php
    include 'template/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Document</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            background: #f8f9fa;
        }
        
        /* Navbar */
        .navbar-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }
        
        .nav-link {
            color: white !important;
            font-weight: 500;
            margin: 0 10px;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            transform: translateY(-2px);
        }
        
        /* Hero Section */
        .hero-tentang {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 5rem 0 3rem;
            color: white;
            text-align: center;
        }
        
        .hero-tentang h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            animation: fadeInDown 0.8s ease;
        }
        
        .hero-tentang p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto;
            opacity: 0.9;
            animation: fadeInUp 0.8s ease;
        }
        
        /* About Section */
        .about-section {
            padding: 4rem 0;
            background: white;
        }
        
        .about-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }
        
        .about-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 50px rgba(102, 126, 234, 0.2);
        }
        
        .about-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
        }
        
        .about-card h3 {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .about-card p {
            color: #666;
            line-height: 1.8;
        }
        
        /* Features Section */
        .features-section {
            padding: 4rem 0;
            background: #f8f9fa;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1rem;
        }
        
        .section-title p {
            color: #666;
            font-size: 1.1rem;
        }
        
        .feature-box {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .feature-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.15);
        }
        
        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }
        
        .feature-box h4 {
            color: #333;
            font-weight: 600;
            margin-bottom: 0.8rem;
        }
        
        .feature-box p {
            color: #666;
            font-size: 0.95rem;
            margin: 0;
        }
        
        /* Tech Stack Section */
        .tech-section {
            padding: 4rem 0;
            background: white;
        }
        
        .tech-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .tech-item {
            background: #f8f9fa;
            padding: 2rem 1rem;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .tech-item:hover {
            border-color: #667eea;
            transform: scale(1.05);
        }
        
        .tech-item i {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 1rem;
        }
        
        .tech-item h5 {
            color: #333;
            font-weight: 600;
            margin: 0;
            font-size: 1rem;
        }
        
        /* Team Section */
        .team-section {
            padding: 4rem 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .team-section .section-title h2,
        .team-section .section-title p {
            color: white;
        }
        
        .team-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        
        .team-card:hover {
            transform: translateY(-10px);
        }
        
        .team-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
        }
        
        .team-card h4 {
            color: #333;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .team-card .role {
            color: #667eea;
            font-weight: 500;
            margin-bottom: 1rem;
        }
        
        .team-card p {
            color: #666;
            font-size: 0.9rem;
        }
        
        /* Stats Section */
        .stats-section {
            background: white;
            padding: 3rem 0;
        }
        
        .stat-box {
            text-align: center;
            padding: 2rem;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #666;
            font-size: 1.1rem;
            font-weight: 500;
        }
        
        /* Footer */
        footer {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem 0;
            text-align: center;
            color: white;
        }
        
        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
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
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-tentang h1 {
                font-size: 2rem;
            }
            
            .hero-tentang p {
                font-size: 1rem;
            }
            
            .section-title h2 {
                font-size: 2rem;
            }
            
            .tech-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
        }
      </style>
</head>
<body>
<!-- Hero section -->
  <section class="hero-tentang">
      <div class="container">
            <h1><i class="fas fa-info-circle me-3"></i>Contact Person SIG SMK di Padang</h1>
            <p></p>
        </div>
  </section>

<!-- About section -->
    <section class="about-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="about-card">
                        <div class="about-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3>Tujuan</h3>
                        <p>Menyediakan informasi lokasi dan profil SMK Negeri di Kota Padang secara interaktif dan mudah diakses oleh masyarakat, siswa, dan orang tua.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="about-card">
                        <div class="about-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h3>Visi</h3>
                        <p>Menjadi platform digital terdepan dalam penyediaan informasi pendidikan vokasi yang transparan dan akurat di Kota Padang.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="about-card">
                        <div class="about-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <h3>Misi</h3>
                        <p>Memfasilitasi akses informasi pendidikan SMK yang mudah, cepat, dan akurat untuk mendukung pendidikan vokasi berkualitas.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- Stats section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-box">
                        <div class="stat-number">14</div>
                        <div class="stat-label">SMK Negeri</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-box">
                        <div class="stat-number">12K+</div>
                        <div class="stat-label">Total Siswa</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-box">
                        <div class="stat-number">45+</div>
                        <div class="stat-label">Program Keahlian</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-box">
                        <div class="stat-number">99.9%</div>
                        <div class="stat-label">Data Akurat</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
<?php
    include 'template/footer.php';
?>