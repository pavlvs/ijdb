<?php

include __DIR__ . '/../includes/DatabaseConnection.php';
include __DIR__ . '/../includes/DatabaseFunctions.php';

try {
    if (isset($_POST['joketext'])) {
        updateJoke($db, $_POST['jokeid'], $_POST['joketext'], 1);

        header("Location: jokes.php");
    } else {
        $joke = getJoke($db, $_GET['id']);

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
