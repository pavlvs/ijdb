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

    public function __construct(\Ninja\DatabaseTable $authorsTable)
    {
        $this->authorstable = $authorsTable;
    }

    public function getAuthor()
    {
        if (empty($this->author)) {
            $this->author = $this->authorstable->findById($this->authorId);
        }
        return $this->author;
    }
}
