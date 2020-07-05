<?php
class IjdbRoutes
{
    public function callAction($route)
    {
        include __DIR__ . '/../classes/DatabaseTable.php';
        include __DIR__ . '/../includes/DatabaseConnection.php';

        $jokesTable   = new DatabaseTable($db, 'jokes', 'id');
        $authorsTable = new DatabaseTable($db, 'authors', 'id');

        if ($route === 'joke/list') {
            include __DIR__ . '/../classes/controllers/JokeController.php';
            $controller = new JokeController($jokesTable, $authorsTable);
            $page       = $controller->list();
        } elseif ($route === '') {
            include __DIR__ . '/../classes/controllers/JokeController.php';
            $controller = new JokeController($jokesTable, $authorsTable);
            $page       = $controller->home();
        } elseif ($route === 'joke/edit') {
            include __DIR__ . '/../classes/controllers/JokeController.php';
            $controller = new JokeController($jokesTable, $authorsTable);
            $page       = $controller->edit();
        } elseif ($route === 'joke/delete') {
            include __DIR__ . '/../classes/controllers/JokeController.php';
            $controller = new JokeController($jokesTable, $authorsTable);
            $page       = $controller->delete();
        } elseif ($route === 'register') {
            include __DIR__ . '/../classes/controllers/RegisterController.php';
            $controller = new RegisterController($authorsTable);
            $page       = $controller->showForm();
        }

        return $page;
    }

}