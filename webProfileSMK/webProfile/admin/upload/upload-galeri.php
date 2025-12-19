<!-- ========================================
     FILE 1: admin/galeri/upload-galeri.php
     Halaman Upload Foto Galeri
     ======================================== -->

<?php
session_start();
include "../includes/header.php";
require "../../config/koneksi.php";

// Ambil daftar SMK
$smk_query = "SELECT id_smk, nama_sekolah FROM tb_smk ORDER BY nama_sekolah ASC";
$smk_result = mysqli_query($koneksi, $smk_query);

// Ambil galeri yang sudah ada jika ada parameter id_smk
$existing_photos = [];
$selected_smk = null;
if (isset($_GET['id_smk'])) {
    $id_smk = (int)$_GET['id_smk'];
    
    // Get SMK name
    $smk_name_query = "SELECT nama_sekolah FROM tb_smk WHERE id_smk = $id_smk";
    $smk_name_result = mysqli_query($koneksi, $smk_name_query);
    if ($smk_name_result && mysqli_num_rows($smk_name_result) > 0) {
        $selected_smk = mysqli_fetch_assoc($smk_name_result);
    }
    
    // Get existing photos
    $photo_query = "SELECT * FROM tb_galeri WHERE id_smk = $id_smk ORDER BY urutan ASC";
    $photo_result = mysqli_query($koneksi, $photo_query);
    if ($photo_result) {
        while ($row = mysqli_fetch_assoc($photo_result)) {
            $existing_photos[] = $row;
        }
    }
}

// Handle alert messages
$alert_message = '';
if (isset($_GET['success'])) {
    if ($_GET['success'] == 'upload') {
        $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> Foto berhasil diupload!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }
}
if (isset($_GET['error'])) {
    $error_msg = htmlspecialchars($_GET['error']);
    $alert_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> Error: ' . $error_msg . '
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>';
}
?>

<style>
    .upload-container {
        max-width: 1200px;
        margin: 30px auto;
    }

    .upload-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .card-header-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        text-align: center;
    }

    .card-header-custom h2 {
        margin: 0 0 10px 0;
        font-weight: 700;
    }

    .file-drop-area {
        border: 3px dashed #667eea;
        border-radius: 16px;
        padding: 60px 20px;
        text-align: center;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: 30px;
    }

    .file-drop-area:hover {
        background: #e9ecef;
        border-color: #764ba2;
        transform: scale(1.02);
    }

    .file-drop-area.highlight {
        background: #e7f3ff;
        border-color: #0d6efd;
    }

    .file-drop-area i {
        font-size: 64px;
        color: #667eea;
        margin-bottom: 20px;
    }

    .preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }

    .preview-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }

    .preview-item:hover {
        transform: translateY(-5px);
    }

    .preview-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .preview-item .remove-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(220, 53, 69, 0.95);
        color: white;
        border: none;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .preview-item .remove-btn:hover {
        background: #dc3545;
        transform: scale(1.1);
    }

    .existing-photo-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        background: white;
        transition: transform 0.3s;
    }

    .existing-photo-item:hover {
        transform: translateY(-5px);
    }

    .existing-photo-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .existing-photo-item .photo-info {
        padding: 15px;
        background: white;
    }

    .existing-photo-item .delete-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(220, 53, 69, 0.95);
        color: white;
        border: none;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .existing-photo-item .delete-btn:hover {
        background: #dc3545;
        transform: scale(1.1);
    }

    .btn-upload {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 15px 50px;
        font-weight: 600;
        border-radius: 12px;
        transition: all 0.3s;
    }

    .btn-upload:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }

    .progress {
        height: 30px;
        border-radius: 15px;
        margin-top: 20px;
        display: none;
    }

    .progress-bar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        font-weight: 600;
    }

    .info-badge {
        background: #e7f3ff;
        border-left: 4px solid #2196F3;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
</style>

<div class="upload-container">
    
    <!-- Alert Messages -->
    <?php echo $alert_message; ?>

    <!-- Upload Form Card -->
    <div class="upload-card">
        <div class="card-header-custom">
            <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i>
            <h2>Upload Foto Galeri SMK</h2>
            <p class="mb-0" style="opacity: 0.9;">
                Pilih sekolah dan upload foto dari galeri HP atau komputer
            </p>
        </div>

        <div class="card-body p-4">
            
            <form id="uploadForm" enctype="multipart/form-data">
                
                <!-- Info Badge -->
                <div class="info-badge">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Informasi:</strong> 
                    Format file: JPG, PNG, JPEG | Ukuran maksimal: 5MB per foto | 
                    Upload maksimal 10 foto sekaligus
                </div>

                <!-- Pilih SMK -->
                <div class="mb-4">
                    <label for="id_smk" class="form-label fw-bold">
                        <i class="fas fa-school me-2"></i>Pilih Sekolah
                    </label>
                    <select class="form-select form-select-lg" id="id_smk" name="id_smk" required>
                        <option value="">-- Pilih SMK --</option>
                        <?php 
                        mysqli_data_seek($smk_result, 0); // Reset pointer
                        while ($smk = mysqli_fetch_assoc($smk_result)): 
                        ?>
                            <option value="<?php echo $smk['id_smk']; ?>" 
                                    <?php echo (isset($_GET['id_smk']) && $_GET['id_smk'] == $smk['id_smk']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($smk['nama_sekolah']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- File Upload Area -->
                <div class="file-drop-area" id="fileDropArea">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <h4>Klik atau Drag & Drop Foto Disini</h4>
                    <p class="text-muted mb-0">Pilih dari galeri HP, ambil foto baru, atau drag file dari komputer</p>
                    <p class="text-muted mt-2">
                        <small><i class="fas fa-check-circle text-success me-1"></i> JPG, PNG, JPEG</small> | 
                        <small><i class="fas fa-check-circle text-success me-1"></i> Max 5MB</small>
                    </p>
                    <input type="file" 
                           id="fileInput" 
                           name="photos[]" 
                           accept="image/jpeg,image/jpg,image/png" 
                           multiple 
                           capture="environment"
                           style="display: none;">
                </div>

                <!-- Preview Container -->
                <div id="previewContainer" class="preview-grid"></div>

                <!-- Progress Bar -->
                <div class="progress" id="uploadProgress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                         role="progressbar" 
                         style="width: 0%">0%</div>
                </div>

                <!-- Keterangan -->
                <div class="mb-4">
                    <label for="keterangan" class="form-label fw-bold">
                        <i class="fas fa-comment me-2"></i>Keterangan Foto (Opsional)
                    </label>
                    <input type="text" 
                           class="form-control form-control-lg" 
                           id="keterangan" 
                           name="keterangan" 
                           placeholder="Contoh: Gedung Utama, Lab Komputer, Lapangan Olahraga">
                    <small class="text-muted">Keterangan ini akan ditampilkan di carousel foto</small>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-upload btn-lg" id="btnSubmit">
                        <i class="fas fa-upload me-2"></i>Upload Foto Sekarang
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- Existing Photos -->
    <?php if (!empty($existing_photos) && $selected_smk): ?>
    <div class="upload-card">
        <div class="card-body p-4">
            <h4 class="mb-4">
                <i class="fas fa-images me-2"></i>
                Foto yang Sudah Ada - <?php echo htmlspecialchars($selected_smk['nama_sekolah']); ?>
            </h4>
            
            <div class="preview-grid">
                <?php foreach ($existing_photos as $photo): ?>
                <div class="existing-photo-item" data-id="<?php echo $photo['id_galeri']; ?>">
                    <img src="../../<?php echo htmlspecialchars($photo['foto']); ?>" 
                         alt="<?php echo htmlspecialchars($photo['keterangan']); ?>"
                         onerror="this.src='../../assets/images/placeholder.jpg'">
                    <div class="photo-info">
                        <small class="text-muted d-block">
                            <i class="fas fa-image me-1"></i>
                            <?php echo htmlspecialchars($photo['keterangan'] ?: 'Foto Sekolah'); ?>
                        </small>
                        <small class="text-muted">
                            <i class="fas fa-sort me-1"></i>Urutan: <?php echo $photo['urutan']; ?>
                        </small>
                    </div>
                    <button class="delete-btn" 
                            onclick="deletePhoto(<?php echo $photo['id_galeri']; ?>)"
                            title="Hapus foto">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<script>
    const fileDropArea = document.getElementById('fileDropArea');
    const fileInput = document.getElementById('fileInput');
    const previewContainer = document.getElementById('previewContainer');
    const uploadForm = document.getElementById('uploadForm');
    const btnSubmit = document.getElementById('btnSubmit');
    const uploadProgress = document.getElementById('uploadProgress');
    const progressBar = uploadProgress.querySelector('.progress-bar');
    const selectSMK = document.getElementById('id_smk');

    let selectedFiles = [];

    // Click to upload
    fileDropArea.addEventListener('click', () => fileInput.click());

    // Drag & Drop handlers
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        fileDropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        fileDropArea.addEventListener(eventName, () => {
            fileDropArea.classList.add('highlight');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        fileDropArea.addEventListener(eventName, () => {
            fileDropArea.classList.remove('highlight');
        });
    });

    fileDropArea.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    });

    fileInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });

    function handleFiles(files) {
        // Validate max 10 files
        if (files.length > 10) {
            alert('Maksimal 10 foto sekaligus!');
            return;
        }

        selectedFiles = [...files];
        previewFiles(selectedFiles);
    }

    function previewFiles(files) {
        previewContainer.innerHTML = '';
        
        files.forEach((file, index) => {
            // Validate file type
            if (!file.type.match('image/jpeg') && !file.type.match('image/jpg') && !file.type.match('image/png')) {
                alert(`File ${file.name} bukan format gambar yang valid!`);
                return;
            }

            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert(`File ${file.name} terlalu besar! Maksimal 5MB.`);
                return;
            }

            const reader = new FileReader();
            
            reader.onload = (e) => {
                const div = document.createElement('div');
                div.className = 'preview-item';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="remove-btn" onclick="removeFile(${index})" title="Hapus">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                previewContainer.appendChild(div);
            };
            
            reader.readAsDataURL(file);
        });
    }

    function removeFile(index) {
        selectedFiles.splice(index, 1);
        previewFiles(selectedFiles);
    }

    // Form Submit dengan AJAX
    uploadForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const id_smk = selectSMK.value;
        const keterangan = document.getElementById('keterangan').value;

        if (!id_smk) {
            alert('Pilih sekolah terlebih dahulu!');
            selectSMK.focus();
            return;
        }

        if (selectedFiles.length === 0) {
            alert('Pilih minimal 1 foto!');
            return;
        }

        // Siapkan FormData
        const formData = new FormData();
        formData.append('id_smk', id_smk);
        formData.append('keterangan', keterangan);
        
        selectedFiles.forEach((file) => {
            formData.append('photos[]', file);
        });

        // Disable button & show progress
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengupload...';
        uploadProgress.style.display = 'block';

        try {
            const xhr = new XMLHttpRequest();

            // Progress handler
            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    progressBar.style.width = percentComplete + '%';
                    progressBar.textContent = Math.round(percentComplete) + '%';
                }
            });

            // Completion handler
            xhr.addEventListener('load', function() {
                if (xhr.status === 200) {
                    try {
                        const result = JSON.parse(xhr.responseText);
                        if (result.success) {
                            window.location.href = `upload-galeri.php?id_smk=${id_smk}&success=upload`;
                        } else {
                            alert('Error: ' + result.message);
                            resetForm();
                        }
                    } catch (e) {
                        alert('Error parsing response');
                        resetForm();
                    }
                } else {
                    alert('Upload failed: HTTP ' + xhr.status);
                    resetForm();
                }
            });

            xhr.addEventListener('error', function() {
                alert('Network error occurred');
                resetForm();
            });

            xhr.open('POST', 'proses-upload.php');
            xhr.send(formData);

        } catch (error) {
            alert('Error: ' + error.message);
            resetForm();
        }
    });

    function resetForm() {
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = '<i class="fas fa-upload me-2"></i>Upload Foto Sekarang';
        uploadProgress.style.display = 'none';
        progressBar.style.width = '0%';
        progressBar.textContent = '0%';
    }

    async function deletePhoto(id) {
        if (!confirm('Yakin ingin menghapus foto ini?')) return;

        try {
            const response = await fetch('hapus-foto.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id_galeri: id })
            });

            const result = await response.json();

            if (result.success) {
                document.querySelector(`[data-id="${id}"]`).remove();
                alert('Foto berhasil dihapus');
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            alert('Error: ' + error.message);
        }
    }

    // Auto-select school on change
    selectSMK.addEventListener('change', function() {
        if (this.value) {
            window.location.href = `upload-galeri.php?id_smk=${this.value}`;
        }
    });
</script>

<?php include "../../template/footer.php"; ?>