# PHP QuickDeployer SDK

The QuickDeployer SDK is a PHP library for interacting with the QuickDeployer API, enabling developers to manage projects and servers programmatically. Built with simplicity and modularity in mind, it provides a clean interface for listing, creating, updating, and deleting projects and servers, with robust error handling and testing support.

## Features
- Manage projects (list, get, create, update, delete).
- Manage servers within projects (list, get, create, update, delete, check status).
- Easy integration with PHP applications, including Laravel.
- Comprehensive unit tests using PHPUnit.
- Built with Guzzle HTTP client for reliable API communication.

## Requirements
- PHP ^7.4|^8.0
- Composer
- Guzzle HTTP Client (`guzzlehttp/guzzle: ^7.0`)
- PHPUnit (for running tests)

## Installation

Install the SDK via Composer:

```bash
composer require niravsutariya/quick-deployer-sdk
```

If the SDK is not yet published, you can include it as a local or VCS repository in your `composer.json`:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/niravsutariya/php-quick-deployer-sdk.git"
        }
    ],
    "require": {
        "niravsutariya/quick-deployer-sdk": "dev-main"
    }
}
```

Then run:

```bash
composer install
```

## Usage

### Initializing the Client

Create a `Client` instance with your API key:

```php
use QuickDeployer\SDK\Client;

$apiKey = 'your-api-token';
$client = new Client($apiKey);
```

Optionally, specify a custom base URI (defaults to `https://staging.quickdeployer.com/api`):

```php
$client = new Client($apiKey, 'https://quickdeployer.com/api');
```

### Managing Projects

#### List Projects

Retrieve a list of projects:

```php
$projects = $client->getProjects();
foreach ($projects as $project) {
    echo "Project ID: {$project['id']}, Name: {$project['name']}\n";
}
```

#### Get a Project

Fetch details for a specific project:

```php
$projectResource = new QuickDeployer\Resources\Project($client->getClient());
$project = $projectResource->get('project-123');
echo "Project Name: {$project['name']}\n";
```

#### Create a Project

Create a new project:

```php
$projectResource = new QuickDeployer\Resources\Project($client->getClient());
$newProject = $projectResource->create([
    'name' => 'New Project',
    'description' => 'A test project'
]);
echo "Created Project ID: {$newProject['id']}\n";
```

#### Update a Project

Update an existing project:

```php
$projectResource = new QuickDeployer\Resources\Project($client->getClient());
$updatedProject = $projectResource->update('project-123', [
    'name' => 'Updated Project'
]);
echo "Updated Project Name: {$updatedProject['name']}\n";
```

#### Delete a Project

Delete a project:

```php
$projectResource = new QuickDeployer\Resources\Project($client->getClient());
$projectResource->delete('project-123');
echo "Project deleted successfully\n";
```

### Managing Servers

#### List Servers

Retrieve servers for a specific project:

```php
$servers = $client->servers('project-123')->list();
foreach ($servers['servers'] as $server) {
    echo "Server ID: {$server['id']}, Name: {$server['name']}\n";
}
```

#### Get a Server

Fetch details for a specific server:

```php
$server = $client->servers('project-123')->get('server-456');
echo "Server Name: {$server['name']}\n";
```

#### Create a Server

Create a new server:

```php
$newServer = $client->servers('project-123')->create([
    'name' => 'New Server',
    'type' => 'web'
]);
echo "Created Server ID: {$newServer['id']}\n";
```

#### Update a Server

Update an existing server:

```php
$updatedServer = $client->servers('project-123')->update('server-456', [
    'name' => 'Updated Server'
]);
echo "Updated Server Name: {$updatedServer['name']}\n";
```

#### Delete a Server

Delete a server:

```php
$client->servers('project-123')->delete('server-456');
echo "Server deleted successfully\n";
```

#### Check Server Status

Check the status of a server:

```php
$status = $client->servers('project-123')->checkStatus('server-456');
echo "Server Status: {$status['status']}\n";
```

### Error Handling

All methods throw a `\RuntimeException` on API failures. Use try-catch blocks to handle errors:

```php
try {
    $projects = $client->getProjects();
} catch (\RuntimeException $e) {
    echo "Error: {$e->getMessage()}\n";
}
```

## Configuration

- **API Key**: Obtain your API key from the QuickDeployer dashboard.
- **Base URI**: Override the default `https://quickdeployer.com/api` if using a different environment (e.g., production).

## Testing

The SDK includes unit tests for the `Project` and `Server` resources using PHPUnit.

### Running Tests

1. Install dependencies:

```bash
composer install
```

2. Run tests:

```bash
vendor/bin/phpunit
```

Tests are located in the `tests/Resources` directory (`ProjectTest.php`, `ServerTest.php`) and use Guzzle’s `MockHandler` to simulate API responses.

## Laravel Integration

To use the SDK in a Laravel project with a domain-driven structure:

1. Install the SDK as a Composer package (see [Installation](#installation)).
2. Place the SDK in a domain module, e.g., `app/Domains/Deployment/Vendor/QuickDeployer`.
3. Create a service class to wrap SDK usage:

```php
namespace App\Domains\Deployment\Services;

use QuickDeployer\SDK\Client;

class DeploymentService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(config('services.quickdeployer.api_key'));
    }

    public function getProjects(): array
    {
        return $this->client->getProjects();
    }
}
```

4. Register the service in a Laravel service provider and configure the API key in `config/services.php`.

## Contributing

1. Fork the repository.
2. Create a feature branch (`git checkout -b feature/your-feature`).
3. Commit your changes (`git commit -m "Add your feature"`).
4. Push to the branch (`git push origin feature/your-feature`).
5. Open a pull request.

Please include tests for new features and follow PSR-12 coding standards.

## License

This SDK is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Support

For issues or questions, open an issue on the [GitHub repository](https://github.com/niravsutariya/php-quick-deployer-sdk) or contact support@quickdeployer.com.