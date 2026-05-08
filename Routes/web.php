<?php

use App\Core\Route;
use App\Controllers\Auth\LoginController;
use App\Controllers\Adwords\AdwordsController;
use App\Controllers\Dashboard\DashboardController;

$r = new Route();

$r->get('/', [LoginController::class, 'index']);
$r->get('/auth/google',[LoginController::class, 'post']);
$r->get('/auth/google/callback',[LoginController::class, 'callback']);
$r->get('/dashboard',[DashboardController::class, 'index']);


$r->run();