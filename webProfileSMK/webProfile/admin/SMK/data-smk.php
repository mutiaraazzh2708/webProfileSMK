<?php  

include "../includes/header.php";
require "../../config/koneksi.php";
?>


</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
    <style>
    .table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .card-header-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border: none;
    }

    .card-header-custom h4 {
        margin: 0;
        font-weight: 600;
    }

    .table th {
        background-color: #f8f9fa;
        color: #333;
        font-weight: 600;
        white-space: nowrap;
        vertical-align: middle;
        border-bottom: 2px solid #dee2e6;
    }

    .table td {
        vertical-align: middle;
        white-space: nowrap;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
        transition: all 0.2s;
    }

    .btn-action-group {
        display: flex;
        gap: 5px;
        flex-wrap: nowrap;
    }

    .btn-sm {
        padding: 5px 10px;
        font-size: 12px;
    }

    .badge-status {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .text-truncate-custom {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        color: #dee2e6;
    }

    .search-box {
        margin-bottom: 20px;
    }

    .search-input {
        max-width: 400px;
    }
    </style>
</head>
<body>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="table-container">
                <!-- Card Header -->
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <div>
                        <h4>
                            <i class="fas fa-school me-2"></i>
                            Data SMK Negeri Kota Padang
                        </h4>
                        <p class="mb-0 mt-1" style="font-size: 14px; opacity: 0.9;">
                            Kelola data sekolah menengah kejuruan
                        </p>
                    </div>
                    <a href="tambah-smk.php" class="btn btn-light">
                        <i class="fas fa-plus-circle me-2"></i> Tambah SMK Baru
                    </a>
                </div>

                <!-- Card Body -->
                <div class="card-body p-4">
                    
                    <!-- Search Box -->
                    <div class="search-box">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control" 
                                           id="searchInput" 
                                           placeholder="Cari nama sekolah, NPSN, atau alamat...">
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <span class="text-muted">
                                    Total: <strong id="totalData">
                                        <?php 
                                        $count_query = "SELECT COUNT(*) as total FROM tb_smk";
                                        $count_result = mysqli_query($koneksi, $count_query);
                                        $count_row = mysqli_fetch_assoc($count_result);
                                        echo $count_row['total'];
                                        ?>
                                    </strong> Sekolah
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="dataTable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th style="width: 100px;">NPSN</th>
                                    <th style="min-width: 200px;">Nama Sekolah</th>
                                    <th style="min-width: 250px;">Alamat</th>
                                    <th style="width: 120px;">Kelurahan</th>
                                    <th style="width: 120px;">Kecamatan</th>
                                    <th style="width: 90px;">Kode Pos</th>
                                    <th style="width: 100px;">Latitude</th>
                                    <th style="width: 100px;">Longitude</th>
                                    <th style="width: 120px;">Telepon</th>
                                    <th style="min-width: 150px;">Email</th>
                                    <th style="min-width: 150px;">Website</th>
                                    <th style="min-width: 150px;">Kepala Sekolah</th>
                                    <th style="width: 80px;">Siswa</th>
                                    <th style="width: 80px;">Guru</th>
                                    <th style="width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Query data SMK
                                $query = "SELECT * FROM tb_smk ORDER BY nama_sekolah ASC";
                                $result = mysqli_query($koneksi, $query);
                                
                                // Cek jika query berhasil
                                if (!$result) {
                                    echo "<tr><td colspan='16' class='text-center text-danger'>";
                                    echo "<i class='fas fa-exclamation-triangle me-2'></i>";
                                    echo "Error: " . mysqli_error($koneksi);
                                    echo "</td></tr>";
                                } else if (mysqli_num_rows($result) > 0) {
                                    $no = 1;
                                    while($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><strong><?= htmlspecialchars($row["npsn"] ?? '-') ?></strong></td>
                                    <td>
                                        <div class="text-truncate-custom" title="<?= htmlspecialchars($row["nama_sekolah"]) ?>">
                                            <strong><?= htmlspecialchars($row["nama_sekolah"]) ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-truncate-custom" title="<?= htmlspecialchars($row["alamat"]) ?>">
                                            <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                            <?= htmlspecialchars($row["alamat"]) ?>
                                        </div>
                                    </td>   
                                    <td><?= htmlspecialchars($row["kelurahan"] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($row["kecamatan"] ?? '-') ?></td>
                                    <td class="text-center"><?= htmlspecialchars($row["kode_pos"] ?? '-') ?></td>
                                    <td class="text-end">
                                        <small class="text-muted"><?= htmlspecialchars($row["latitude"] ?? '-') ?></small>
                                    </td>   
                                    <td class="text-end">
                                        <small class="text-muted"><?= htmlspecialchars($row["longitude"] ?? '-') ?></small>
                                    </td>
                                    <td>
                                        <?php if (!empty($row["telepon"])): ?>
                                            <a href="tel:<?= htmlspecialchars($row["telepon"]) ?>" class="text-decoration-none">
                                                <i class="fas fa-phone text-success me-1"></i>
                                                <?= htmlspecialchars($row["telepon"]) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row["email"])): ?>
                                            <a href="mailto:<?= htmlspecialchars($row["email"]) ?>" 
                                               class="text-decoration-none"
                                               title="<?= htmlspecialchars($row["email"]) ?>">
                                                <i class="fas fa-envelope text-primary me-1"></i>
                                                <small><?= htmlspecialchars($row["email"]) ?></small>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row["website"])): ?>
                                            <a href="<?= htmlspecialchars($row["website"]) ?>" 
                                               target="_blank" 
                                               class="text-decoration-none"
                                               title="<?= htmlspecialchars($row["website"]) ?>">
                                                <i class="fas fa-globe text-info me-1"></i>
                                                <small>Buka Website</small>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>   
                                    <td><?= htmlspecialchars($row["nama_kepsek"] ?? '-') ?></td>
                                    <td class="text-end">
                                        <span class="badge bg-primary">
                                            <?= number_format($row["jumlah_siswa"] ?? 0) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-success">
                                            <?= number_format($row["jumlah_guru"] ?? 0) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-action-group">
                                            <a href="../../profil.php?id=<?= $row['id_smk'] ?>" 
                                               class="btn btn-sm btn-info text-white"
                                               title="Lihat Detail"
                                               target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="proses-edit-smk.php?id=<?= $row['id_smk'] ?>" 
                                               class="btn btn-sm btn-warning"
                                               title="Edit Data">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="proses-smk.php?hapus=<?= $row['id_smk'] ?>" 
                                               class="btn btn-sm btn-danger"
                                               title="Hapus Data"
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus SMK <?= htmlspecialchars($row['nama_sekolah']) ?>?\n\nData yang dihapus tidak dapat dikembalikan!')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                                    }
                                } else {
                                    // Tidak ada data
                                ?>
                                <tr>
                                    <td colspan="16">
                                        <div class="empty-state">
                                            <i class="fas fa-school"></i>
                                            <h5>Belum Ada Data SMK</h5>
                                            <p class="text-muted">Silakan tambahkan data SMK dengan klik tombol "Tambah SMK Baru"</p>
                                            <a href="tambah-smk.php" class="btn btn-primary mt-3">
                                                <i class="fas fa-plus-circle me-2"></i> Tambah SMK Pertama
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
</body>
</html>

<!-- JavaScript untuk Search Function -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('dataTable');
    const tbody = table.querySelector('tbody');
    const rows = tbody.querySelectorAll('tr');

    // Search functionality
    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        let visibleRows = 0;

        rows.forEach(row => {
            // Skip empty state row
            if (row.querySelector('.empty-state')) return;

            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
                visibleRows++;
            } else {
                row.style.display = 'none';
            }
        });

        // Show "No results" message if no rows visible
        if (visibleRows === 0 && searchTerm !== '') {
            if (!tbody.querySelector('.no-results')) {
                const noResults = document.createElement('tr');
                noResults.className = 'no-results';
                noResults.innerHTML = `
                    <td colspan="16" class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5>Tidak Ada Hasil</h5>
                        <p class="text-muted">Tidak ditemukan data yang sesuai dengan pencarian "${searchTerm}"</p>
                    </td>
                `;
                tbody.appendChild(noResults);
            }
        } else {
            const noResults = tbody.querySelector('.no-results');
            if (noResults) noResults.remove();
        }
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});
</script>

<?php 
// Include footer
include "../../template/footer.php";
?>