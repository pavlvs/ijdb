<?php
namespace Ijdb\Entity;

use Ninja\DatabaseTable;

class Category
{
    public $id;
    public $name;
    private $jokesTable;
    private $jokesCategoriesTable;

    public function __construct(DatabaseTable $jokesTable, DatabaseTable $jokesCategoriesTable)
    {
        $this->jokesTable           = $jokesTable;
        $this->jokesCategoriesTable = $jokesCategoriesTable;
    }

    public function getJokes()
    {
        $jokeCategories = $this->jokesCategoriesTable->find('categoryId', $this->id);

        $jokes = [];

        foreach ($jokeCategories as $jokeCategory) {
            $joke = $this->jokesTable->findById($jokeCategory->jokeId);
            if ($joke) {
                $jokes[] = $joke;
            }
        }

        return $jokes;
    }
}
