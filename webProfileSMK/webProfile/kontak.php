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
        .hero-kontak {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 5rem 0 3rem;
            color: white;
            text-align: center;
        }
        
        .hero-kontak h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            animation: fadeInDown 0.8s ease;
        }
        
        .hero-kontak p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto;
            opacity: 0.9;
        }
        
        /* Team Section */
        .team-section {
            padding: 4rem 0;
            background: white;
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
        
        /* Member Card */
        .member-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            margin-bottom: 2rem;
            border: 2px solid transparent;
        }
        
        .member-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 50px rgba(102, 126, 234, 0.3);
            border-color: #667eea;
        }
        
        .member-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 1.5rem;
            border: 5px solid #667eea;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }
        
        .member-card:hover .member-photo {
            transform: scale(1.05);
            border-color: #764ba2;
        }
        
        .member-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .member-nim {
            color: #667eea;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        
        .member-role {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 1rem;
            font-style: italic;
        }
        
        .member-contact {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .contact-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .contact-icon:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        /* Info Section */
        .info-section {
            padding: 4rem 0;
            background: #f8f9fa;
        }
        
        .info-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }
        
        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .info-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .info-card h4 {
            color: #333;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .info-card p {
            color: #666;
            margin: 0;
            line-height: 1.8;
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
        
        .member-card {
            animation: fadeInUp 0.6s ease;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-kontak h1 {
                font-size: 2rem;
            }
            
            .section-title h2 {
                font-size: 2rem;
            }
            
            .member-photo {
                width: 120px;
                height: 120px;
            }
            
            .member-name {
                font-size: 1.2rem;
            }
        }
    </style>

</head>
<body>
<!-- Hero section -->
  <section class="hero-kontak">
      <div class="container">
            <h1><i class="fas fa-info-circle me-3"></i>Contact Person SIG SMK di Padang</h1>
            <p></p>
        </div>
  </section>


<!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <div class="section-title">
                <h2>Tim Developer</h2>
                <p>7 Orang Developer yang Berdedikasi</p>
            </div>
            
            <div class="row">
                <!-- Member 1 -->
                <div class="col-lg-4 col-md-6">
                    <div class="member-card">
                        <img src="gambar/rofiq.jpg" alt="Member 1" class="member-photo">
                        <h3 class="member-name">Rofiq  </h3>
                        <p class="member-nim">NIM: 2401091012</p>
                       
                        <div class="member-contact">
                            <a href="mailto:rofiqislamy88@gmail.com" class="contact-icon" title="Email">
                                <i class="fas fa-envelope"></i>
                            </a>
                            <a href="https://wa.me/62895337252897" class="contact-icon" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="https://github.com/qifor27" class="contact-icon" title="GitHub">
                                <i class="fab fa-github"></i>
                            </a>
                             <a href="https://www.instagram.com/luqifor27/" class="contact-icon" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Member 2 -->
                <div class="col-lg-4 col-md-6">
                    <div class="member-card">
                        <img src="gambar/rafa.jpg" alt="Member 2" class="member-photo">
                        <h3 class="member-name">Rafa </h3>
                        <p class="member-nim">NIM: 2401092012</p>
                        <div class="member-contact">
                            <a href="mailto:siti.nurhaliza@example.com" class="contact-icon" title="Email">
                                <i class="fas fa-envelope"></i>
                            </a>
                            <a href="https://wa.me/6281378648533" class="contact-icon" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="https://github.com/friietz24" class="contact-icon" title="GitHub">
                                <i class="fab fa-github"></i>
                            </a>
                             <a href="https://www.instagram.com/rafamaaritza/" class="contact-icon" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Member 3 -->
                <div class="col-lg-4 col-md-6">
                    <div class="member-card">
                        <img src="gambar/azlan.jpg" alt="Member 3" class="member-photo">
                        <h3 class="member-name"> Azlan </h3>
                        <p class="member-nim">NIM: 2401092008</p>
                        <div class="member-contact">
                            <a href="mailto:maritzakardinal@gmail.com" class="contact-icon" title="Email">
                                <i class="fas fa-envelope"></i>
                            </a>
                            <a href="https://wa.me/6283801955081" class="contact-icon" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="https://github.com/MuhammadAzlan286" class="contact-icon" title="GitHub">
                                <i class="fab fa-github"></i>
                            </a>
                            <a href="https://www.instagram.com/azlanallin/" class="contact-icon" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Member 4 -->
                <div class="col-lg-4 col-md-6">
                    <div class="member-card">
                        <img src="gambar/velisa.jpg" alt="Member 4" class="member-photo">
                        <h3 class="member-name">Veli </h3>
                        <p class="member-nim">NIM: 2401092018</p>
                        <div class="member-contact">
                            <a href="mailto:veliramadhani2320.com" class="contact-icon" title="Email">
                                <i class="fas fa-envelope"></i>
                            </a>
                            <a href="https://wa.me/6285975388302" class="contact-icon" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="https://github.com/veliramadhani" class="contact-icon" title="GitHub">
                                <i class="fab fa-github"></i>
                            </a>
                            <a href="https://www.instagram.com/veli_ramadhani/" class="contact-icon" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Member 5 -->
                <div class="col-lg-4 col-md-6">
                    <div class="member-card">
                        <img src="gambar/mutiata.jpg" alt="Member 5" class="member-photo">
                        <h3 class="member-name">Mutiara</h3>
                        <p class="member-nim">NIM: 2401091013</p>
                        <div class="member-contact">
                            <a href="mailto:mutiaraazzh2708@gmail.com" class="contact-icon" title="Email">
                                <i class="fas fa-envelope"></i>
                            </a>
                            <a href="https://wa.me/62895619675445" class="contact-icon" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="https://github.com/mutiaraazzh2708" class="contact-icon" title="GitHub">
                                <i class="fab fa-github"></i>
                            </a>
                            <a href="https://www.instagram.com/xzzhy27_/" class="contact-icon" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Member 6 -->
                <div class="col-lg-4 col-md-6">
                    <div class="member-card">
                        <img src="gambar/sucijpg.jpg" alt="Member 6" class="member-photo">
                        <h3 class="member-name">Suci </h3>
                        <p class="member-nim">NIM: 24010910</p>
                        <p class="member-role">Anggota Developer</p>
                        <div class="member-contact">
                            <a href="mailto:auliasuciputri7@gmail.com" class="contact-icon" title="Email">
                                <i class="fas fa-envelope"></i>
                            </a>
                            <a href="https://wa.me/6283130593507" class="contact-icon" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="https://github.com/auliasuciputri7-max" class="contact-icon" title="GitHub">
                                <i class="fab fa-github"></i>
                            </a>
                            <a href="https://www.instagram.com/chyii06/" class="contact-icon" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
                  </div>

                <!-- Member 7 -->
                <div class="col-lg-4 col-md-6 offset-md-4">
                    <div class="member-card">
                        <img src="gambar/sab.jpg" alt="Member 7" class="member-photo">
                        <h3 class="member-name"> Sabrina</h3>
                        <p class="member-nim">NIM: 2401093011</p>
                        <p class="member-role">Anggota Developer</p>
                        <div class="member-contact">
                            <a href="mailto:sabrinaarumiey@gmail.com" class="contact-icon" title="Email">
                                <i class="fas fa-envelope"></i>
                            </a>
                            <a href="https://wa.me/6283185761228" class="contact-icon" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="https://github.com/Sabrinaharumi187" class="contact-icon" title="GitHub">
                                <i class="fab fa-github"></i>
                            </a>
                            <a href="https://www.instagram.com/sabrinaarumm/" class="contact-icon" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
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