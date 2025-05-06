<?php

declare(strict_types=1);

namespace QuickDeployer\SDK;

use GuzzleHttp\Client as GuzzleClient;
use QuickDeployer\Resources\Project;
use QuickDeployer\Resources\Server;

class Client
{
    private GuzzleClient $client;
    private string $apiKey;

    public function __construct(string $apiKey, string $baseUri = 'https://staging.quickdeployer.com/api')
    {
        $this->apiKey = $apiKey;
        $this->client = new GuzzleClient([
            'base_uri' => $baseUri,
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function getProjects(): array
    {
        $projectResource = new Project($this->client);
        return $projectResource->list();
    }

    public function servers(string $projectId): Server
    {
        return new Server($this->client, $projectId);
    }
}