<?php
declare(strict_types=1);

// Get the constants needed
require __DIR__ . '/env.php';
loadEnv(__DIR__ . '/.env');

$g_maps_key = $_ENV['GMAPS_API_KEY'];
$debug      = $_ENV['APP_DEBUG'];

// load the database

/*
|--------------------------------------------------------------------------
| Fake local user
|--------------------------------------------------------------------------
*/
$user = [
    'id' => 1,
    'username' => 'localuser',
    'display_name' => 'Local User',
    'email' => 'local@example.com',
];

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/
function abort404(): void
{
    http_response_code(404);
    $pageTitle = '404 Not Found';

    // if (file_exists(__DIR__ . '/header.php')) {
    //     require __DIR__ . '/header.php';
    // }

    echo '<main>';
    echo '<h2>404 - Page not found</h2>';
    echo '</main>';

    // if (file_exists(__DIR__ . '/footer.php')) {
    //     require __DIR__ . '/footer.php';
    // }

    exit;
}

function render(string $template, array $data = []): void
{
    global $user;

    $templatePath = __DIR__ . '/' . $template . '.php';

    if (!file_exists($templatePath)) {
        abort404();
    }
    extract($data, EXTR_SKIP);

    require __DIR__ . '/header.php';
    
    require $templatePath;

    require __DIR__ . '/footer.php';

    exit;
}

/*
|--------------------------------------------------------------------------
| Current route
|--------------------------------------------------------------------------
*/
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$route = trim($uri, '/');

if ($route === '') {
    $route = 'home';
}

$route = preg_replace('#/+#', '/', $route);
$route = trim($route, '/');

if (!preg_match('#^[a-zA-Z0-9/_-]+$#', $route)) {
    abort404();
}

/*
|--------------------------------------------------------------------------
| Route table
|--------------------------------------------------------------------------
*/
$routes = [
    'home' => function () {
        render('home', ['pageTitle' => 'Home']);
    },

    'projects' => function () {
        render('projects/list', ['pageTitle' => 'Projects List']);
    },

    'projects/{id}' => function ($id) {
        render('projects/edit', [
            'pageTitle' => 'Blog Post',
            'id' => $id,
        ]);
    },

    'assets' => function () {
        render('assets', ['pageTitle' => 'Manage Assets']);
    },

    'user/{id}' => function ($id) {
        render('user', [
            'pageTitle' => 'User',
            'userIdFromRoute' => $id,
        ]);
    },
];

foreach ($routes as $pattern => $handler) {
    $regex = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '([^/]+)', $pattern);
    $regex = '#^' . $regex . '$#';

    if (preg_match($regex, $route, $matches)) {
        array_shift($matches);
        $handler(...$matches);
    }
}

/*
|--------------------------------------------------------------------------
| File fallback
|--------------------------------------------------------------------------
*/
$templatePath = __DIR__ . '/' . $route . '.php';

if (file_exists($templatePath)) {
    render($route, [
        'pageTitle' => ucwords(str_replace(['-', '/'], [' ', ' / '], $route)),
    ]);
}

abort404();

?>

    

    