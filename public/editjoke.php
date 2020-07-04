<?php

include __DIR__ . '/../includes/DatabaseConnection.php';
include __DIR__ . '/../classes/DatabaseTable.php';

    $jokesTable = new DatabaseTable($db, 'jokes', 'id');

try {
    if (isset($_POST['joke'])) {

        $joke = $_POST['joke'];
        $joke['jokedate'] = new DateTime();
        $joke['authorId'] =1;

        $jokesTable->save($joke);

        header("Location: jokes.php");
    } else {
        if(isset($_GET['id'])){
            $joke = $joke->findById($_GET['id']);
        }

        $title = 'Edit joketext';

        ob_start();

        include __DIR__ . '/../templates/editjoke.html.php';

        $output = ob_get_clean();
    }
} catch (PDOException $e) {
    $title = 'An error has occured';

    $output = 'Database error: ' . $e->getMessage() . 'in ' . $e->getFile() . ':' . $e->getLine();
}

include __DIR__ . '/../templates/layout.html.php';
