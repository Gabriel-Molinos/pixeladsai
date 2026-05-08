<?php

namespace App\Controllers\Dashboard;

class DashboardController
{

    public function index()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: /");
            exit;
        }

        $title = 'Pixel Ads AI - Dashboard';
        $qtd = $_SESSION['qtd'] ?? 0;
        $accounts = $_SESSION['accounts'];
        return view('dashboard', compact('title','accounts','qtd'), 'auth');
    }
}
