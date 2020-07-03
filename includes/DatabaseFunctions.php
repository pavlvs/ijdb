<?php
include __DIR__ . '/../includes/DatabaseConnection.php';

function totalJokes($db) {
    $stmt = query($db, 'SELECT COUNT(*) FROM jokes');
    $row  = $stmt->fetch();

    return $row[0];
}

function getJoke($db, $id) {
    $parameters = [':id' => $id];
    $stmt = query($db, 'SELECT * FROM `jokes` WHERE `id` = :id', $parameters);
    return $stmt->fetch();
}

function query($db, $sql, $parameters = []) {
    $stmt = $db->prepare($sql);
    $stmt->execute($parameters);
    return $stmt;
}

function insertJoke($db, $joketext, $authorId) {
    $sql = 'INSERT INTO jokes (joketext, jokedate, authorId) VALUES (:joketext, CURDATE(), :authorId)';

    $parameters = [':joketext' => $joketext, ':authorId' => $authorId];

    query($db, $sql, $parameters);
}

function updateJoke($db, $jokeId, $joketext, $authorId) {
    $parameters = [':joketext' => $joketext, ':authorId' => $authorId, ':id' => $jokeId];

    query($db, 'UPDATE jokes SET authorId = :authorId, joketext = :joketext  WHERE id = :id', $parameters);
}

function deleteJoke($db, $id) {
    $parameters = [':id' => $id];

    query($db, 'DELETE FROM jokes WHERE id = :id', $parameters);
}

function allJokes($db) {
    $jokes = query($db, 'SELECT jokes.id, joketext, `name`, email
        FROM jokes
        INNER JOIN authors
        ON authorId = authors.id');

    return $jokes->fetchAll();
}
