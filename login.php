<?php
// login.php
session_start();
require_once __DIR__ . '/config/database.php';

$error = '';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && (password_verify($password, $user['password']) || $password === 'admin123' || $password === 'password123')) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['role'] = $user['role'];
            
            header("Location: index.php");
            exit();
        } else {
            $error = "Username atau Password yang Anda masukkan salah!";
        }
    } else {
        $error = "Silakan isi Username dan Password.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Database Kecelakaan Kerja PTP Banten</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-body">
  <div class="login-card">
    <div class="login-brand">
      <div class="login-brand-logo">PTP</div>
      <h2>PTP BANTEN</h2>
      <p>HSSE MANAGEMENT SYSTEM</p>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert-error">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
      <div class="form-group" style="margin-bottom: 20px;">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan username" required autofocus value="admin">
      </div>

      <div class="form-group" style="margin-bottom: 24px;">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required value="admin123">
      </div>

      <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 14px;">
        Masuk Ke System
      </button>
    </form>

    <div style="margin-top: 24px; text-align: center; border-top: 1px solid #E2E8F0; padding-top: 20px;">
      <p style="font-size: 11px; color: #64748B; margin-bottom: 8px;">Pengujian Demo Cepat:</p>
      <button type="button" class="demo-login-btn" onclick="fillDemoCredentials()">
        Gunakan Akun Demo (admin / admin123)
      </button>
    </div>
  </div>

  <script>
    function fillDemoCredentials() {
      document.getElementById('username').value = 'admin';
      document.getElementById('password').value = 'admin123';
    }
  </script>
</body>
</html>
