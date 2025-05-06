<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use QuickDeployer\SDK\Client;

$apiKey = '3d4d3b5df65eb049497ce1dae3d21d7121f4eb52cf8981e2b5d0899ea2adcdd8';
$client = new Client($apiKey);

try {
    // Test Projects
    $projects = $client->getProjects();
    echo "Projects:\n";
    print_r($projects);

    // Test Servers
    $projectId = '1';
    $servers = $client->servers($projectId)->list();
    echo "Servers:\n";
    print_r($servers);

    // Test Server Status
    $serverId = '1';
    $status = $client->servers($projectId)->checkStatus($serverId);
    echo "Server Status:\n";
    print_r($status);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}