<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - Layanan Tidak Tersedia | <?= env('APP_NAME', 'Haryadi Framework') ?></title>
    
    <style>
        <?php include __DIR__ . '/error-styles.php'; ?>
        
        .error-icon { color: #8b5cf6; }
        .error-code { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
        
        .maintenance-info {
            background: var(--secondary);
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--border);
            margin: 1.5rem 0;
            text-align: left;
        }
        
        .countdown {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin: 1rem 0;
            text-align: center;
        }
        
        .progress-bar {
            width: 100%;
            height: 6px;
            background: var(--border);
            border-radius: 3px;
            overflow: hidden;
            margin: 1rem 0;
        }
        
        .progress {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
            width: 0%;
            transition: width 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="theme-toggle" id="themeToggle">
            <span id="themeIcon">🌙</span>
        </div>

        <div class="error-icon">🔧</div>
        <div class="error-code">503</div>
        <h1 class="error-title">Sedang Dalam Pemeliharaan</h1>
        
        <p class="error-message">
            Sistem sedang dalam pemeliharaan untuk peningkatan performa dan keamanan.
            Kami akan kembali online sesegera mungkin. Terima kasih atas pengertiannya.
        </p>
        
        <div class="maintenance-info">
            <h3>📅 Informasi Pemeliharaan</h3>
            <p><strong>Perkiraan Selesai:</strong> <span id="estimatedTime">30 menit lagi</span></p>
            <p><strong>Progress:</strong></p>
            <div class="progress-bar">
                <div class="progress" id="maintenanceProgress"></div>
            </div>
            <div class="countdown" id="countdown">30:00</div>
        </div>
        
        <div class="error-actions">
            <a href="javascript:location.reload()" class="btn btn-primary">
                <span>🔄</span>
                Periksa Kembali
            </a>
            <a href="mailto:<?= env('APP_SUPPORT_EMAIL', 'support@haryadi.com') ?>" class="btn btn-secondary">
                <span>📧</span>
                Hubungi Support
            </a>
        </div>
        
        <details class="error-details">
            <summary>📊 Status Layanan</summary>
            <div>
                <strong>Maintenance Start:</strong> <code><?= date('Y-m-d H:i:s') ?></code><br>
                <strong>Estimated End:</strong> <code id="estimatedEnd"><?= date('Y-m-d H:i:s', time() + 1800) ?></code><br>
                <strong>Service:</strong> <code>Haryadi Framework API</code><br>
                <strong>Incident ID:</strong> <code>INC-<?= strtoupper(uniqid()) ?></code>
            </div>
        </details>
    </div>

    <script>
        <?php include __DIR__ . '/error-scripts.php'; ?>
        
        // Maintenance countdown
        document.addEventListener('DOMContentLoaded', function() {
            let timeLeft = 30 * 60; // 30 minutes in seconds
            const progressBar = document.getElementById('maintenanceProgress');
            const countdownElement = document.getElementById('countdown');
            
            const countdown = setInterval(() => {
                timeLeft--;
                
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                
                countdownElement.textContent = 
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                // Update progress bar
                const progress = ((30 * 60 - timeLeft) / (30 * 60)) * 100;
                progressBar.style.width = progress + '%';
                
                if (timeLeft <= 0) {
                    clearInterval(countdown);
                    countdownElement.textContent = 'Selesai!';
                    progressBar.style.width = '100%';
                    document.querySelector('.error-message').textContent = 
                        'Pemeliharaan telah selesai. Sistem akan segera kembali online.';
                }
            }, 1000);
        });
    </script>
</body>
</html>