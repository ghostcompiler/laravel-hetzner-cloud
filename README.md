<p align="center">
  <img src="https://res.cloudinary.com/djgvfl1tv/image/upload/v1780666791/logo_mqnqn4.png" alt="Laravel Hetzner Cloud" width="180">
</p>

<h1 align="center">Laravel Hetzner Cloud SDK</h1>

<p align="center">
  A premium, feature-rich PHP SDK and Laravel integration for the Hetzner Cloud API, featuring rate-limit handling, automatic retries, and concurrent batch operations.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11%20%7C%2012%20%7C%2013-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2%20to%208.5-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP Version">
  <img src="https://img.shields.io/badge/Hetzner--Cloud-API-D85012?style=for-the-badge" alt="Hetzner Cloud API">
  <img src="https://img.shields.io/badge/Built%20By-Ghost%20Compiler-0F172A?style=for-the-badge" alt="Ghost Compiler">
</p>

---

## Features

- **100% Endpoint Coverage**: Complete implementation of all servers, volumes, networks, firewalls, load balancers, IPs, and supporting resources.
- **Fail-Safe Retries & Backoff**: Robust exponential backoff and rate-limit parsing handling `RateLimit-Reset` response headers automatically.
- **Concurrently Pooled Processing**: Execute calls asynchronously or concurrently in batches.
- **Dynamic Filter Builder**: Fluent query-building for filtering, page indexing, and sorting.
- **Type-Safe DTOs**: Automated data hydration into standard PHP DTO structures.
- **Custom Exceptions**: Specialized mapping of API status codes.

---

## Installation

Install the package via Composer:

```bash
composer require ghostcompiler/laravel-hetzner-cloud
```

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Vendor\HetznerCloud\Providers\HetznerCloudServiceProvider" --tag="config"
```

Add your Hetzner Cloud API Token to your `.env` file:

```env
HETZNER_CLOUD_TOKEN=your_api_token_here
HETZNER_CLOUD_TIMEOUT=30
HETZNER_CLOUD_RETRIES=3
HETZNER_CLOUD_RETRY_BACKOFF=100
HETZNER_CLOUD_LOGGING_ENABLED=true
```

---

## Usage Examples

### Servers

#### Listing and Filtering Servers
```php
use Vendor\HetznerCloud\Facades\Hetzner;

// List the first 50 servers matching a name, sorted by creation date
$servers = Hetzner::servers()
    ->filter(['name' => 'web-01'])
    ->sort('created:desc')
    ->perPage(50)
    ->page(1)
    ->get();

foreach ($servers as $server) {
    echo $server->name . ': ' . $server->status . "\n";
}
```

#### Paginated Results
```php
$paginated = Hetzner::servers()->paginate(25, 2);

$servers = $paginated->items; // ServerCollection
$meta = $paginated->pagination; // PaginationMeta DTO

echo "Page: " . $meta->page . " of " . $meta->lastPage;
```

#### Creating and Deleting a Server
```php
// Create a new server
$response = Hetzner::servers()->create([
    'name' => 'database-prod',
    'server_type' => 'cx22',
    'image' => 'ubuntu-24.04',
    'location' => 'fsn1',
]);

$server = $response->server;
$action = $response->action;
$rootPassword = $response->rootPassword;

echo "Provisioned server ID: " . $server->id . "\n";
echo "Initial Root Password: " . $rootPassword . "\n";

// Delete the server
$deleteAction = Hetzner::servers()->delete($server->id);
if ($deleteAction) {
    echo "Deletion status: " . $deleteAction->status;
}
```

#### Triggering Server Actions
```php
// Power off a server
$action = Hetzner::servers()->powerOff($serverId);
echo "Action status: " . $action->status; // running / success

// Attach an ISO
Hetzner::servers()->attachIso($serverId, 'ubuntu-24.04-preinstall');
```

---

### Volumes
```php
// Create a volume
$response = Hetzner::volumes()->create([
    'name' => 'data-disk',
    'size' => 100,
    'location' => 'fsn1'
]);

$volume = $response->volume;

// Attach volume to a server
Hetzner::volumes()->attach($volume->id, $serverId);

// Resize volume
Hetzner::volumes()->resize($volume->id, 250);
```

---

### Firewalls
```php
// Apply firewall to a server
$action = Hetzner::firewalls()->apply($firewallId, $serverId);

// Remove firewall from a server
Hetzner::firewalls()->remove($firewallId, $serverId);
```

---

### Asynchronous Requests
```php
// Return a Guzzle Promise immediately
$promise = Hetzner::servers()->async()->all();

// Resolve promise when ready
$servers = $promise->wait();
```

---

### Batch Concurrent Operations
```php
// Execute multiple queries concurrently
$results = Hetzner::batch([
    fn () => Hetzner::servers()->find(1),
    fn () => Hetzner::servers()->find(2),
    fn () => Hetzner::volumes()->find(10),
]);

$server1 = $results[0];
$server2 = $results[1];
$volume = $results[2];
```

---

### Exception Handling
All exceptions inherit from `Vendor\HetznerCloud\Exceptions\HetznerException`.

```php
use Vendor\HetznerCloud\Exceptions\AuthenticationException;
use Vendor\HetznerCloud\Exceptions\ValidationException;
use Vendor\HetznerCloud\Exceptions\RateLimitException;
use Vendor\HetznerCloud\Exceptions\HetznerException;

try {
    Hetzner::servers()->create(['name' => '']);
} catch (AuthenticationException $e) {
    // 401 Unauthorized
} catch (ValidationException $e) {
    // 422 Unprocessable Entity
    $errors = $e->getErrors(); // Get field-specific validation errors
} catch (RateLimitException $e) {
    // 429 Rate Limit Exceeded
    $secondsToWait = $e->getSecondsUntilReset();
} catch (HetznerException $e) {
    // Base exception handler
}
```

---

## Static Analysis & Linting

Run PHPStan static analysis:
```bash
vendor/bin/phpstan analyse
```

Run Psalm static analysis:
```bash
vendor/bin/psalm
```

Format code with Pint:
```bash
vendor/bin/pint
```

---

## Development Environment

Built using **ServBay**

<p align="left">
  <img src="https://res.cloudinary.com/djgvfl1tv/image/upload/v1780667063/servbay_edc7jz.png" alt="ServBay" width="120">
</p>

- Mac M4 Tested
- macOS Apple Silicon
- Powered by ServBay

---
