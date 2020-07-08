<?php

namespace Ijdb\Controllers;

class Category
{
    private $categoriesTable;

    public function __construct(\Ninja\DatabaseTable $categoriesTable)
    {
        $this->categoriesTable = $categoriesTable;
    }

    function list() {
        $categories = $this->categoriesTable->findAll();

        $title = 'Joke categoriesTable';

        return [
            'template'  => 'categories.html.php',
            'title'     => $title,
            'variables' => [
                'categories' => $categories,
            ],
        ];
    }

    public function edit()
    {
        if (isset($_GET['id'])) {
            $category = $this->categoriesTable->findById($_GET['id']);
        }

        $title = 'Edit Category';

        return [
            'template'  => 'editcategory.html.php',
            'title'     => $title,
            'variables' => [
                'category' => $category ?? null,
            ],
        ];
    }

    public function saveEdit()
    {
        $category = $_POST['category'];

        $this->categoriesTable->save($category);

        header('Location: /category/list');
    }

    public function delete()
    {
        $this->categoriesTable->delete($_POST['id']);

        header('location: /category/list');
    }
}
