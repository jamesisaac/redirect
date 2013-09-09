<?php

require_once __DIR__.'/../vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Bootstrap the application
$app = new Silex\Application();
// $app['debug'] = true;

// Create a database connection
$conn = DriverManager::getConnection(array(
    'dbname' => 'redirect',
    'user' => 'root',
    'driver' => 'pdo_mysql'
));

// Set up the main route
$app->get('/{path}', function (Application $app, Request $request, $path) use ($conn) {
    // Get the namespace and parts
    $parts = explode('/', $path);
    $slug = array_pop($parts);
    $namespace = count($parts) ? $parts[0] : null;

    // Fetch the link from the db
    $sql = 'SELECT * FROM link WHERE slug = :slug ' .
        ($namespace ? 'AND namespace = :namespace ' : 'AND namespace IS NULL ');
    $stmt = $conn->prepare($sql);
    $stmt->bindValue('slug', $slug);
    if ($namespace) {
        $stmt->bindValue('namespace', $namespace);
    }
    $stmt->execute();
    $link = $stmt->fetch();

    // 404 if not found
    if (!$link) {
        return $app->abort(404, 'Could not find link');
    }

    // Record the hit
    $conn->insert('hit', array(
        'link_id' => $link['id'],
        'visited_at' => date('Y-m-d H:i:s'),
        'ip' => $request->getClientIp(),
        'ua' => $request->server->get('HTTP_USER_AGENT'),
        'referer' => $request->headers->get('referer'),
    ));

    // Moved permanently
    return $app->redirect($link['url'], 301);
})
->assert('path', '[A-Za-z0-9\-]+(/[A-Za-z0-9\-]+)?');

$app->run();