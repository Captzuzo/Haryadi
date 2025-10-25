<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error | <?= env('APP_NAME', 'Haryadi Framework') ?></title>
    
    <style>
        <?php include __DIR__ . '/error-styles.php'; ?>
        
        .error-icon { color: #f59e0b; }
        .error-code { background: linear-gradient(135deg, #f59e0b, #d97706); }
        
        .server-status {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin: 1.5rem 0;
        }
        
        .status-item {
            background: var(--secondary);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid var(--border);
            text-align: center;
            min-width: 100px;
        }
        
        .status-value {
            font-weight: 600;
            color: var(--primary);
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="theme-toggle" id="themeToggle">
            <span id="themeIcon">🌙</span>
        </div>

        <div class="error-icon">💥</div>
        <div class="error-code">500</div>
        <h1 class="error-title">Kesalahan Server</h1>
        
        <p class="error-message">
            Terjadi kesalahan internal pada server. Tim developer telah diberitahu 
            dan sedang memperbaiki masalah ini. Silakan coba lagi dalam beberapa saat.
        </p>
        
        <div class="server-status">
            <div class="status-item">
                <div>🕒 Uptime</div>
                <div class="status-value" id="uptime">Loading...</div>
            </div>
            <div class="status-item">
                <div>📊 Memory</div>
                <div class="status-value" id="memory">Loading...</div>
            </div>
        </div>
        
        <div class="error-actions">
            <a href="/" class="btn btn-primary">
                <span>🔄</span>
                Refresh Halaman
            </a>
            <a href="javascript:location.reload()" class="btn btn-secondary">
                <span>🔧</span>
                Coba Lagi
            </a>
            <button onclick="history.back()" class="btn btn-secondary">
                <span>↩️</span>
                Kembali
            </button>
        </div>
        
        <details class="error-details">
            <summary>🔧 Informasi Server</summary>
            <div>
                <strong>Server Time:</strong> <code><?= date('Y-m-d H:i:s') ?></code><br>
                <strong>PHP Version:</strong> <code><?= PHP_VERSION ?></code><br>
                <strong>Framework:</strong> <code>Haryadi Framework <?= env('APP_VERSION', '1.0.0') ?></code><br>
                <strong>Environment:</strong> <code><?= env('APP_ENV', 'production') ?></code>
                <?php if (env('APP_DEBUG')): ?>
                <br><strong>Error:</strong> <code><?= htmlspecialchars($error ?? 'Unknown error') ?></code>
                <?php endif; ?>
            </div>
        </details>
    </div>

    <script>
        <?php include __DIR__ . '/error-scripts.php'; ?>
        
        // Server status simulation
        document.addEventListener('DOMContentLoaded', function() {
            // Simulate uptime
            const startTime = Date.now();
            setInterval(() => {
                const uptime = Math.floor((Date.now() - startTime) / 1000);
                document.getElementById('uptime').textContent = formatUptime(uptime);
            }, 1000);
            
            // Simulate memory usage
            setInterval(() => {
                const memory = (Math.random() * 80 + 20).toFixed(1);
                document.getElementById('memory').textContent = memory + '%';
            }, 2000);
        });
        
        function formatUptime(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }
    </script>
</body>
</html>