<?php

namespace App\Services;

use App\Traits\ConsumeExternalTrait;

class EvaluationService
{
    use ConsumeExternalTrait;

    protected $url;
    protected $token;

    public function __construct() {
        $this->token = config('services.micro-02.token');
        $this->url = config('services.micro-02.url');
    }

    public function getEvaluationsCompany($company = null) {
       $params = !empty($company) ? ['company' => $company] : null;
       $response = $this->request('get', '/evaluations', $params);

       return $response->body();
    }
}
