
        
        <!-- ========================================
     FILE 1: admin/kejuruan/data-kejuruan.php
     Halaman List Data Kejuruan
     ======================================== -->

<?php
include "../includes/header.php";
require "../../config/koneksi.php";

// Ambil ID SMK dari parameter (opsional)
$id_smk_filter = isset($_GET['id_smk']) ? (int)$_GET['id_smk'] : 0;
?>

<style>
    .card-kejuruan {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .card-header-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
    }

    .kejuruan-card {
        border-radius: 12px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s;
        height: 100%;
    }

    .kejuruan-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .kejuruan-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 15px;
    }

    .badge-custom {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 12px;
    }
</style>

<div class="container-fluid mt-4">
    <div class="card-kejuruan">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <div>
                <h4><i class="fas fa-graduation-cap me-2"></i>Data Kejuruan SMK</h4>
                <p class="mb-0 mt-1" style="font-size: 14px; opacity: 0.9;">
                    Kelola program keahlian yang tersedia di setiap SMK
                </p>
            </div>
            <a href="tambah-kejuruan.php" class="btn btn-light">
                <i class="fas fa-plus-circle me-2"></i> Tambah Kejuruan Baru
            </a>
        </div>

        <div class="card-body p-4">
            <!-- Filter SMK -->
            <div class="mb-4">
                <label class="form-label"><i class="fas fa-filter me-2"></i>Filter Berdasarkan SMK</label>
                <select class="form-select" id="filterSMK" onchange="filterBySMK(this.value)">
                    <option value="0">Semua SMK</option>
                    <?php
                    $smk_query = "SELECT id_smk, nama_sekolah FROM tb_smk ORDER BY nama_sekolah ASC";
                    $smk_result = mysqli_query($koneksi, $smk_query);
                    while ($smk = mysqli_fetch_assoc($smk_result)) {
                        $selected = ($smk['id_smk'] == $id_smk_filter) ? 'selected' : '';
                        echo "<option value='{$smk['id_smk']}' {$selected}>{$smk['nama_sekolah']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Grid Kejuruan -->
            <div class="row g-4">
                <?php
                // Query kejuruan dengan join SMK
                if ($id_smk_filter > 0) {
                    $query = "SELECT k.*, s.nama_sekolah 
                             FROM tb_kejuruan k 
                             INNER JOIN tb_smk s ON k.id_smk = s.id_smk 
                             WHERE k.id_smk = $id_smk_filter
                             ORDER BY k.nama_kejuruan ASC";
                } else {
                    $query = "SELECT k.*, s.nama_sekolah 
                             FROM tb_kejuruan k 
                             INNER JOIN tb_smk s ON k.id_smk = s.id_smk 
                             ORDER BY s.nama_sekolah ASC, k.nama_kejuruan ASC";
                }
                
                $result = mysqli_query($koneksi, $query);
                
                if ($result && mysqli_num_rows($result) > 0) {
                    $icons = [
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

                    $colors = [
                        'linear-gradient(135deg, #667eea, #764ba2)',
                        'linear-gradient(135deg, #f093fb, #f5576c)',
                        'linear-gradient(135deg, #4facfe, #00f2fe)',
                        'linear-gradient(135deg, #43e97b, #38f9d7)',
                        'linear-gradient(135deg, #fa709a, #fee140)',
                        'linear-gradient(135deg, #30cfd0, #330867)',
                    ];

                    $index = 0;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $kode = strtoupper(substr($row['kode_kejuruan'] ?? '', 0, 5));
                        $icon = $icons[$kode] ?? $icons['default'];
                        $color = $colors[$index % count($colors)];
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card kejuruan-card">
                        <div class="card-body">
                            <div class="kejuruan-icon" style="background: <?= $color ?>; color: white;">
                                <i class="fas <?= $icon ?>"></i>
                            </div>
                            <h5 class="card-title"><?= htmlspecialchars($row['nama_kejuruan']) ?></h5>
                            <p class="text-muted mb-2">
                                <i class="fas fa-school me-1"></i>
                                <?= htmlspecialchars($row['nama_sekolah']) ?>
                            </p>
                            <p class="card-text text-muted small mb-3">
                                <?= htmlspecialchars(substr($row['deskripsi'] ?? 'Tidak ada deskripsi', 0, 100)) ?>...
                            </p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge-custom bg-primary">
                                    <i class="fas fa-users me-1"></i>
                                    <?= number_format($row['jumlah_siswa'] ?? 0) ?> Siswa
                                </span>
                                <span class="badge-custom bg-success">
                                    <i class="fas fa-door-open me-1"></i>
                                    <?= $row['jumlah_rombel'] ?? 0 ?> Rombel
                                </span>
                            </div>
                           
                        </div>
                    </div>
                </div>
                <?php
                        $index++;
                    }
                } else {
                ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-graduation-cap fa-5x text-muted mb-3"></i>
                        <h5>Belum Ada Data Kejuruan</h5>
                        <p class="text-muted">Silakan tambahkan program keahlian untuk SMK</p>
                        <a href="tambah-kejuruan.php" class="btn btn-primary mt-3">
                            <i class="fas fa-plus-circle me-2"></i> Tambah Kejuruan
                        </a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
function filterBySMK(id_smk) {
    window.location.href = `data-kejuruan.php?id_smk=${id_smk}`;
}
</script>

<?php include "../../template/footer.php"; ?>


