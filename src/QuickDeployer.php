<?php

declare(strict_types=1);

namespace NiravSutariya\QuickDeployer;

use GuzzleHttp\Client;
use NiravSutariya\QuickDeployer\Resources\Project;
use NiravSutariya\QuickDeployer\Resources\Server;

class QuickDeployer
{
    private Client $client;
    private string $apiKey;

    public function __construct(string $apiKey, string $baseUri = 'https://staging.quickdeployer.com/api')
    {
        $this->apiKey = $apiKey;
        $this->client = new Client([
            'base_uri' => $baseUri,
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function projects(): Project
    {
        return new Project($this->client);
    }

    public function servers(string $projectId): Server
    {
        return new Server($this->client, $projectId);
    }
}