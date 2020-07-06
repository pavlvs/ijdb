<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/joke.css">
    <title><?=$title?></title>
</head>

<body>
    <header>
        <h1>Internet Joke Database</h1>
        <nav>
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/joke/list">Jokes List</a></li>
                <li><a href="/joke/edit">Add a new joke</a></li>

    <?php if ($loggedIn): ?>
        <li><a href="/logout">Log out</a></li>
    <?php else: ?>
        <li><a href="/login">Log in</a></li>
    <?php endif;?>

            </ul>
        </nav>
    </header>
    <main>
        <?=$output?>
    </main>
    <footer>
        &copy; <?php echo date('Y'); ?> IJDB All Rights Reserved.
    </footer>
</body>

</html>