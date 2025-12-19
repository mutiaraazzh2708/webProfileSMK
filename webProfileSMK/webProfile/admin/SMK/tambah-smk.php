<?php  

include "../includes/header.php";
require "../../config/koneksi.php";
?>


<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Input Data SMK </h4>
                </div>
                <div class="card-body">
                    <a href="data-smk.php" class="btn btn-primary mb-4">
                        <i class="bi bi-arrow-left"></i> Data SMK
                    </a>

                    <form action="proses-smk.php" method="post">
                        <div class="mb-3">
                            <label for="nim" class="form-label">Nama Sekolah</label>
                            <input type="text" class="form-control" name="nama" id="nama" required>
                        </div>

                        <div class="mb-3">

                            <label for="npsn" class="form-label">NPSN</label>
                            <input type="text" class="form-control" name="npsn" id="npsn" required>
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" id="alamat" rows="4" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="kelurahan" class="form-label">kelurahan</label>
                            <input type="text" class="form-control" name="kelurahan" id="kelurahan" required>
                        </div>

                        <div class="mb-3">
                            <label for="kelurahan" class="form-label">kecamatan</label>
                            <input type="text" class="form-control" name="kecamatan" id="kecamatan" required>
                        </div>

                        <div class="mb-3">
                            <label for="kode_pos" class="form-label">kode pos</label>
                            <input type="text" class="form-control" name="kode_pos" id="kode_pos" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="latitude" class="form-label">latitude</label>
                            <input type="text" class="form-control" name="latitude" id="latitude" required>
                        </div>

                        <div class="mb-3">
                            <label for="longitude" class="form-label">longitude</label>
                            <input type="text" class="form-control" name="longitude" id="longitude" required>
                        </div>

                        <div class="mb-3">
                            <label for="telepon" class="form-label">telepon</label>
                            <input type="text" class="form-control" name="telepon" id="telepon" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">email</label>
                            <input type="email" class="form-control" name="email" id="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="website" class="form-label">website</label>
                            <input type="text" class="form-control" name="website" id="website" required>
                        </div>

                         <div class="mb-3">
                            <label for="kepsek" class="form-label">Kepala Sekolah</label>
                            <input type="text" class="form-control" name="kepsek" id="kepsek" required>
                        </div>

                        <div class="mb-3">
                            <label for="siswa" class="form-label">Jumlah siswa</label>
                            <input type="text" class="form-control" name="siswa" id="siswa" required>
                        </div>

                        <div class="mb-3">
                            <label for="guru" class="form-label">Jumlah Guru</label>
                            <input type="text" class="form-control" name="guru" id="guru" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button  accesskey="submit" name="create" class="btn btn-primary">   
                                Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>