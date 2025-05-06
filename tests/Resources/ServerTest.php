<?php

declare(strict_types=1);

namespace NiravSutariya\QuickDeployer\Tests\Resources;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use NiravSutariya\QuickDeployer\Resources\Server;
use PHPUnit\Framework\TestCase;

class ServerTest extends TestCase
{
    private Client $client;
    private MockHandler $mockHandler;
    private string $projectId = 'project-123';

    protected function setUp(): void
    {
        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $this->client = new Client(['handler' => $handlerStack]);
    }

    public function testCanListServers(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'servers' => [
                ['id' => 'server-456', 'name' => 'Test Server']
            ]
        ])));

        $serverResource = new Server($this->client, $this->projectId);
        $result = $serverResource->list();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('servers', $result);
        $this->assertEquals('server-456', $result['servers'][0]['id']);
    }

    public function testCanCheckServerStatus(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'id' => 'server-456',
            'status' => 'online'
        ])));

        $serverResource = new Server($this->client, $this->projectId);
        $result = $serverResource->checkStatus('server-456');

        $this->assertIsArray($result);
        $this->assertEquals('online', $result['status']);
    }

    public function testThrowsExceptionOnStatusFailure(): void
    {
        $this->mockHandler->append(new Response(404, [], 'Not Found'));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Failed to check server status');

        $serverResource = new Server($this->client, $this->projectId);
        $serverResource->checkStatus('server-456');
    }
}