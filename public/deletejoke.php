<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=ijdb;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'DELETE FROM jokes WHERE id = :id';

    $stmt = $db->prepare($sql);

    $stmt->bindValue(':id', $_POST['id']);

    $stmt->execute();

    header("Location: jokes.php");
} catch (PDOException $e) {
    $title = 'An error has occurred';

    $output = 'Database error: ' . $e->getMessage() . 'in ' . $e->getFile() . ':' . $e->getLine();
}
