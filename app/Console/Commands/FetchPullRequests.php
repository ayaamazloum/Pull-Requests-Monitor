<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
