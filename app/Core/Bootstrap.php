<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();
