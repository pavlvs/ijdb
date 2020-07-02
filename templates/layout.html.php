<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="joke.css">
    <title><?= $title ?></title>
</head>

<body>
    <header>
        <h1>Internet Joke Database</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="jokes.php">Jokes List</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <?= $output ?>
    </main>
    <footer>
        &copy; <?php echo date('Y'); ?> IJDB All Rights Reserved.
    </footer>
</body>

</html>