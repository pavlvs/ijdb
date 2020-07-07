<?php
namespace Ijdb\Entity;

class Author
{
    public $id;
    public $name;
    public $email;
    private $jokesTable;

    public function __construct(\Ninja\DatabaseTable $jokesTable)
    {
        $this->jokesTable = $jokesTable;
    }

    public function getJokes()
    {
        return $this->jokesTable->find('authorId', $this->id);
    }

    public function addJoke($joke)
    {
        $joke['authorId'] = $this->id;

        $this->jokesTable->save($joke);
    }
}
