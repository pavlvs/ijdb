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

function insertJoke($db, $fields) {
    $sql = 'INSERT INTO jokes (';

    foreach ($fields as $key => $value) {
        $sql .= '`' . $key . '`,';
    }
    $sql = rtrim($sql, ',');

    $sql .= ') VALUES (';

    foreach ($fields as $key => $value) {
        $sql .= ':' . $key . ',';
    }

    $sql = rtrim($sql, ',');

    $sql .= ')';

    $fields = processDates($fields);

    query($db, $sql, $fields);
}

function updateJoke($db, $fields) {
    $sql = 'UPDATE jokes SET ';

    foreach ($fields as $key => $value) {
        $sql .= '`' . $key . '`' . ' = :'  . $key . ',';
    }
    $sql = rtrim($sql, ',');
    $sql .= ' WHERE `id` = :primaryKey';

    $fields = processDates($fields);

    $fields['primaryKey'] = $fields['$id'];

    query($db, $sql, $fields);
}

function deleteJoke($db, $id) {
    $parameters = [':id' => $id];

    query($db, 'DELETE FROM jokes WHERE id = :id', $parameters);
}

function allJokes($db) {
    $jokes = query($db, 'SELECT jokes.id, joketext, jokedate, `name`, email
        FROM jokes
        INNER JOIN authors
        ON authorId = authors.id');

    return $jokes->fetchAll();
}

function processDates($fields) {
    foreach ($fields as $key => $value) {
        if ($value instanceof DateTime) {
            $fields[$key] = $value->format('Y-m-d');
        }
    }

    return $fields;
}

function allAuthors($db) {
    $authors = query($db, 'SELECT * FROM authors');

    return $authors->fetchAll();
}

function deleteAuthor($db, $id) {
    $parameters = [':id' => $id];

    query($db, 'DELETE FROM authors WHERE id = :id', $parameters);
}

function insertAuthor($db, $fields) {
    $sql = 'INSERT INTO authors (';

    foreach ($fields as $key => $value) {
        $sql .= '`' . $key . '`,';
    }

    $sql = rtrim($sql, ',');

    $sql .= ') VALUES (';


    foreach ($fields as $key => $value) {
        $sql .= ':' . $key . ',';
    }


    $sql = rtrim($sql, ',');

    $sql .= ')';

    $fields = processDates($fields);

    query($db, $sql, $fields);
}

function findAll($db, $table) {
    $result = query($db, 'SELECT * FROM `' . $table . '`');

    return $result->fetchAll();
}

function delete($db, $table, $primaryKey, $id) {
    $parameters = [':id' => $id];

    query($db, 'DELETE FROM `' . $table . '` WHERE `' . $primaryKey . '` = :id', $parameters);
}

function insert($db, $table, $fields) {
    $sql = 'INSERT INTO `' . $table . '` (';

    foreach ($fields as $key => $value) {
        $sql .= '`' . $key . '`,';
    }

    $sql = rtrim($sql, ',');

    $sql .= ') VALUES (';

    foreach ($fields as $key => $value) {
        $sql .= ':' . $key . ',';
    }

    $sql = rtrim($sql, ',');

    $sql .= ')';

    $fields = processDates($fields);

    query($db, $sql, $fields);
}

function update($pdo, $table, $primaryKey, $fields) {
    $sql = ' UPDATE `' . $table . '` SET ';

    foreach ($fields as $key => $value) {
        $sql .= '`' . $key . '` = :' . $key . ',';
    }

    $sql = rtrim($sql, ',');

    $sql .= ' WHERE `' . $primaryKey . '` = :primaryKey';

    // Set the :primaryKey variable
    $fields['primaryKey'] = $fields['id'];

    $fields = processDates($fields);

    query($pdo, $sql, $fields);
}

function findById($db, $table, $primaryKey, $value) {
    $sql = 'SELECT * FROM `' . $table . '` WHERE `' . $primaryKey . '` = :value';

    $parameters = ['value' => $value];

    $stmt = query($db, $sql, $parameters);

    return $stmt->fetch();
}

function total($db, $table) {
    $stmt = query($db, 'SELECT COUNT(*) FROM `' . $table . '`');
    $row = $stmt->fetch();
    return $row[0];
}

function save($db, $table, $primaryKey, $record) {
    try {
        if ($record[$primaryKey] == '') {
            $record[$primaryKey] = null;
        }
        insert($db, $table, $record);
    } catch (PDOException $e) {
        update($db, $table, $primaryKey, $record);
    }
}
