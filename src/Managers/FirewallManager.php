<?php

namespace Vendor\HetznerCloud\Managers;

use Vendor\HetznerCloud\Collections\FirewallCollection;
use Vendor\HetznerCloud\DTOs\Action;
use Vendor\HetznerCloud\DTOs\Firewall;
use Vendor\HetznerCloud\DTOs\PaginationMeta;
use Vendor\HetznerCloud\Responses\PaginatedResponse;

class FirewallManager extends AbstractManager
{
    public function all()
    {
        $response = $this->getRequest('firewalls', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $firewalls = array_map(fn (array $item) => Firewall::fromArray($item), $data['firewalls'] ?? []);
            return new FirewallCollection($firewalls);
        });
    }

    public function get()
    {
        return $this->all();
    }

    public function paginate(int $perPage = 25, int $page = 1)
    {
        $this->perPage($perPage)->page($page);
        $response = $this->getRequest('firewalls', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $firewalls = array_map(fn (array $item) => Firewall::fromArray($item), $data['firewalls'] ?? []);
            $meta = PaginationMeta::fromArray($data['meta']['pagination'] ?? []);
            return new PaginatedResponse(new FirewallCollection($firewalls), $meta);
        });
    }

    public function find(int $id)
    {
        $response = $this->getRequest("firewalls/{$id}");

        return $this->hydrate($response, function (array $data) {
            return Firewall::fromArray($data['firewall'] ?? []);
        });
    }

    public function create(array $data)
    {
        $response = $this->postRequest('firewalls', $data);

        return $this->hydrate($response, function (array $data) {
            return Firewall::fromArray($data['firewall'] ?? []);
        });
    }

    public function update(int $id, array $data)
    {
        $response = $this->putRequest("firewalls/{$id}", $data);

        return $this->hydrate($response, function (array $data) {
            return Firewall::fromArray($data['firewall'] ?? []);
        });
    }

    public function delete(int $id)
    {
        return $this->deleteRequest("firewalls/{$id}");
    }

    public function apply($firewallId, $serverId)
    {
        $params = [
            'apply_to' => [
                [
                    'type' => 'server',
                    'server' => ['id' => (int) $serverId]
                ]
            ]
        ];
        // Note: Hetzner API returns {"actions": [...]} here. We can return the first action or array of Action.
        // Let's hydrate and return the actions as ActionCollection or the first action.
        // Standard in FUNCTIONS.md returns the result. Let's return the action.
        $response = $this->postRequest("firewalls/{$firewallId}/actions/apply_to_resources", $params);

        return $this->hydrate($response, function (array $data) {
            $actions = $data['actions'] ?? [];
            return Action::fromArray($actions[0] ?? []);
        });
    }

    public function remove($firewallId, $serverId)
    {
        $params = [
            'remove_from' => [
                [
                    'type' => 'server',
                    'server' => ['id' => (int) $serverId]
                ]
            ]
        ];
        $response = $this->postRequest("firewalls/{$firewallId}/actions/remove_from_resources", $params);

        return $this->hydrate($response, function (array $data) {
            $actions = $data['actions'] ?? [];
            return Action::fromArray($actions[0] ?? []);
        });
    }

    public function setRules(int $firewallId, array $rules)
    {
        $params = ['rules' => $rules];
        $response = $this->postRequest("firewalls/{$firewallId}/actions/set_rules", $params);

        return $this->hydrate($response, function (array $data) {
            $actions = $data['actions'] ?? [];
            return Action::fromArray($actions[0] ?? []);
        });
    }
}
