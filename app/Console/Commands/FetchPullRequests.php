<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GitHubService;

class FetchPullRequests extends Command
{
    protected $signature = 'fetch:pullrequests';
    protected $description = 'Fetch pull requests from GitHub';

    protected $githubService;
    
    public function __construct(GitHubService $githubService)
    {
        parent::__construct();
        $this->githubService = $githubService;
    }
    
    public function handle()
    {
        $this->fetchAndSave('Old Pull Requests', '1-old-pull-requests.txt', [$this->githubService, 'getOldPullRequests']);
    }

    protected function fetchAndSave($title, $filename, $fetchMethod)
    {
        $this->info("Fetching $title...");
        $pullRequests = call_user_func($fetchMethod);

        if ($pullRequests && isset($pullRequests['items'])) {
            $data = array_map(function ($pr) {
                return [
                    'PR#' => $pr['number'],
                    'Title' => $pr['title'],
                    'URL' => $pr['html_url'],
                ];
            }, $pullRequests['items']);

            $content = array_reduce($data, function ($carry, $item) {
                return $carry . "PR# {$item['PR#']}, Title: {$item['Title']}, URL: {$item['URL']}\n";
            }, '');

            file_put_contents(storage_path("app/$filename"), $content);
            $this->info("Saved $title to $filename");
        } else {
            $this->warn("No data found for $title");
        }
    }
}