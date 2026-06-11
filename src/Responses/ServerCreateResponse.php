<?php

namespace Vendor\HetznerCloud\Responses;

use Vendor\HetznerCloud\DTOs\Action;
use Vendor\HetznerCloud\DTOs\Server;

class ServerCreateResponse
{
    public Server $server;

    public ?Action $action = null;

    public ?string $rootPassword = null;

    public array $nextActions = [];

    public function __construct(Server $server, ?Action $action = null, ?string $rootPassword = null, array $nextActions = [])
    {
        $this->server = $server;
        $this->action = $action;
        $this->rootPassword = $rootPassword;
        $this->nextActions = $nextActions;
    }

    /**
     * Create response wrapper from raw API data.
     */
    public static function fromArray(array $data): self
    {
        $server = Server::fromArray($data['server'] ?? []);
        $action = isset($data['action']) ? Action::fromArray($data['action']) : null;
        $rootPassword = isset($data['root_password']) ? (string) $data['root_password'] : null;

        $nextActions = [];
        if (isset($data['next_actions']) && is_array($data['next_actions'])) {
            foreach ($data['next_actions'] as $actData) {
                $nextActions[] = Action::fromArray($actData);
            }
        }

        return new self($server, $action, $rootPassword, $nextActions);
    }
}
