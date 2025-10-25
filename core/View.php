<?php
namespace Haryadi\Core;

class View
{
    private $viewsPath;

    public function __construct()
    {
        $this->viewsPath = BASE_PATH . '/resources/views/';
    }

    /**
     * Instance method untuk render view
     */
    public function render(string $template, array $data = []): void
    {
        $templatePath = $this->viewsPath . $template . '.php';
        
        if (!file_exists($templatePath)) {
            throw new \Exception("View template not found: {$template}");
        }

        // Extract data to variables
        extract($data);

        // Start output buffering
        ob_start();

        // Include the template
        include $templatePath;

        // Get the contents and clean the buffer
        $content = ob_get_clean();

        // Output the content
        echo $content;
    }

    /**
     * Static method untuk convenience
     */
    public static function renderStatic(string $template, array $data = []): void
    {
        $instance = new self();
        $instance->render($template, $data);
    }
}