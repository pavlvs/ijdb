<?php
namespace Ijdb;

class IjdbRoutes
{
    public function callAction($route)
    {
        include __DIR__ . '/../../includes/DatabaseConnection.php';

        $jokesTable   = new \Ninja\DatabaseTable($db, 'jokes', 'id');
        $authorsTable = new \Ninja\DatabaseTable($db, 'authors', 'id');

        if ($route === 'joke/list') {
            $controller = new \Ijdb\Controllers\Joke($jokesTable, $authorsTable);
            $page       = $controller->list();
        } elseif ($route === '') {
            $controller = new \Ijdb\Controllers\Joke($jokesTable, $authorsTable);
            $page       = $controller->home();
        } elseif ($route === 'joke/edit') {
            $controller = new \Ijdb\Controllers\Joke($jokesTable, $authorsTable);
            $page       = $controller->edit();
        } elseif ($route === 'joke/delete') {
            $controller = new \Ijdb\Controllers\Joke($jokesTable, $authorsTable);
            $page       = $controller->delete();
        } elseif ($route === 'register') {
            $controller = new \Ijdb\Controllers\Register($authorsTable);
            $page       = $controller->showForm();
        }

        return $page;
    }

}
