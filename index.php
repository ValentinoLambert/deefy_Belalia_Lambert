<?php

declare(strict_types=1);
require_once 'vendor/autoload.php';

use iutnc\deefy\dispatch\Dispatcher;
use iutnc\deefy\db\ConnectionFactory;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

ConnectionFactory::setConfig('conf.ini');

$d = new Dispatcher();
$d->run();