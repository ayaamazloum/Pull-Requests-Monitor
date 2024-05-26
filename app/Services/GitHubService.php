<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GitHubService
{
    protected $client;
    protected $baseUri = 'https://api.github.com/';
    protected $repo = 'woocommerce/woocommerce';

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->baseUri,
            'headers' => [
                'Authorization' => 'Bearer ' . env('GITHUB_TOKEN'),
                'Accept' => 'application/vnd.github+json',
            ]
        ]);
    }
}
