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

    public function fetchPullRequests($query)
    {
        Log::info("Fetching PRs with query: $query");
        try {
            $response = $this->client->get("search/issues", [
                'query' => [
                    'q' => $query,
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error("Error fetching pull requests: " . $e->getMessage());
            return null;
        }
    }

    public function getOldPullRequests()
    {
        $query = "repo:{$this->repo} is:pr is:open created:<" . now()->subDays(14)->toDateString();
        return $this->fetchPullRequests($query);
    }

    public function getReviewRequiredPullRequests()
    {
        $query = "repo:{$this->repo} is:pr is:open review:required";
        return $this->fetchPullRequests($query);
    }
}
