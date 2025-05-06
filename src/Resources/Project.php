<?php

declare(strict_types=1);

namespace QuickDeployer\Resources;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Project
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function list(): array
    {
        try {
            $response = $this->client->get('projects');
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw new \RuntimeException('Failed to list projects: ' . $e->getMessage());
        }
    }

    public function get(string $projectId): array
    {
        try {
            $response = $this->client->get("projects/{$projectId}");
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw new \RuntimeException('Failed to get project: ' . $e->getMessage());
        }
    }

    public function create(array $data): array
    {
        try {
            $response = $this->client->post('projects', ['json' => $data]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw new \RuntimeException('Failed to create project: ' . $e->getMessage());
        }
    }

    public function update(string $projectId, array $data): array
    {
        try {
            $response = $this->client->put("projects/{$projectId}", ['json' => $data]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw new \RuntimeException('Failed to update project: ' . $e->getMessage());
        }
    }

    public function delete(string $projectId): bool
    {
        try {
            $this->client->delete("projects/{$projectId}");
            return true;
        } catch (GuzzleException $e) {
            throw new \RuntimeException('Failed to delete project: ' . $e->getMessage());
        }
    }
}