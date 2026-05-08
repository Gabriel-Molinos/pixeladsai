<?php

namespace Cron\Services;

class AdwordsCronService
{

    private $credentials = [];
    private $requestUri = 'https://googleads.googleapis.com/';
    private $apiVersion = 'v24';

    public function __construct()
    {
        $this->credentials = [
            'clientId' => $_ENV['CLIENT_ID'],
            'clientSecret' => $_ENV['CLIENT_SECRET'],
            'refreshToken' => $_ENV['REFRESH_TOKEN'],
            'type' => $_ENV['TYPE'],
            'developerToken' => $_ENV['DEVELOPER_TOKEN']
        ];
    }

    public function getToken()
    {

        $url = 'https://oauth2.googleapis.com/token';

        $data = [
            'client_id'     => $this->credentials['clientId'],
            'client_secret' => $this->credentials['clientSecret'],
            'refresh_token' => $this->credentials['refreshToken'],
            'grant_type'    => $this->credentials['type'],
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
            $this->credentials['token'] = $result['access_token'];
        } else {
            die("Erro ao obter Access Token: " . ($result['error_description'] ?? 'Erro desconhecido'));
        }
    }

    public function listCustomer()
    {
        $apiVersion = 'v24';
        $uri = "https://googleads.googleapis.com/{$apiVersion}/customers:listAccessibleCustomers";

        $headers = [
            'Authorization: Bearer ' . $this->credentials['token'],
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

    public function getDataCampaign($customers)
    {
        foreach ($customers as $customerId) {
            $url = $this->requestUri . $this->apiVersion . "/{$customerId}:googleAds:search";
            $query = "
                SELECT
                    customer.descriptive_name,
                    campaign.id,
                    campaign.name,
                    campaign.status,
                    metrics.cost_micros,
                    metrics.budget_amount_micros,
                    metrics.clicks,
                    metrics.impressions,
                    metrics.conversions,
                    segments.date
                FROM campaign
                WHERE segments.date BETWEEN '2026-05-06' AND '2026-05-06'
                AND campaign.status = 'ENABLED'
            ";

            $body = json_encode([
                'query' => $query
            ]);

            $headers = [
                'Authorization: Bearer ' . $this->credentials['token'],
                'developer-token: ' . $this->credentials['developerToken'],
                'Content-Type: application/json',
                'login-customer-id: ' . $customerId // importante em MCC
            ];

            $ch = curl_init($url);

            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => $headers,
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($httpCode !== 200) {
                throw new \Exception("Erro Campaign API ({$httpCode}): " . $response);
            }

            dd(json_decode($response, true));
        }
    }
}
