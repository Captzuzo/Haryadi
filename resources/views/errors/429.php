<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>429 - Terlalu Banyak Request | <?= env('APP_NAME', 'Haryadi Framework') ?></title>
    
    <style>
        <?php include __DIR__ . '/error-styles.php'; ?>
        
        .error-icon { color: #6366f1; }
        .error-code { background: linear-gradient(135deg, #6366f1, #4f46e5); }
        
        .rate-limit-info {
            background: var(--secondary);
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--border);
            margin: 1.5rem 0;
            text-align: center;
        }
        
        .cooldown-timer {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            margin: 1rem 0;
        }
        
        .retry-after {
            font-size: 0.9rem;
            color: var(--text-light);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="theme-toggle" id="themeToggle">
            <span id="themeIcon">🌙</span>
        </div>

        <div class="error-icon">⏱️</div>
        <div class="error-code">429</div>
        <h1 class="error-title">Terlalu Banyak Request</h1>
        
        <p class="error-message">
            Anda telah melebihi batas request yang diizinkan. 
            Silakan tunggu beberapa saat sebelum mencoba kembali.
        </p>
        
        <div class="rate-limit-info">
            <div class="cooldown-timer" id="cooldownTimer">60</div>
            <div class="retry-after">detik tersisa</div>
        </div>
        
        <div class="error-actions">
            <button onclick="history.back()" class="btn btn-secondary" id="backBtn" disabled>
                <span>↩️</span>
                Kembali (<span id="backCountdown">60</span>)
            </button>
            <a href="/" class="btn btn-primary">
                <span>🏠</span>
                Ke Beranda
            </a>
        </div>
        
        <details class="error-details">
            <summary>📊 Informasi Rate Limit</summary>
            <div>
                <strong>Limit Type:</strong> <code>Rate Limiting</code><br>
                <strong>Retry After:</strong> <code id="retryAfter">60 seconds</code><br>
                <strong>Your IP:</strong> <code><?= htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? 'Unknown') ?></code><br>
                <strong>Request ID:</strong> <code>REQ-<?= strtoupper(uniqid()) ?></code>
            </div>
        </details>
    </div>

    <script>
        <?php include __DIR__ . '/error-scripts.php'; ?>
        
        // Rate limit countdown
        document.addEventListener('DOMContentLoaded', function() {
            let timeLeft = 60;
            const cooldownTimer = document.getElementById('cooldownTimer');
            const backCountdown = document.getElementById('backCountdown');
            const backBtn = document.getElementById('backBtn');
            const retryAfter = document.getElementById('retryAfter');
            
            const countdown = setInterval(() => {
                timeLeft--;
                
                cooldownTimer.textContent = timeLeft;
                backCountdown.textContent = timeLeft;
                retryAfter.textContent = timeLeft + ' seconds';
                
                if (timeLeft <= 0) {
                    clearInterval(countdown);
                    cooldownTimer.textContent = 'Siap!';
                    backCountdown.textContent = '0';
                    backBtn.disabled = false;
                    backBtn.textContent = '↩️ Kembali Sekarang';
                    backBtn.classList.remove('btn-secondary');
                    backBtn.classList.add('btn-primary');
                }
            }, 1000);
        });
    </script>
</body>
</html>