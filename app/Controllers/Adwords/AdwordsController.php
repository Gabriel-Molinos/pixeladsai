<?php

namespace App\Controllers\Adwords;
use App\Services\AdwordsService;

class AdwordsController {

    public function get(){
        $a = new AdwordsService();
        $a->getAccessToken();
        $a->getCampaigns();
    }

}