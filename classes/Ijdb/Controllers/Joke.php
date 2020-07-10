<?php
namespace Ijdb\Controllers;

use \Ninja\Authentication;
use \Ninja\DatabaseTable;

class Joke
{
    private $authorsTable;
    private $jokesTable;
    private $categoriesTable;
    private $authentication;

    public function __construct(DatabaseTable $jokesTable,
        DatabaseTable $authorsTable,
        DatabaseTable $categoriesTable,
        Authentication $authentication) {
        $this->jokesTable      = $jokesTable;
        $this->authorsTable    = $authorsTable;
        $this->categoriesTable = $categoriesTable;
        $this->authentication  = $authentication;
    }

    function list() {
        if (isset($_GET['category'])) {
            $category = $this->categoriesTable->findById($_GET['category']);
            $jokes    = $category->getJokes();
        } else {
            $jokes = $this->jokesTable->findAll();
        }

        $title = 'Jokes list';

        $totalJokes = $this->jokesTable->total();

        $author = $this->authentication->getUser();

        return ['template' => 'jokes.html.php',
            'title'            => $title,
            'variables'        => [
                'totalJokes' => $totalJokes,
                'jokes'      => $jokes,
                'user'       => $author, // 'userId'     => $author->id ?? null,
                'categories' => $this->categoriesTable->findAll(),
            ],
        ];
    }

    public function home()
    {
        $title = 'Internet Joke DatabaseTable';

        return ['template' => 'home.html.php', 'title' => $title];
    }

    public function delete()
    {
        $author = $this->authentication->getUser();

        $joke = $this->jokesTable->findById($_POST['id']);

        if ($joke->authorId != $author->id && !$author->hasPermission(\Ijdb\Entity\Author::DELETE_JOKES)) {
            return;
        }

        $this->jokesTable->delete($_POST['id']);

        header("Location: /joke/list");
    }

    public function saveEdit()
    {
        $author = $this->authentication->getUser();

        $joke             = $_POST['joke'];
        $joke['jokedate'] = new \DateTime();

        $jokeEntity = $author->addJoke($joke);

        $jokeEntity->clearCategories();

        foreach ($_POST['category'] as $categoryId) {
            $jokeEntity->addCategory($categoryId);
        }

        header("Location: /joke/list");
    }

    public function edit()
    {
        $author     = $this->authentication->getUser();
        $categories = $this->categoriesTable->findAll();

        if (isset($_GET['id'])) {
            $joke = $this->jokesTable->findById($_GET['id']);
        }

        $title = 'Edit joke';

        return ['template' => 'editjoke.html.php',
            'title'            => $title,
            'variables'        => [
                'joke'       => $joke ?? null,
                'user' => $author, //'userId'     => $author->id ?? null,
                'categories' => $categories,
            ],
        ];
    }

}
