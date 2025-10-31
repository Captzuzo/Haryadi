<?php
$viewsPath = __DIR__ . '/../resources/views';
$src = file_get_contents($viewsPath . '/home.haryadi.php');
$source = $src;

// Save HTML comments to placeholders
$comments = [];
if (preg_match_all('/<!--(.*?)-->/s', $source, $mComments)) {
    foreach ($mComments[0] as $i => $full) {
        $token = "__HTML_COMMENT_{$i}__";
        $comments[$token] = $full;
        $source = str_replace($full, $token, $source);
    }
}

$source = preg_replace_callback('/@extends\(["\'](.+?)["\']\)/', function ($m) {
    return "<?php \\$__env->setParent('" . addslashes($m[1]) . "'); ?>";
}, $source);

$source = preg_replace_callback('/@section\(["\'](.+?)["\']\)/', function ($m) {
    return "<?php \\$__env->startSection('" . addslashes($m[1]) . "'); ?>";
}, $source);

$source = preg_replace('/@endsection/', '<?php $__env->stopSection(); ?>', $source);

$source = preg_replace_callback('/@yield\(["\'](.+?)["\']\s*(?:,\s*([^\)]+)\s*)?\)/', function ($m) {
    $default = isset($m[2]) ? $m[2] : "''";
    return "<?php echo \\$__env->yieldContent('" . addslashes($m[1]) . "', $default); ?>";
}, $source);

$source = str_replace('@csrf', '<?php echo csrf_field(); ?>', $source);

$source = preg_replace_callback('/\{\{\s*(.+?)\s*\}\}/', function ($m) {
    return "<?php echo htmlspecialchars(" . $m[1] . ", ENT_QUOTES, 'UTF-8'); ?>";
}, $source);

$source = preg_replace_callback('/\{!!\s*(.+?)\s*!!\}/', function ($m) {
    return "<?php echo " . $m[1] . "; ?>";
}, $source);

if (!empty($comments)) {
    foreach ($comments as $token => $full) {
        $source = str_replace($token, $full, $source);
    }
}

file_put_contents(__DIR__ . '/../storage/last_compiled_debug.php', $source);
echo "Wrote compiled to storage/last_compiled_debug.php\n";
