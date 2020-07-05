<?php
try {
    include __DIR__ . '/../includes/autoload.php';

    //if no route variable is set, use 'joke/home'
    $route = ltrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');

    $entryPoint = new \Ninja\EntryPoint($route, new \Ijdb\IjdbRoutes());
    $entryPoint->run();
} catch (PDOException $e) {
    $title = 'An error has occurred';

    $output = 'Database error: ' . $e->getMessage() . 'in ' . $e->getFile() . ':' . $e->getLine();

    include __DIR__ . '/../templates/layout.html.php';
}
