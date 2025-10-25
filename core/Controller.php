<?php
namespace Haryadi\Core;

use Haryadi\Core\Request;
use Haryadi\Core\Response;
use Haryadi\Core\View;

class Controller
{
    /**
     * Request instance
     */
    protected Request $request;

    /**
     * View instance
     */
    protected View $view;

    /**
     * Constructor - inject Request dan buat View instance
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->view = new View();
    }

    /**
     * Render a view template
     */
    protected function view(string $template, array $data = []): void
    {
        $this->view->render($template, $data);
    }

    /**
     * Return JSON response
     */
    protected function json($data, int $statusCode = 200): void
    {
        Response::json($data, $statusCode);
    }

    /**
     * Redirect to another URL
     */
    protected function redirect(string $url, int $statusCode = 302): void
    {
        Response::redirect($url, $statusCode);
    }

    /**
     * Get input data from request
     */
    protected function input(?string $key = null, $default = null)
    {
        return $this->request->input($key, $default);
    }

    /**
     * Get query parameters
     */
    protected function query(?string $key = null, $default = null)
    {
        return $this->request->get($key, $default);
    }

    /**
     * Get POST data
     */
    protected function post(?string $key = null, $default = null)
    {
        return $this->request->post($key, $default);
    }

    /**
     * Get uploaded files
     */
    protected function files(?string $key = null)
    {
        return $this->request->files($key);
    }

    /**
     * Validate request data
     */
    protected function validate(array $rules): array
    {
        // Simple validation implementation
        $data = [];
        $errors = [];

        foreach ($rules as $field => $rule) {
            $value = $this->input($field);

            if (strpos($rule, 'required') !== false && empty($value)) {
                $errors[$field] = "The $field field is required.";
            }

            if (!empty($value)) {
                if (strpos($rule, 'email') !== false && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = "The $field must be a valid email address.";
                }

                if (strpos($rule, 'numeric') !== false && !is_numeric($value)) {
                    $errors[$field] = "The $field must be a number.";
                }
            }

            $data[$field] = $value;
        }

        if (!empty($errors)) {
            $this->json(['errors' => $errors], 422);
            exit;
        }

        return $data;
    }

    /**
     * Return success response
     */
    protected function success($data = null, string $message = 'Success', int $statusCode = 200): void
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];

        $this->json($response, $statusCode);
    }

    /**
     * Return error response
     */
    protected function error(string $message = 'Error', $data = null, int $statusCode = 400): void
    {
        $response = [
            'success' => false,
            'message' => $message,
            'data' => $data
        ];

        $this->json($response, $statusCode);
    }

    /**
     * Return 404 response
     */
    protected function notFound(string $message = 'Resource not found'): void
    {
        $this->error($message, null, 404);
    }

    /**
     * Alternative: Method untuk child class yang tidak butuh parameter
     */
    protected function showNotFound(): void
    {
        $this->notFound('Halaman yang Anda cari tidak ditemukan.');
    }

    /**
     * Get route parameters
     */
    protected function params(): array
    {
        return $this->request->getParams();
    }

    /**
     * Get specific route parameter
     */
    protected function param(string $key, $default = null)
    {
        $params = $this->request->getParams();
        return $params[$key] ?? $default;
    }
}