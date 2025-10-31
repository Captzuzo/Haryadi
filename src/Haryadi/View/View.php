<?php
namespace Haryadi\View;

class View
{
    public static function render(string $name, array $data = []): string
    {
        $viewsPath = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views';
    $name = str_replace("\0", '', $name);
    // support dot notation: layouts.main -> layouts/main
    $name = str_replace('.', DIRECTORY_SEPARATOR, $name);
        $name = ltrim($name, '/\\');

        if (strpos($name, '..') !== false) {
            return "Invalid view name: $name";
        }

        if (substr($name, -12) !== '.haryadi.php') {
            $name .= '.haryadi.php';
        }

        $file = $viewsPath . DIRECTORY_SEPARATOR . $name;
        if (!file_exists($file)) {
            return "View not found: $name";
        }

        $source = file_get_contents($file);
        $compiled = self::compile($source);

        // Cache directory for compiled templates
        $cacheDir = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'views';
        if (!is_dir($cacheDir)) @mkdir($cacheDir, 0777, true);

        $cacheKey = md5($file . '|' . filemtime($file));
        $compiledPath = $cacheDir . DIRECTORY_SEPARATOR . $cacheKey . '.php';

        if (!file_exists($compiledPath)) {
            // write compiled PHP to cache file
            file_put_contents($compiledPath, $compiled);
        }

        // ensure framework helpers are available when rendering cached templates
        $helpersFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Support' . DIRECTORY_SEPARATOR . 'helpers.php';
        if (!function_exists('csrf_field') && file_exists($helpersFile)) {
            require_once $helpersFile;
        }

        extract($data, EXTR_SKIP);
        $__env = new Environment();

        ob_start();
        include $compiledPath;

        // If the compiled template declared a parent layout via $__env, render parent(s)
        $parent = $__env->getParent();
        while ($parent) {
            $parentName = $parent;
            // support dot notation like "layouts.main" -> "layouts/main"
            $parentName = str_replace('.', DIRECTORY_SEPARATOR, $parentName);
            if (substr($parentName, -12) !== '.haryadi.php') {
                $parentName .= '.haryadi.php';
            }

            $parentFile = $viewsPath . DIRECTORY_SEPARATOR . $parentName;
            if (!file_exists($parentFile)) {
                // stop and return what we have if parent missing
                break;
            }

            $parentSource = file_get_contents($parentFile);
            $parentCompiled = self::compile($parentSource);
            $parentCacheKey = md5($parentFile . '|' . filemtime($parentFile));
            $parentCompiledPath = $cacheDir . DIRECTORY_SEPARATOR . $parentCacheKey . '.php';
            if (!file_exists($parentCompiledPath)) {
                file_put_contents($parentCompiledPath, $parentCompiled);
            }

            // clear current parent marker to avoid re-including the same parent
            $__env->setParent(null);

            // include the parent compiled template in the same scope so it can echo sections
            include $parentCompiledPath;

            // check if the parent itself extends another layout
            $parent = $__env->getParent();
        }

        return ob_get_clean();
    }

    protected static function compile(string $source): string
    {
        // Save HTML comments to placeholders so we don't compile directives inside them
        $comments = [];
        if (preg_match_all('/<!--(.*?)-->/s', $source, $mComments)) {
            foreach ($mComments[0] as $i => $full) {
                $token = "__HTML_COMMENT_{$i}__";
                $comments[$token] = $full;
                $source = str_replace($full, $token, $source);
            }
        }

        $source = preg_replace_callback('/@extends\([\"\'](.+?)[\"\']\)/', function ($m) {
              return '<?php $__env->setParent(\'' . addslashes($m[1]) . '\'); ?>';
        }, $source);

        $source = preg_replace_callback('/@section\([\"\'](.+?)[\"\']\)/', function ($m) {
              return '<?php $__env->startSection(\'' . addslashes($m[1]) . '\'); ?>';
        }, $source);

        $source = preg_replace('/@endsection/', '<?php $__env->stopSection(); ?>', $source);

        // @yield('name', 'default')
        $source = preg_replace_callback('/@yield\([\"\'](.+?)[\"\']\s*(?:,\s*([^\)]+)\s*)?\)/', function ($m) {
              $default = isset($m[2]) ? $m[2] : "''";
              return '<?php echo $__env->yieldContent(\'' . addslashes($m[1]) . '\', ' . $default . '); ?>';
        }, $source);

        // CSRF helper
        $source = str_replace('@csrf', '<?php echo csrf_field(); ?>', $source);

      // @include('partial.name') -> render the partial
      $source = preg_replace_callback('/@include\([\"\'](.+?)[\"\']\)/', function ($m) {
          return '<?php echo \\Haryadi\\View\\View::render(\'' . addslashes($m[1]) . '\'); ?>';
      }, $source);

        $source = preg_replace_callback('/\{\{\s*(.+?)\s*\}\}/', function ($m) {
              return '<?php echo htmlspecialchars(' . $m[1] . ', ENT_QUOTES, \'' . 'UTF-8' . '\'); ?>';
        }, $source);

        $source = preg_replace_callback('/\{!!\s*(.+?)\s*!!\}/', function ($m) {
              return '<?php echo ' . $m[1] . '; ?>';
        }, $source);

        // restore comments
        if (!empty($comments)) {
            foreach ($comments as $token => $full) {
                $source = str_replace($token, $full, $source);
            }
        }

        return $source;
    }
}