<?php
namespace Ijdb\Entity;

class Joke
{
    public $id;
    public $authorId;
    public $jokedate;
    public $joketext;
    private $authorstable;
    private $author;
    private $jokesCategoriesTable;

    public function __construct(\Ninja\DatabaseTable $authorsTable, \Ninja\DatabaseTable $jokesCategoriesTable)
    {
        $this->authorstable         = $authorsTable;
        $this->jokesCategoriesTable = $jokesCategoriesTable;
    }

    public function getAuthor()
    {
        if (empty($this->author)) {
            $this->author = $this->authorstable->findById($this->authorId);
        }
        return $this->author;
    }

    public function addCategory($categoryId)
    {
        $jokeCat = ['jokeId' => $this->id,
            'categoryId'         => $categoryId];

        $this->jokesCategoriesTable->save($jokeCat);
    }
}
