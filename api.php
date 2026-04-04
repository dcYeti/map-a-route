<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/classes/Projects.php';

class ApiRouter
{
    protected array $map = [
        'projects_get_projects'   => [Projects::class, 'getProjects'],
        'projects_edit_project'    => [Projects::class, 'editProject'],
        'projects_save_project'    => [Projects::class, 'saveProject'],
        'projects_delete_project'  => [Projects::class, 'deleteProject'],
    ];

    public function handle(): void
    {
        try {
            $this->enforcePost();
            $this->enforceSameOrigin();

            $action = $_POST['system_action'] ?? '';

            if (!$action) {
                $this->jsonResponse([
                    'success' => false,
                    'error'   => 'Missing system_action',
                ], 400);
            }

            if (!isset($this->map[$action])) {
                $this->jsonResponse([
                    'success' => false,
                    'error'   => 'Invalid system_action',
                ], 400);
            }

            [$className, $method] = $this->map[$action];

            $handler = new $className();

            if (!method_exists($handler, $method)) {
                $this->jsonResponse([
                    'success' => false,
                    'error'   => 'Handler method not found',
                ], 500);
            }

            $result = $handler->$method($_POST);

            $this->jsonResponse([
                'success' => true,
                'data'    => $result,
            ]);
        } catch (Throwable $e) {
            $this->jsonResponse([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    protected function enforcePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse([
                'success' => false,
                'error'   => 'Only POST requests are allowed',
            ], 405);
        }
    }

    protected function enforceSameOrigin(): void
    {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if ($host === '') {
            $this->jsonResponse([
                'success' => false,
                'error'   => 'Unable to determine host',
            ], 400);
        }

        $origin  = $_SERVER['HTTP_ORIGIN'] ?? '';
        $referer = $_SERVER['HTTP_REFERER'] ?? '';

        if ($origin !== '') {
            $originHost = parse_url($origin, PHP_URL_HOST);
            if ($originHost !== $host) {
                $this->jsonResponse([
                    'success' => false,
                    'error'   => 'Cross-domain requests are not allowed',
                ], 403);
            }
            return;
        }

        if ($referer !== '') {
            $refererHost = parse_url($referer, PHP_URL_HOST);
            if ($refererHost !== $host) {
                $this->jsonResponse([
                    'success' => false,
                    'error'   => 'Cross-domain requests are not allowed',
                ], 403);
            }
            return;
        }

        // If neither Origin nor Referer exists, reject it
        $this->jsonResponse([
            'success' => false,
            'error'   => 'Missing origin information',
        ], 403);
    }

    protected function jsonResponse(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}

$router = new ApiRouter();
$router->handle();