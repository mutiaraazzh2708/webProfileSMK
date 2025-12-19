<?php
include "../includes/header.php";
require "../../config/koneksi.php";
?>

<style>
    .form-container {
        max-width: 900px;
        margin: 30px auto;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .form-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        text-align: center;
    }

    .form-header h3 {
        margin: 0;
        font-weight: 600;
    }

    .form-body {
        padding: 40px;
    }

    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 12px 40px;
        font-weight: 600;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }

    .info-box {
        background: #e7f3ff;
        border-left: 4px solid #2196F3;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .kode-helper {
        font-size: 12px;
        color: #6c757d;
        margin-top: 5px;
    }
</style>

<div class="form-container">
    <div class="form-header">
        <i class="fas fa-graduation-cap fa-3x mb-3"></i>
        <h3>Tambah Program Keahlian Baru</h3>
        <p class="mb-0 mt-2" style="opacity: 0.9;">Lengkapi data program keahlian SMK</p>
    </div>

    <div class="form-body">
        
        <!-- Info Box -->
        <div class="info-box">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Informasi:</strong> Pastikan semua data yang diisi sudah benar. 
            Data ini akan ditampilkan di halaman profil sekolah.
        </div>

        <!-- Form -->
        <form action="proses-kejuruan.php" method="POST" id="formKejuruan">
            
            <!-- Pilih SMK -->
            <div class="mb-4">
                <label for="id_smk" class="form-label">
                    <i class="fas fa-school me-2"></i>Pilih SMK <span class="text-danger">*</span>
                </label>
                <select class="form-select form-select-lg" id="id_smk" name="id_smk" required>
                    <option value="">-- Pilih Sekolah --</option>
                    <?php
                    $smk_query = "SELECT id_smk, nama_sekolah FROM tb_smk ORDER BY nama_sekolah ASC";
                    $smk_result = mysqli_query($koneksi, $smk_query);
                    while ($smk = mysqli_fetch_assoc($smk_result)) {
                        echo "<option value='{$smk['id_smk']}'>{$smk['nama_sekolah']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="row">
                <!-- Kode Kejuruan -->
                <div class="col-md-6 mb-4">
                    <label for="kode_kejuruan" class="form-label">
                        <i class="fas fa-code me-2"></i>Kode Kejuruan <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control form-control-lg" 
                           id="kode_kejuruan" 
                           name="kode_kejuruan" 
                           placeholder="Contoh: RPL, TKJ, MM" 
                           maxlength="10"
                           required>
                    <div class="kode-helper">
                        <strong>Contoh kode:</strong> RPL (Rekayasa Perangkat Lunak), 
                        TKJ (Teknik Komputer Jaringan), MM (Multimedia), 
                        DKV (Desain Komunikasi Visual), AKL (Akuntansi), dll.
                    </div>
                </div>

                <!-- Nama Kejuruan -->
                <div class="col-md-6 mb-4">
                    <label for="nama_kejuruan" class="form-label">
                        <i class="fas fa-tag me-2"></i>Nama Kejuruan <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control form-control-lg" 
                           id="nama_kejuruan" 
                           name="nama_kejuruan" 
                           placeholder="Contoh: Rekayasa Perangkat Lunak" 
                           required>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="mb-4">
                <label for="deskripsi" class="form-label">
                    <i class="fas fa-align-left me-2"></i>Deskripsi Program Keahlian
                </label>
                <textarea class="form-control" 
                          id="deskripsi" 
                          name="deskripsi" 
                          rows="4" 
                          placeholder="Jelaskan tentang program keahlian ini, kompetensi yang dipelajari, dan prospek kerja..."></textarea>
                <small class="text-muted">Opsional - Jelaskan tentang program keahlian ini</small>
            </div>

           
            <!-- Buttons -->
            <div class="d-flex gap-3 justify-content-end mt-4">
                <a href="data-kejuruan.php" class="btn btn-secondary btn-lg">
                    <i class="fas fa-times me-2"></i>Batal
                </a>
                <button type="submit" name="tambah" class="btn btn-primary btn-submit btn-lg">
                    <i class="fas fa-save me-2"></i>Simpan Data
                </button>
            </div>

        </form>
    </div>
</div>

<!-- Validation Script -->
<script>
document.getElementById('formKejuruan').addEventListener('submit', function(e) {
    const idSmk = document.getElementById('id_smk').value;
    const kodeKejuruan = document.getElementById('kode_kejuruan').value;
    const namaKejuruan = document.getElementById('nama_kejuruan').value;

    if (!idSmk) {
        e.preventDefault();
        alert('Pilih SMK terlebih dahulu!');
        document.getElementById('id_smk').focus();
        return false;
    }

    if (!kodeKejuruan) {
        e.preventDefault();
        alert('Kode kejuruan harus diisi!');
        document.getElementById('kode_kejuruan').focus();
        return false;
    }

    if (!namaKejuruan) {
        e.preventDefault();
        alert('Nama kejuruan harus diisi!');
        document.getElementById('nama_kejuruan').focus();
        return false;
    }

    return true;
});

// Auto uppercase kode kejuruan
document.getElementById('kode_kejuruan').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});
</script>

<?php include "../../template/footer.php"; ?>

