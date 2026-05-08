<?php

namespace App\Controllers\Auth;

use App\Services\AdwordsService;

class LoginController
{

    public function index()
    {
        $title = 'Pixel Ads AI - Login';
        return view('index', compact('title'), 'auth');
    }

    public function post()
    {
        $service = new AdwordsService();
        header("Location: " . $service->getGoogleAuthUrl());
        exit;
    }

    public function callback()
    {
        $service = new AdwordsService();

        if (!isset($_GET['code'])) {
            die("Code não enviado");
        }

        $result = $service->handleGoogleCallback($_GET['code']);
        $this->formatSession($result);
        $refreshToken = $_SESSION['google_ads']['refresh_token'];
        $accessToken = $service->getAccessToken($refreshToken);
        $data = $service->listCustomer($accessToken);
        $_SESSION['accounts'] = $this->cleanData($data);
        $_SESSION['qtd'] = count($data); 

        header("Location: /dashboard");
        exit;
    }

    private function formatSession($result)
    {
        $idToken = $result['id_token'];

        $parts = explode('.', $idToken);

        $payload = json_decode(
            base64_decode($parts[1]),
            true
        );

        $_SESSION['user'] = [
            'google_email' => $payload['email'],
            'google_name'  => $payload['name'],
            'google_id'    => $payload['sub']
        ];

        $_SESSION['google_ads'] = [
            'refresh_token' => $result['refresh_token']
        ];
    }

    private function cleanData($data) {
        foreach($data  as $k => $value){
            $data[$k] = str_replace('customers/','',$value); 
        }

        return $data;
    }
}
