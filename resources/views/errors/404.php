<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | <?= env('APP_NAME', 'Haryadi Framework') ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⚡</text></svg>">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #f8fafc;
            --text: #1e293b;
            --text-light: #64748b;
            --border: #e2e8f0;
            --bg-primary: #ffffff;
            --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        [data-theme="dark"] {
            --primary: #818cf8;
            --primary-dark: #6366f1;
            --secondary: #1e293b;
            --text: #f1f5f9;
            --text-light: #94a3b8;
            --border: #334155;
            --bg-primary: #0f172a;
            --bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            --shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text);
            line-height: 1.6;
            transition: all 0.3s ease;
            padding: 1rem;
        }

        .error-container {
            background: var(--bg-primary);
            padding: 3rem 2rem;
            border-radius: 20px;
            box-shadow: var(--shadow);
            text-align: center;
            max-width: 500px;
            width: 100%;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.8s ease-out;
        }

        .error-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
        }

        .error-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            animation: bounce 2s infinite;
        }

        .error-code {
            font-size: 5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .error-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text);
        }

        .error-message {
            color: var(--text-light);
            margin-bottom: 2.5rem;
            font-size: 1.1rem;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .error-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }

        .btn {
            padding: 0.875rem 1.75rem;
            border: none;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
        }

        .btn-secondary {
            background: var(--secondary);
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: var(--border);
            transform: translateY(-2px);
        }

        .error-details {
            background: var(--secondary);
            padding: 1.25rem;
            border-radius: 12px;
            font-size: 0.875rem;
            color: var(--text-light);
            text-align: left;
            border: 1px solid var(--border);
            margin-top: 2rem;
        }

        .error-details summary {
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 0.5rem;
            color: var(--text);
        }

        .error-details div {
            margin-top: 0.75rem;
        }

        .error-details code {
            background: var(--bg-primary);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 0.8rem;
            border: 1px solid var(--border);
        }

        .theme-toggle {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--secondary);
            border: 1px solid var(--border);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--text);
        }

        .theme-toggle:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .error-container {
                padding: 2rem 1.5rem;
            }
            
            .error-code {
                font-size: 4rem;
            }
            
            .error-title {
                font-size: 1.5rem;
            }
            
            .error-actions {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Loading animation for buttons */
        .btn.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .btn.loading::after {
            content: '';
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 8px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <!-- Theme Toggle -->
        <div class="theme-toggle" id="themeToggle">
            <span id="themeIcon">🌙</span>
        </div>

        <!-- Error Icon -->
        <div class="error-icon">🚫</div>
        
        <!-- Error Code -->
        <div class="error-code">404</div>
        
        <!-- Error Title -->
        <h1 class="error-title">Halaman Tidak Ditemukan</h1>
        
        <!-- Error Message -->
        <p class="error-message">
            Maaf, halaman yang Anda cari tidak dapat ditemukan di server. 
        </p>
        
        <!-- Action Buttons -->
        <div class="error-actions">
            <button onclick="history.back()" class="btn btn-secondary" id="backBtn">
                <span>↩️</span>
                Kembali ke Sebelumnya
            </button>
        </div>
        
        <!-- Debug Information -->
        <details class="error-details">
            <summary>📋 Informasi Teknis</summary>
            <div>
                <strong>Path:</strong> <code><?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/') ?></code><br>
                <strong>Method:</strong> <code><?= htmlspecialchars($_SERVER['REQUEST_METHOD'] ?? 'GET') ?></code><br>
                <strong>Framework:</strong> <code>Haryadi Framework <?= env('APP_VERSION', '1.0.0') ?></code><br>
                <strong>Environment:</strong> <code><?= env('APP_ENV', 'production') ?></code>
            </div>
        </details>
    </div>

    <script>
        // Theme Toggle Functionality
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const body = document.body;

        // Load saved theme or detect system preference
        const savedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
            body.setAttribute('data-theme', 'dark');
            themeIcon.textContent = '☀️';
        }

        themeToggle.addEventListener('click', () => {
            const currentTheme = body.getAttribute('data-theme');
            
            if (currentTheme === 'dark') {
                body.removeAttribute('data-theme');
                themeIcon.textContent = '🌙';
                localStorage.setItem('theme', 'light');
            } else {
                body.setAttribute('data-theme', 'dark');
                themeIcon.textContent = '☀️';
                localStorage.setItem('theme', 'dark');
            }
        });

        // Button loading states
        const homeBtn = document.getElementById('homeBtn');
        const backBtn = document.getElementById('backBtn');

        homeBtn.addEventListener('click', function(e) {
            this.classList.add('loading');
            setTimeout(() => {
                this.classList.remove('loading');
            }, 1000);
        });

        backBtn.addEventListener('click', function() {
            this.classList.add('loading');
            setTimeout(() => {
                this.classList.remove('loading');
                if (history.length > 1) {
                    history.back();
                } else {
                    window.location.href = '/';
                }
            }, 500);
        });

        // Add some interactive elements
        document.addEventListener('DOMContentLoaded', function() {
            // Animate error code on load
            const errorCode = document.querySelector('.error-code');
            errorCode.style.animation = 'float 3s ease-in-out infinite';
            
            // Add click effect to buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('mousedown', function() {
                    this.style.transform = 'scale(0.95)';
                });
                
                button.addEventListener('mouseup', function() {
                    this.style.transform = '';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                });
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Alt + H for home
            if (e.altKey && e.key === 'h') {
                e.preventDefault();
                homeBtn.click();
            }
            
            // Alt + B for back
            if (e.altKey && e.key === 'b') {
                e.preventDefault();
                backBtn.click();
            }
            
            // Escape key to go back
            if (e.key === 'Escape') {
                backBtn.click();
            }
        });

        // Console message for developers
        console.log(
            '%c⚡ Haryadi Framework - 404 Error',
            'color: #6366f1; font-size: 16px; font-weight: bold;'
        );
        console.log(
            `%cPath: ${window.location.pathname}\n` +
            `Check your routes configuration or create this page.`,
            'color: #64748b;'
        );
    </script>
</body>
</html>