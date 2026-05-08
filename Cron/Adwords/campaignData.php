<?php

require __DIR__ . '/../../vendor/autoload.php';

use Cron\Services\AdwordsCronService;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();
$job = new AdwordsCronService();
$job->getToken();

$cusomters = $job->listCustomer();
$job->getDataCampaign($cusomters);