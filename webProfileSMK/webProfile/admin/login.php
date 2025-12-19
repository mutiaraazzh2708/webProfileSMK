<?php
require '../config/koneksi.php';
session_start();

//Proses login

if (isset($_POST['login'])){
    $username = $_POST['username'];
    $pass = md5($_POST['password']);

    

    $cek_login = $koneksi -> query("SELECT * from tb_admin where username = '$username' and password = '$pass'");

    if ($cek_login->num_rows == 1){
        // login berhasil
        $_SESSION['login'] = true;
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        $error = "Username atau password tidak valid";
    }
}

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Sistem Informasi Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        
      body {
       background: linear-gradient(135deg, #8395e6ff 0%, #764ba2 100%); 
        min-height: 100vh;
        display: flex;
        align-items: center;
      }
      .login-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      }
    </style>
  </head>
  <body>
    
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-5">
          <div class="login-card p-5">
            <div class="text-center mb-4">
              <h2 class="fw-bold text-primary">ESEMKA</h2>
              <p class="text-muted">Silakan login untuk melanjutkan</p>
            </div>

            <?php if (isset($error)): ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> <?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>

            <form action="" method="POST">
              <div class="mb-3">
                <label for="username" class="form-label fw-semibold">Username</label>
                <input type="text" class="form-control form-control-lg" id="username" name="username" placeholder="Masukkan username" required autofocus>
              </div>

              <div class="mb-4">
                <label for="password" class="form-label fw-semibold">Password</label>
                <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Masukkan password" required>
              </div>

              <div class="d-grid">
                <button type="submit" name="login" class="btn btn-primary btn-lg">
                  Login
                </button>
              </div>
            </form>

            <div class="text-center mt-3">
              <small class="text-muted">© 2024 Sistem Informasi Akademik</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html>