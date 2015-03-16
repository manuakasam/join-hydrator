<?php
if (is_file('vendor/autoload.php')) {
    require 'vendor/autoload.php';
} elseif (is_file('../vendor/autoload.php')) {
    require '../vendor/autoload.php';
} else {
    die('No Autoload Configuration Found');
}

date_default_timezone_set('Europe/Berlin');
