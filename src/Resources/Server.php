<?php

declare(strict_types=1);

namespace QuickDeployer\Resources;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Server
{
    private Client $client;
    private string $projectId;

    public function __construct(Client $client, string $projectId)
    {
        $this->client = $client;
        $this->projectId = $projectId;
    }

    public function list(): array
    {
        try {
            $response = $this->client->get("projects/{$this->projectId}/servers");
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw new \RuntimeException('Failed to list servers: ' . $e->getMessage());
        }
    }

    public function get(string $serverId): array
    {
        try {
            $response = $this->client->get("projects/{$this->projectId}/servers/{$serverId}");
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw new \RuntimeException('Failed to get server: ' . $e->getMessage());
        }
    }

    public function create(array $data): array
    {
        try {
            $response = $this->client->post("projects/{$this->projectId}/servers", ['json' => $data]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw new \RuntimeException('Failed to create server: ' . $e->getMessage());
        }
    }

    public function update(string $serverId, array $data): array
    {
        try {
            $response = $this->client->put("projects/{$this->projectId}/servers/{$serverId}", ['json' => $data]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw new \RuntimeException('Failed to update server: ' . $e->getMessage());
        }
    }

    public function delete(string $serverId): bool
    {
        try {
            $this->client->delete("projects/{$this->projectId}/servers/{$serverId}");
            return true;
        } catch (GuzzleException $e) {
            throw new \RuntimeException('Failed to delete server: ' . $e->getMessage());
        }
    }

    public function checkStatus(string $serverId): array
    {
        try {
            $response = $this->client->get("projects/{$this->projectId}/servers/{$serverId}/status");
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw new \RuntimeException('Failed to check server status: ' . $e->getMessage());
        }
    }
}