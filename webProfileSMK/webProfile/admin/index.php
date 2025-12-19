<?php

include "includes/header.php";

require_once '../config/function.php';
require_once '../config/koneksi.php';

// CEK LOGIN DI SINI!
cekLogin();

// Jika sampai sini berarti sudah login
$smk_count = 0;
$jurusan_count = 0;
$guru_count = 0;
$siswa_count = 0;

//* ambil jumlah smk
$query = $koneksi->query("SELECT COUNT(*) as jml from tb_smk");
if ($query) {
    $r = $query->fetch_assoc();
    $smk_count = (int)$r['jml'];
}

//* ambil jumlah siswa
$query = $koneksi->query("SELECT SUM(jumlah_siswa) as total from tb_smk");
if ($query){
    $r = $query -> fetch_assoc();
    $siswa_count = (int)$r['total'];
}

//* ambil jumlah guru
$query = $koneksi->query("SELECT SUM(jumlah_guru) as total from tb_smk");
if ($query){
    $r = $query -> fetch_assoc();
    $guru_count = (int)$r['total'];
}

$latestData = [];
$query = $koneksi->query("SELECT id_smk,npsn,nama_sekolah,jumlah_siswa,jumlah_guru from tb_smk order by id_smk desc limit 5");
if ($query){
    while ($row = $query-> fetch_assoc()) {
        $latestData[] = $row;
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <style>
        body {background:white;}
        .sidebar {min-height:75vh;}
        .card-quick {cursor: pointer; transition: transform .12s ease;}
        .card-quick:hover {transform: translateY(-4px);}
        .avatar { width:46px; height:46px; border-radius:8px;background: linear-gradient(135deg,#6f42c1,#0d6efd);display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:600; }
    </style>
       <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
   <div class="container-fluid mt-4">
        <div class="row justify-content-center">
      
        <main class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h2 class="mb-0">Dashboard Data SMK</h2>
                        <p class="text-muted small mb-0">Ringkasan data dan aksi cepat</p>
                    </div>
            </div>
            <div class="row g-3 mb-4">

                <div class="col-sm-6 col-md-4">
                    <div class="card card-quick shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="avatar me-3">
                                SMK
                            </div>
                            <div>
                                 <small class="text-muted">Total SMK</small>
                            <h4 class="mb-0"><?= $smk_count ?> </h4>
                            <a href="smk/data-smk.php" class="small">Lihat Daftar</a>
                            </div>
                        </div>
                    </div>
                </div>

                 <div class="col-sm-6 col-md-4">
                    <div class="card card-quick shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="avatar me-3">
                                J
                            </div>
                            <div>
                                 <small class="text-muted">Total Kejuruan</small>
                            <h4 class="mb-0"><?= $jurusan_count ?> </h4>
                            <a href="kejuruan/data-kejuruan.php" class="small">Lihat Daftar</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4">
                    <div class="card card-quick shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="avatar me-3">
                                G
                            </div>
                            <div>
                                 <small class="text-muted">Total Guru</small>
                            <h4 class="mb-0"><?= $guru_count ?> </h4>
                            <a href="guru/data-guru.php" class="small">Lihat Daftar</a>
                            </div>
                        </div>
                    </div>
                </div>

                 <div class="col-sm-6 col-md-4">
                    <div class="card card-quick shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="avatar me-3">
                                S
                            </div>
                            <div>
                                 <small class="text-muted">Total Siswa</small>
                            <h4 class="mb-0"><?= $siswa_count ?> </h4>
                            <a href="siswa/siswa.php" class="small">Lihat Daftar</a>
                            </div>
                        </div>
                    </div>
                </div>

                 <div class="col-12 col-md-4">
              <div class="card card-quick shadow-sm">
                <div class="card-body ">
                  <small class="text-muted">Tindakan Cepat</small>
                  <div class="d-grid gap-2 mt-2">
                    <a href="smk/tambah-smk.php" class="btn" style=" background: linear-gradient(135deg, #8395e6ff 0%, #764ba2 100%); color:white;">Tambah SMK</a>
                     <a href="kejuruan/tambah-kejuruan.php" class="btn" style=" background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white;">Tambah Kejuruan</a>
                  
                  </div>
                </div>
              </div>
            </div>

               <div class="col-12 col-md-4">
              <div class="card card-quick shadow-sm">
                <div class="card-body ">
                  <small class="text-muted">Tindakan Cepat</small>
                  <div class="d-grid gap-2 mt-2">
                    <a href="upload/upload-galeri.php" class="btn" style=" background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);color:white;;">Tambah Foto</a>
                     <a href="mahasiswa/create.php" class="btn" style=" background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white;">Tambah Siswa</a>
                </div>
              </div>
            </div>
            
          </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Data Terbaru</h5>
                    <?php if (count($latestData) === 0): ?>
                         <p class="text-muted mb-0">Belum ada data</p>
                   <?php else: ?>
                    <div class="table-responsive">
                            <table class="table table-striped align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>NPSN</th>
                                            <th>Nama Sekolah</th>
                                            <th>jumlah siswa</th>
                                            <th>jumlah guru</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($latestData as $i => $m ): ?>
                                            <tr>
                                                <td><?= $i+1 ?></td>
                                                <td><?= htmlspecialchars($m['npsn']) ?></td>
                                                <td><?= htmlspecialchars($m['nama_sekolah']) ?></td>
                                                <td><?= htmlspecialchars($m['jumlah_siswa']) ?></td>
                                                <td><?= htmlspecialchars($m['jumlah_guru']) ?></td>

                                            </tr>
                                            <?php  endforeach; ?>
                                    </tbody>
                            </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
   </div>
</body>
</html>