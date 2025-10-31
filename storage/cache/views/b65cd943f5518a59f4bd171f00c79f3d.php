<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($sections['title']) ? $sections['title'] : 'Haryadi - Framework PHP' ?></title>
    <link rel="icon" type="image/png/img/jpg" href="/assets/images/ht.png">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link href="/assets/haryadi/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Theme Toggle -->
    <div class="theme-toggle" id="themeToggle">
        <span class="theme-icon" id="themeIcon">🌙</span>
    </div>

    <!-- Environment Badge -->
    <div class="env-badge" id="envBadge">
        <?php echo strtoupper(env('APP_ENV', 'local')); ?> Environment
    </div>

    <!-- Login Badge -->
    <div class="login-badge">
        <a href="/login" class="login-link">Login</a>
    </div>

    <div class="container">
        <?= isset($sections['content']) ? $sections['content'] : '' ?>

        <!-- Include Footer -->
        <?php include 'D:\\haryadi/resources/views/partials/footer.haryadi.php'; ?>
    </div>

    <!-- JavaScript -->
    <script src="/assets/haryadi/js/script.js"></script>
</body>
</html>