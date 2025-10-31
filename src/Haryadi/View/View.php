<?php

namespace Haryadi\View;

class View
{
    protected string $viewPath;
    protected array $data = [];

    public function __construct()
    {
        $this->viewPath = dirname(dirname(dirname(__DIR__))) . '/resources/views/';
    }

    protected static array $sections = [];

    public function render(string $template, array $data = []): void
    {
        $this->data = $data;
        $templatePath = $this->viewPath . str_replace('.', '/', $template) . '.haryadi.php';

        if (!file_exists($templatePath)) {
            throw new \Exception("View template not found: {$template}");
        }

        // Ensure cache directory exists
        $cacheDir = dirname(dirname(dirname(__DIR__))) . '/storage/cache/views/';
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0777, true);
        }

        $compiledPath = $cacheDir . md5($templatePath) . '.php';

        if (!file_exists($compiledPath) || filemtime($templatePath) > filemtime($compiledPath)) {
            $this->compile($templatePath, $compiledPath);
        }

        extract($this->data);
        // provide env() helper access inside views
        $sections = &self::$sections; // Use static sections array
        include $compiledPath;
    }

    public function include(string $partial, array $data = []): void
    {
        $partialPath = $this->viewPath . str_replace('.', '/', $partial) . '.haryadi.php';
        
        if (!file_exists($partialPath)) {
            throw new \Exception("Partial view not found: {$partial}");
        }

        extract(array_merge($this->data, $data));
        include $partialPath;
    }

    protected function compile(string $sourcePath, string $compiledPath): void
    {
        $content = file_get_contents($sourcePath);

        // Handle @extends('layout.name')
        $layout = null;
        if (preg_match("/@extends\(\s*'([^)']+)'\s*\)/", $content, $m)) {
            $layout = trim($m[1]);
            // remove the extends directive from content
            $content = str_replace($m[0], '', $content);
        }

        // Handle @section('name', 'content') single line
        $content = preg_replace_callback("/@section\(\s*'([^']+)'\s*,\s*'([^']+)'\s*\)/", function ($matches) {
            $name = $matches[1];
            $value = $matches[2];
            return "<?php \$sections['" . $name . "'] = '" . addslashes($value) . "'; ?>";
        }, $content);

        // Handle @section('name') ... @endsection
        $content = preg_replace_callback("/@section\(\s*'([^)']+)'\s*\)(.*?)@endsection/s", function ($matches) {
            $name = $matches[1];
            $inner = $matches[2];
            return "<?php ob_start(); ?>" . $inner . "<?php \$sections['" . $name . "'] = ob_get_clean(); ?>";
        }, $content);

        // Handle @include('path.to.partial') -> include file
        $content = preg_replace_callback("/@include\(\s*'([^)']+)'\s*\)/", function ($m) {
            $partial = str_replace('.', '/', $m[1]);
            $path = addslashes($this->viewPath . $partial . '.haryadi.php');
            return "<?php include '" . $path . "'; ?>";
        }, $content);

        // Handle @csrf directive
            $csrfSnippet = <<<'PHP'
    <?php
    if (function_exists('csrf_field')) {
        echo csrf_field();
    } else {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['_token'])) {
            $_SESSION['_token'] = bin2hex(random_bytes(16));
        }
        echo '<input type="hidden" name="_token" value="' . htmlspecialchars($_SESSION['_token']) . '" />';
    }
    ?>
    PHP;

            $content = str_replace('@csrf', $csrfSnippet, $content);

        // If layout is defined, append code to include compiled layout
        if ($layout) {
            // compile layout as well so @yield works
            $layoutPath = $this->viewPath . str_replace('.', '/', $layout) . '.haryadi.php';
            $layoutCompiled = dirname($compiledPath) . '/' . md5($layoutPath) . '.php';
            if (!file_exists($layoutCompiled) || (file_exists($layoutPath) && filemtime($layoutPath) > filemtime($layoutCompiled))) {
                // recursively compile layout
                $this->compile($layoutPath, $layoutCompiled);
            }

            // after sections are defined in this compiled child, include the layout compiled file
            $content = $content . "\n<?php include '" . addslashes($layoutCompiled) . "'; ?>";
        }

        // In layouts: replace @yield('name', 'default') with PHP echo from sections
        $content = preg_replace_callback("/@yield\(\s*'([^)']+)'(?:\s*,\s*'([^']*)')?\s*\)/", function ($m) {
            $name = $m[1];
            $default = isset($m[2]) ? $m[2] : '';
            $defaultEsc = addslashes($default);
            return "<?= isset(\$sections['" . $name . "']) ? \$sections['" . $name . "'] : '" . $defaultEsc . "' ?>";
        }, $content);

        // Save compiled file
        file_put_contents($compiledPath, $content);
    }
}