<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Register' ?></title>
    <link rel="icon" type="image/png/img/jpg" href="/assets/images/haryadi/ht.png">
    <link rel="stylesheet" href="/assets/css/haryadi/register/style.css">
    
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <!-- Logo Section -->
            <div class="logo-container">
                <div class="logo">
                    <img src="/assets/images/haryadi/ht.png" alt="Logo Aplikasi" class="logo" 
                         onerror="this.style.display='none'; document.querySelector('.logo').innerHTML='🚀'">
                </div>
            </div>
            <h2 class="auth-title">Daftar</h2>
            
            <div id="alert" class="alert"></div>

            <form id="registerForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name" class="form-label">Nama Depan</label>
                        <input type="text" id="first_name" name="first_name" class="form-input" required 
                               placeholder="Nama depan">
                    </div>
                    
                    <div class="form-group">
                        <label for="last_name" class="form-label">Nama Belakang</label>
                        <input type="text" id="last_name" name="last_name" class="form-input" required 
                               placeholder="Nama belakang">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input" required 
                           placeholder="Masukkan email Anda">
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-input" required 
                           placeholder="Buat password yang kuat"
                           oninput="validatePassword(this.value)">
                    <div class="password-strength">
                        <div id="strengthBar" class="strength-bar"></div>
                    </div>
                </div>

                
                <button type="submit" class="btn-auth" id="submitBtn">Daftar Sekarang</button>
            </form>
            
            <div class="auth-links">
                <p>Sudah punya akun? <a href="/login" class="auth-link">Login di sini</a></p>
                <p><a href="/" class="auth-link">← Kembali ke Home</a></p>
            </div>
        </div>
    </div>

    <script src="/assets/js/script.js"></script>
    <script>
        function validatePassword(password) {
            const requirements = {
                length: password.length >= 8,
                upperLower: /[a-z]/.test(password) && /[A-Z]/.test(password),
                number: /\d/.test(password),
                special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
            };

            // Update requirement indicators
            updateRequirement('reqLength', requirements.length);
            updateRequirement('reqUpperLower', requirements.upperLower);
            updateRequirement('reqNumber', requirements.number);
            updateRequirement('reqSpecial', requirements.special);

            // Update password strength
            updatePasswordStrength(requirements);
            
            // Validate confirm password if it has value
            const confirmPassword = document.getElementById('confirm_password').value;
            if (confirmPassword) {
                validateConfirmPassword(confirmPassword);
            }
        }

        function validateConfirmPassword(confirmPassword) {
            const password = document.getElementById('password').value;
            const isMatch = password === confirmPassword && password.length > 0;
            
            updateRequirement('reqMatch', isMatch);
            
            // Update confirm password border color
            const confirmInput = document.getElementById('confirm_password');
            if (confirmPassword.length > 0) {
                confirmInput.style.borderColor = isMatch ? 'var(--success)' : 'var(--danger)';
            } else {
                confirmInput.style.borderColor = 'var(--border)';
            }
        }

        function updateRequirement(elementId, isMet) {
            const element = document.getElementById(elementId);
            const icon = element.querySelector('.requirement-icon');
            const text = element.querySelector('.requirement-text');
            
            if (isMet) {
                icon.className = 'requirement-icon requirement-met';
                icon.textContent = '✓';
                element.className = 'requirement-item requirement-met';
                icon.classList.add('bounce');
                setTimeout(() => icon.classList.remove('bounce'), 500);
            } else {
                icon.className = 'requirement-icon requirement-unmet';
                icon.textContent = '✗';
                element.className = 'requirement-item requirement-unmet';
            }
        }

        function updatePasswordStrength(requirements) {
            const strengthBar = document.getElementById('strengthBar');
            const metCount = Object.values(requirements).filter(Boolean).length;
            const totalCount = Object.keys(requirements).length;
            const strength = metCount / totalCount;
            
            strengthBar.className = 'strength-bar';
            
            if (strength === 0) {
                strengthBar.style.width = '0%';
            } else if (strength <= 0.25) {
                strengthBar.classList.add('strength-weak');
            } else if (strength <= 0.5) {
                strengthBar.classList.add('strength-fair');
            } else if (strength <= 0.75) {
                strengthBar.classList.add('strength-good');
            } else {
                strengthBar.classList.add('strength-strong');
            }
        }

        function isPasswordValid() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            const requirements = {
                length: password.length >= 8,
                upperLower: /[a-z]/.test(password) && /[A-Z]/.test(password),
                number: /\d/.test(password),
                special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password),
                match: password === confirmPassword
            };
            
            return Object.values(requirements).every(Boolean);
        }

        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!isPasswordValid()) {
                const alert = document.getElementById('alert');
                alert.className = 'alert alert-error';
                alert.textContent = 'Harap penuhi semua persyaratan password!';
                alert.style.display = 'block';
                alert.style.animation = 'shake 0.5s ease-in-out';
                setTimeout(() => alert.style.animation = '', 500);
                return;
            }
            
            const formData = new FormData(this);
            const alert = document.getElementById('alert');
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.textContent;
            
            // Show loading
            submitBtn.textContent = 'Mendaftarkan...';
            submitBtn.disabled = true;
            alert.style.display = 'none';
            
            try {
                const response = await fetch('/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert.className = 'alert alert-success';
                    alert.textContent = result.message || 'Pendaftaran berhasil!';
                    alert.style.display = 'block';
                    
                    // Redirect setelah 2 detik
                    setTimeout(() => {
                        window.location.href = result.data?.redirect || '/login';
                    }, 2000);
                } else {
                    alert.className = 'alert alert-error';
                    alert.textContent = result.message || 'Pendaftaran gagal!';
                    if (result.data && result.data.errors) {
                        alert.textContent += ': ' + Object.values(result.data.errors).join(', ');
                    }
                    alert.style.display = 'block';
                    alert.style.animation = 'shake 0.5s ease-in-out';
                    setTimeout(() => alert.style.animation = '', 500);
                }
            } catch (error) {
                console.error('Error:', error);
                alert.className = 'alert alert-error';
                alert.textContent = 'Terjadi kesalahan jaringan!';
                alert.style.display = 'block';
            } finally {
                // Reset button
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        });

        // Initialize password validation on page load
        document.addEventListener('DOMContentLoaded', function() {
            const password = document.getElementById('password').value;
            if (password) {
                validatePassword(password);
            }
            
            const confirmPassword = document.getElementById('confirm_password').value;
            if (confirmPassword) {
                validateConfirmPassword(confirmPassword);
            }
        });
    </script>
</body>
</html>