<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Haryadi')</title>
    <link rel="stylesheet" href="/assets/haryadi/css/style.css">
</head>
<body>
    <?php // navbar partial ?>
    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Haryadi Framework</p>
    </footer>

    <script src="/assets/haryadi/js/script.js"></script>
</body>
</html>
