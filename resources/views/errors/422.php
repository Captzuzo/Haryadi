<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>422 - Data Tidak Valid | <?= env('APP_NAME', 'Haryadi Framework') ?></title>
    
    <style>
        <?php include __DIR__ . '/error-styles.php'; ?>
        
        .error-icon { color: #f97316; }
        .error-code { background: linear-gradient(135deg, #f97316, #ea580c); }
        
        .validation-errors {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 1rem;
            margin: 1.5rem 0;
            text-align: left;
        }
        
        [data-theme="dark"] .validation-errors {
            background: #1f2937;
            border-color: #7f1d1d;
        }
        
        .validation-errors h4 {
            color: #dc2626;
            margin-bottom: 0.5rem;
        }
        
        .error-list {
            list-style: none;
            padding: 0;
        }
        
        .error-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #fecaca;
            color: #b91c1c;
        }
        
        [data-theme="dark"] .error-list li {
            border-color: #7f1d1d;
            color: #fca5a5;
        }
        
        .error-list li:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="theme-toggle" id="themeToggle">
            <span id="themeIcon">🌙</span>
        </div>

        <div class="error-icon">📝</div>
        <div class="error-code">422</div>
        <h1 class="error-title">Data Tidak Valid</h1>
        
        <p class="error-message">
            Data yang Anda kirim tidak dapat diproses oleh server. 
            Silakan periksa kembali informasi yang Anda masukkan.
        </p>
        
        <?php if (isset($errors) && !empty($errors)): ?>
        <div class="validation-errors">
            <h4>⚠️ Kesalahan Validasi:</h4>
            <ul class="error-list">
                <?php foreach ($errors as $field => $error): ?>
                <li><strong><?= htmlspecialchars($field) ?>:</strong> <?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        
        <div class="error-actions">
            <button onclick="history.back()" class="btn btn-primary">
                <span>✏️</span>
                Kembali & Perbaiki
            </button>
            <a href="/" class="btn btn-secondary">
                <span>🏠</span>
                Ke Beranda
            </a>
        </div>
        
        <details class="error-details">
            <summary>🔍 Debug Information</summary>
            <div>
                <strong>Validation Failed:</strong> <code>Unprocessable Entity</code><br>
                <strong>Content Type:</strong> <code><?= htmlspecialchars($_SERVER['CONTENT_TYPE'] ?? 'application/json') ?></code><br>
                <strong>Timestamp:</strong> <code><?= date('Y-m-d H:i:s') ?></code>
            </div>
        </details>
    </div>

    <script>
        <?php include __DIR__ . '/error-scripts.php'; ?>
        
        // Auto-focus on back button for better UX
        document.addEventListener('DOMContentLoaded', function() {
            const backButton = document.querySelector('.btn-primary');
            backButton.focus();
        });
    </script>
</body>
</html>