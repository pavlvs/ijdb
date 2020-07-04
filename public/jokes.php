<?php

try {
    include __DIR__ . '/../includes/DatabaseConnection.php';
    include __DIR__ . '/../classes/DatabaseTable.php';

    $jokesTable = new DatabaseTable($db, 'jokes', 'id');
    $authorsTable = new DatabaseTable($db, 'authors', 'id');

    $result = $jokesTable->findAll();

    $jokes = [];

    foreach ($result as $joke) {
        $author = $authorsTable->findById($joke['authorId']);

        $jokes[] = [
            'id' => $joke['id'],
            'joketext' => $joke['joketext'],
            'jokedate' => $joke['jokedate'],
            'name' => $author['name'],
            'email' => $author['email']
        ];
    }
    $title = 'Jokes list';

    $totalJokes = $jokesTable->total();

    // Start the buffer

    ob_start();

    // Include the template. The PHP code will be executed, but the resulting HTML will be stored in the buffer rather than sent to the browser.

    include __DIR__ . '/../templates/jokes.html.php';

    // Read the contents of the buffer and store them in the $output variable for use in layout.html.php

    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'An error has occurred';

    $error = 'Database error: ' . $e->getMessage() . 'in ' . $e->getFile() . ':' . $e->getLine();
}

include __DIR__ . '/../templates/layout.html.php';
