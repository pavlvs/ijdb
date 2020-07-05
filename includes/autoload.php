<?php
function autoloader($className) {
    $file = __DIR__ . '/../classes/' . $className . '.<?php ?>';
    include $file;
}