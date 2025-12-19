
<?php
session_start();
include "../includes/header.php";
require "../../config/koneksi.php";

// Handle alert messages
$alert_message = '';
if (isset($_GET['success'])) {
    $messages = [
        'delete' => 'Foto berhasil dihapus!',
        'delete_multiple' => 'Beberapa foto berhasil dihapus!',
        'update' => 'Urutan foto berhasil diperbarui!'
    ];
    $msg = $messages[$_GET['success']] ?? 'Operasi berhasil!';
    $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>' . $msg . '
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>';
}
if (isset($_GET['error'])) {
    $error_msg = htmlspecialchars($_GET['error']);
    $alert_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>Error: ' . $error_msg . '
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>';
}

// Ambil filter SMK
$id_smk_filter = isset($_GET['id_smk']) ? (int)$_GET['id_smk'] : 0;

// Query galeri dengan join SMK
if ($id_smk_filter > 0) {
    $query = "SELECT g.*, s.nama_sekolah 
              FROM tb_galeri g 
              INNER JOIN tb_smk s ON g.id_smk = s.id_smk 
              WHERE g.id_smk = $id_smk_filter
              ORDER BY g.urutan ASC, g.created_at DESC";
} else {
    $query = "SELECT g.*, s.nama_sekolah 
              FROM tb_galeri g 
              INNER JOIN tb_smk s ON g.id_smk = s.id_smk 
              ORDER BY s.nama_sekolah ASC, g.urutan ASC";
}

$result = mysqli_query($koneksi, $query);

// Ambil semua SMK untuk filter
$smk_query = "SELECT id_smk, nama_sekolah FROM tb_smk ORDER BY nama_sekolah ASC";
$smk_result = mysqli_query($koneksi, $smk_query);

// Hitung total foto
$total_query = "SELECT COUNT(*) as total FROM tb_galeri" . ($id_smk_filter > 0 ? " WHERE id_smk = $id_smk_filter" : "");
$total_result = mysqli_query($koneksi, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_photos = $total_row['total'];
?>

<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px;
        border-radius: 16px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .page-header h2 {
        margin: 0 0 10px 0;
        font-weight: 700;
    }

    .page-header p {
        margin: 0;
        opacity: 0.9;
    }

    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .photo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
    }

    .photo-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s;
        position: relative;
    }

    .photo-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .photo-card.selected {
        border: 3px solid #667eea;
    }

    .photo-card img {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }

    .photo-info {
        padding: 20px;
    }

    .photo-title {
        font-weight: 600;
        color: #333;
        margin: 0 0 8px 0;
        font-size: 1rem;
    }

    .photo-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 15px;
        font-size: 13px;
    }

    .meta-badge {
        background: #f8f9fa;
        padding: 5px 10px;
        border-radius: 6px;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .photo-actions {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        flex: 1;
        padding: 8px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .btn-view {
        background: #0d6efd;
        color: white;
    }

    .btn-view:hover {
        background: #0b5ed7;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
    }

    .btn-delete:hover {
        background: #bb2d3b;
    }

    .checkbox-select {
        position: absolute;
        top: 15px;
        left: 15px;
        width: 30px;
        height: 30px;
        cursor: pointer;
        z-index: 10;
    }

    .bulk-actions {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        display: none;
    }

    .bulk-actions.active {
        display: block;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .empty-state i {
        font-size: 80px;
        color: #dee2e6;
        margin-bottom: 20px;
    }

    .stats-bar {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .stat-item {
        background: white;
        padding: 15px 25px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .stat-icon.primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .stat-icon.success {
        background: linear-gradient(135deg, #11998e, #38ef7d);
        color: white;
    }

    .stat-content h4 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 700;
        color: #333;
    }

    .stat-content p {
        margin: 0;
        color: #6c757d;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .photo-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        }
    }
</style>

<div class="container-fluid mt-4">
    
    <!-- Alert Messages -->
    <?php echo $alert_message; ?>

    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2><i class="fas fa-images me-2"></i>Kelola Galeri Foto</h2>
                <p>Kelola, edit, dan hapus foto galeri SMK</p>
            </div>
            <div class="d-flex gap-2">
                <a href="upload-galeri.php" class="btn btn-light btn-lg">
                    <i class="fas fa-plus-circle me-2"></i>Upload Foto Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="stats-bar">
        <div class="stat-item">
            <div class="stat-icon primary">
                <i class="fas fa-images"></i>
            </div>
            <div class="stat-content">
                <h4><?php echo $total_photos; ?></h4>
                <p>Total Foto</p>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon success">
                <i class="fas fa-school"></i>
            </div>
            <div class="stat-content">
                <h4>
                    <?php
                    $smk_count_query = "SELECT COUNT(DISTINCT id_smk) as total FROM tb_galeri";
                    $smk_count_result = mysqli_query($koneksi, $smk_count_query);
                    $smk_count = mysqli_fetch_assoc($smk_count_result)['total'];
                    echo $smk_count;
                    ?>
                </h4>
                <p>SMK dengan Galeri</p>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="filter-card">
        <div class="row align-items-end">
            <div class="col-md-6 mb-3 mb-md-0">
                <label class="form-label fw-bold">
                    <i class="fas fa-filter me-2"></i>Filter Berdasarkan SMK
                </label>
                <select class="form-select form-select-lg" id="filterSMK" onchange="filterGaleri(this.value)">
                    <option value="0" <?php echo $id_smk_filter == 0 ? 'selected' : ''; ?>>
                        Semua SMK (<?php echo $total_photos; ?> foto)
                    </option>
                    <?php while ($smk = mysqli_fetch_assoc($smk_result)): 
                        // Count photos for this SMK
                        $count_q = "SELECT COUNT(*) as cnt FROM tb_galeri WHERE id_smk = " . $smk['id_smk'];
                        $count_r = mysqli_query($koneksi, $count_q);
                        $count = mysqli_fetch_assoc($count_r)['cnt'];
                    ?>
                        <option value="<?php echo $smk['id_smk']; ?>" 
                                <?php echo $id_smk_filter == $smk['id_smk'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($smk['nama_sekolah']); ?> (<?php echo $count; ?> foto)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-6">
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary flex-fill" onclick="selectAll()">
                        <i class="fas fa-check-square me-2"></i>Pilih Semua
                    </button>
                    <button class="btn btn-outline-secondary flex-fill" onclick="deselectAll()">
                        <i class="fas fa-square me-2"></i>Batal Pilih
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-actions" id="bulkActions">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <strong><span id="selectedCount">0</span> foto dipilih</strong>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-danger" onclick="deleteSelected()">
                    <i class="fas fa-trash me-2"></i>Hapus yang Dipilih
                </button>
            </div>
        </div>
    </div>

    <!-- Photo Grid -->
    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        
        <div class="photo-grid">
            <?php while ($photo = mysqli_fetch_assoc($result)): ?>
                <div class="photo-card" data-photo-id="<?php echo $photo['id_galeri']; ?>">
                    
                    <!-- Checkbox -->
                    <input type="checkbox" 
                           class="checkbox-select" 
                           value="<?php echo $photo['id_galeri']; ?>"
                           onchange="updateBulkActions()">

                    <!-- Photo Image -->
                    <img src="../../<?php echo htmlspecialchars($photo['foto']); ?>" 
                         alt="<?php echo htmlspecialchars($photo['keterangan']); ?>"
                         onerror="this.src='https://via.placeholder.com/300x220?text=Foto+Tidak+Ditemukan'">

                    <!-- Photo Info -->
                    <div class="photo-info">
                        <h6 class="photo-title">
                            <?php echo htmlspecialchars($photo['keterangan'] ?: 'Foto Sekolah'); ?>
                        </h6>
                        
                        <div class="photo-meta">
                            <span class="meta-badge">
                                <i class="fas fa-school"></i>
                                <?php echo htmlspecialchars($photo['nama_sekolah']); ?>
                            </span>
                            <span class="meta-badge">
                                <i class="fas fa-sort"></i>
                                Urutan: <?php echo $photo['urutan']; ?>
                            </span>
                            <span class="meta-badge">
                                <i class="fas fa-calendar"></i>
                                <?php echo date('d/m/Y', strtotime($photo['created_at'])); ?>
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="photo-actions">
                            <button class="btn-action btn-view" 
                                    onclick="viewPhoto('../../<?php echo htmlspecialchars($photo['foto']); ?>')">
                                <i class="fas fa-eye"></i> Lihat
                            </button>
                            <button class="btn-action btn-delete" 
                                    onclick="deleteSinglePhoto(<?php echo $photo['id_galeri']; ?>, '<?php echo htmlspecialchars(addslashes($photo['keterangan'] ?: 'Foto ini')); ?>')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>

                </div>
            <?php endwhile; ?>
        </div>

    <?php else: ?>
        
        <!-- Empty State -->
        <div class="empty-state">
            <i class="fas fa-images"></i>
            <h4>Belum Ada Foto</h4>
            <p class="text-muted mb-4">
                <?php if ($id_smk_filter > 0): ?>
                    Belum ada foto untuk SMK yang dipilih
                <?php else: ?>
                    Belum ada foto galeri yang diupload
                <?php endif; ?>
            </p>
            <a href="upload-galeri.php" class="btn btn-primary btn-lg">
                <i class="fas fa-plus-circle me-2"></i>Upload Foto Pertama
            </a>
        </div>

    <?php endif; ?>

</div>

<!-- Modal View Photo -->
<div class="modal fade" id="viewPhotoModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Preview" style="max-width: 100%; height: auto;">
            </div>
        </div>
    </div>
</div>

<script>
    // Filter galeri
    function filterGaleri(id_smk) {
        if (id_smk == 0) {
            window.location.href = 'kelola-galeri.php';
        } else {
            window.location.href = 'kelola-galeri.php?id_smk=' + id_smk;
        }
    }

    // View photo in modal
    function viewPhoto(photoUrl) {
        document.getElementById('modalImage').src = photoUrl;
        const modal = new bootstrap.Modal(document.getElementById('viewPhotoModal'));
        modal.show();
    }

    // Delete single photo
    function deleteSinglePhoto(id, keterangan) {
        if (!confirm(`Yakin ingin menghapus foto "${keterangan}"?\n\nFoto yang dihapus tidak dapat dikembalikan!`)) {
            return;
        }

        // Show loading
        const card = document.querySelector(`[data-photo-id="${id}"]`);
        card.style.opacity = '0.5';
        card.style.pointerEvents = 'none';

        fetch('hapus-foto.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_galeri: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Fade out and remove
                card.style.transition = 'all 0.3s';
                card.style.transform = 'scale(0)';
                setTimeout(() => {
                    card.remove();
                    // Reload if no more photos
                    if (document.querySelectorAll('.photo-card').length === 0) {
                        window.location.reload();
                    }
                }, 300);
                
                showToast('success', 'Foto berhasil dihapus!');
            } else {
                card.style.opacity = '1';
                card.style.pointerEvents = 'auto';
                showToast('error', 'Error: ' + data.message);
            }
        })
        .catch(error => {
            card.style.opacity = '1';
            card.style.pointerEvents = 'auto';
            showToast('error', 'Terjadi kesalahan: ' + error.message);
        });
    }

    // Select/deselect all
    function selectAll() {
        document.querySelectorAll('.checkbox-select').forEach(cb => {
            cb.checked = true;
            cb.closest('.photo-card').classList.add('selected');
        });
        updateBulkActions();
    }

    function deselectAll() {
        document.querySelectorAll('.checkbox-select').forEach(cb => {
            cb.checked = false;
            cb.closest('.photo-card').classList.remove('selected');
        });
        updateBulkActions();
    }

    // Update bulk actions visibility
    function updateBulkActions() {
        const checkboxes = document.querySelectorAll('.checkbox-select:checked');
        const bulkActions = document.getElementById('bulkActions');
        const selectedCount = document.getElementById('selectedCount');
        
        selectedCount.textContent = checkboxes.length;
        
        if (checkboxes.length > 0) {
            bulkActions.classList.add('active');
        } else {
            bulkActions.classList.remove('active');
        }

        // Update card selection style
        document.querySelectorAll('.checkbox-select').forEach(cb => {
            if (cb.checked) {
                cb.closest('.photo-card').classList.add('selected');
            } else {
                cb.closest('.photo-card').classList.remove('selected');
            }
        });
    }

    // Delete selected photos
    function deleteSelected() {
        const checkboxes = document.querySelectorAll('.checkbox-select:checked');
        const ids = Array.from(checkboxes).map(cb => cb.value);
        
        if (ids.length === 0) {
            alert('Pilih foto yang ingin dihapus terlebih dahulu!');
            return;
        }

        if (!confirm(`Yakin ingin menghapus ${ids.length} foto?\n\nFoto yang dihapus tidak dapat dikembalikan!`)) {
            return;
        }

        // Delete each photo
        let deleted = 0;
        ids.forEach(id => {
            fetch('hapus-foto.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id_galeri: parseInt(id) })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const card = document.querySelector(`[data-photo-id="${id}"]`);
                    if (card) card.remove();
                    deleted++;
                    
                    if (deleted === ids.length) {
                        window.location.href = 'kelola-galeri.php?success=delete_multiple';
                    }
                }
            });
        });
    }

    // Toast notification
    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed top-0 end-0 m-3`;
        toast.style.zIndex = '9999';
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle me-2"></i>
            ${message}
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateBulkActions();
    });
</script>

<?php include "../../template/footer.php"; ?>