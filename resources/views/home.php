<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?? 'Haryadi Framework' ?></title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .container { background: #f5f5f5; padding: 30px; border-radius: 10px; }
        h1 { color: #333; }
        .feature { background: white; padding: 10px; margin: 5px 0; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= $title ?></h1>
        <p><?= $message ?></p>
        
        <h3>Fitur Framework:</h3>
        <?php foreach ($features as $feature): ?>
            <div class="feature">✅ <?= $feature ?></div>
        <?php endforeach; ?>
        
        <div style="margin-top: 20px;">
            <a href="/about">Tentang</a> | 
            <a href="/contact">Kontak</a> | 
            <a href="/demo">Demo</a>
        </div>
    </div>
</body>
</html>