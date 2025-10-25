<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Login' ?></title>
    <link rel="icon" type="image/png/img/jpg" href="/assets/images/haryadi/ht.png">
    <link rel="stylesheet" href="/assets/css/haryadi/login/style.css">

</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <!-- Logo Section -->
            <div class="logo-container">
                <div class="logo">
                    <!-- Ganti dengan path logo Anda -->
                    <img src="/assets/images/haryadi/ht.png" alt="Logo Aplikasi" class="logo" 
                         onerror="this.style.display='none'; document.querySelector('.logo').innerHTML='🚀'">
                </div>
            </div>
            
            <h2 class="auth-title">Masuk</h2>
            
            <div id="alert" class="alert"></div>

            <form id="loginForm">
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input" required 
                           placeholder="Masukkan email Anda">
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-input" required 
                           placeholder="Masukkan password Anda">
                </div>
                
                <button type="submit" class="btn-auth" id="submitBtn">Login</button>
            </form>

            <!-- <div class="demo-account">
                <h4>🔑 Akun Demo:</h4>
                <p><strong>Email:</strong> admin@haryadi.com</p>
                <p><strong>Password:</strong> password123</p>
            </div> -->
            
            <div class="auth-links">
                <p>Belum punya akun? <a href="/register" class="auth-link">Daftar di sini</a></p>
                <p><a href="/" class="auth-link">← Kembali ke Home</a></p>
            </div>
        </div>
    </div>

    <script src="/assets/js/haryadi/login/script.js"></script>
</body>
</html>