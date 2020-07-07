<?php

echo 'foo thinks userId is :' . $userId;
echo 'baz thinks jokeauthorId is: ' . $joke['authorId'];
?>

<?php if ($userId == $joke['authorId']): ?>
    <form action="" method="POST">
        <input type="hidden" name="joke[id]" value="<?=$joke['id'] ?? ''?>">
        <label for="joketext">Type your joke here:</label>

        <textarea name="joke[joketext]" id="joketext" cols="40" rows="3">
            <?=$joke['joketext'] ?? ''?></textarea>

        <input type="submit" value="Save">
    </form>
<?php else: ?>
    <p>You may only edit jokes that you posted.</p>
<?php endif;?>