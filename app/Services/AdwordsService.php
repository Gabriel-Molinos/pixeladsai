<?php

namespace App\Services;


class AdwordsService
{
    
    private $credentials = [];
    private $OauthUri = 'https://oauth2.googleapis.com/token';
    private $accessToken = null;
    private $apiVersion = 'v24';
    private $requestUri = 'https://googleads.googleapis.com/';
    private $CustomerPath = '/customers:listAccessibleCustomers';

    public function __construct()
    {
        $this->credentials = [
            'clientId' => $_ENV['CLIENT_ID'],
            'clientSecret' => $_ENV['CLIENT_SECRET'],
            'redirectUri' => $_ENV['REDIRECT_URI'],
        ];
    }

    // ==============================
    
    // 1. Cria a url de Auth
    public function getGoogleAuthUrl()
    {
        $params = [
            'client_id' => $this->credentials['clientId'],
            'redirect_uri' => $this->credentials['redirectUri'],
            'response_type' => 'code',
            'scope' => 'https://www.googleapis.com/auth/adwords openid email profile',
            'access_type' => 'offline',
            'prompt' => 'consent'
        ];

        return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
    }

    // 2. CALLBACK (troca code por token)
    public function handleGoogleCallback($code)
    {
        $data = [
            'client_id' => $this->credentials['clientId'],
            'client_secret' => $this->credentials['clientSecret'],
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->credentials['redirectUri'],
        ];

        $ch = curl_init($this->OauthUri);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            die("Erro OAuth: " . curl_error($ch));
        }

        $result = json_decode($response, true);

        if (!isset($result['access_token'])) {
            die("Erro login Google: " . $response);
        }

        $this->accessToken = $result['access_token'];

        return $result;
    }

    public function getAccessToken($refreshToken)
    {
        $url = 'https://oauth2.googleapis.com/token';

        $data = [
            'client_id'     => $this->credentials['clientId'],
            'client_secret' => $this->credentials['clientSecret'],
            'refresh_token' => $refreshToken,
            'grant_type'    => 'refresh_token',
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $response = curl_exec($ch);
        $error = curl_error($ch);


        if ($error) {
            return "Erro no cURL: " . $error;
        }

        $result = json_decode($response, true);

        if (isset($result['access_token'])) {
            return $result['access_token'];
        } else {
            return "Erro ao obter Access Token: " . ($result['error_description'] ?? 'Erro desconhecido');
        }
    }

    // 3. Pega os customers ao qual essa conta tem acesso.
    public function listCustomer($token)
    {
        $apiVersion = 'v24';
        $uri = "https://googleads.googleapis.com/{$apiVersion}/customers:listAccessibleCustomers";

        $headers = [
            'Authorization: Bearer ' . $token,
            'developer-token: ' . $_ENV['DEVELOPER_TOKEN'],
            'Content-Type: application/json'
        ];
        $ch = curl_init($uri);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                return "Erro na API ({$httpCode}): " . $response;
            }

        return json_decode($response, true)['resourceNames'];
    }
}
