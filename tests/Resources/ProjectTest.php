<?php

declare(strict_types=1);

namespace NiravSutariya\QuickDeployer\Tests\Resources;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use NiravSutariya\QuickDeployer\Resources\Project;
use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{
    private Client $client;
    private MockHandler $mockHandler;

    protected function setUp(): void
    {
        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $this->client = new Client(['handler' => $handlerStack]);
    }

    public function testCanListProjects(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'projects' => [
                ['id' => 'project-123', 'name' => 'Test Project']
            ]
        ])));

        $projectResource = new Project($this->client);
        $result = $projectResource->list();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('projects', $result);
        $this->assertEquals('project-123', $result['projects'][0]['id']);
    }

    public function testCanGetProject(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'id' => 'project-123',
            'name' => 'Test Project'
        ])));

        $projectResource = new Project($this->client);
        $result = $projectResource->get('project-123');

        $this->assertIsArray($result);
        $this->assertEquals('project-123', $result['id']);
    }

    public function testThrowsExceptionOnListFailure(): void
    {
        $this->mockHandler->append(new Response(500, [], 'Server Error'));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Failed to list projects');

        $projectResource = new Project($this->client);
        $projectResource->list();
    }
}