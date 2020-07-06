<?php
namespace Ijdb;

class IjdbRoutes implements \Ninja\Routes
{
    private $authorsTable;
    private $jokesTable;
    private $authentication;

    public function __construct()
    {
        include __DIR__ . '/../../includes/DatabaseConnection.php';

        $this->jokesTable     = new \Ninja\DatabaseTable($db, 'jokes', 'id');
        $this->authorsTable   = new \Ninja\DatabaseTable($db, 'authors', 'id');
        $this->authentication = new \Ninja\Authentication($this->authorsTable, 'email', 'password');
    }

    public function getRoutes(): array
    {
        $jokeController   = new \Ijdb\Controllers\Joke($this->jokesTable, $this->authorsTable);
        $authorController = new \Ijdb\Controllers\Register($this->authorsTable);
        $loginController  = new \Ijdb\Controllers\Login($this->authentication);

        $routes = [
            'author/register' => [
                'GET'  => [
                    'controller' => $authorController,
                    'action'     => 'registrationForm',
                ],
                'POST' => [
                    'controller' => $authorController,
                    'action'     => 'registerUser',
                ],
            ],
            'author/success'  => [
                'GET' => [
                    'controller' => $authorController,
                    'action'     => 'success',
                ],
            ],
            'joke/edit'       => [
                'POST'  => [
                    'controller' => $jokeController,
                    'action'     => 'saveEdit',
                ],
                'GET'   => [
                    'controller' => $jokeController,
                    'action'     => 'edit',
                ],
                'login' => true,
            ],
            'joke/delete'     => [
                'POST'  => [
                    'controller' => $jokeController,
                    'action'     => 'delete',
                ],
                'login' => true,
            ],
            'joke/list'       => [
                'GET' => [
                    'controller' => $jokeController,
                    'action'     => 'list',
                ],
            ],
            ''                => [
                'GET' => [
                    'controller' => $jokeController,
                    'action'     => 'home',
                ],
            ],
            'login/error'     => [
                'GET' => [
                    'controller' => $loginController,
                    'action'     => 'error',
                ],
            ],
            'login'           => [
                'GET'  => [
                    'controller' => $loginController,
                    'action'     => 'loginForm',
                ],
                'POST' => [
                    'controller' => $loginController,
                    'action'     => 'processLogin',
                ],
            ],
            'login/success'   => [
                'GET'   => [
                    'controller' => $loginController,
                    'action'     => 'success',
                ],
                'login' => true,
            ],
            'logout'          => [
                'GET' => [
                    'controller' => $loginController,
                    'action'     => 'logout',
                ],
            ],
        ];

        return $routes;

    }

    public function getAuthentication(): \Ninja\Authentication
    {
        return $this->authentication;
    }
}
