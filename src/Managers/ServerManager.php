<?php

namespace Vendor\HetznerCloud\Managers;

use GuzzleHttp\Promise\PromiseInterface;
use Vendor\HetznerCloud\Collections\ActionCollection;
use Vendor\HetznerCloud\Collections\ServerCollection;
use Vendor\HetznerCloud\DTOs\Action;
use Vendor\HetznerCloud\DTOs\PaginationMeta;
use Vendor\HetznerCloud\DTOs\Server;
use Vendor\HetznerCloud\Responses\PaginatedResponse;
use Vendor\HetznerCloud\Responses\ServerCreateResponse;

class ServerManager extends AbstractManager
{
    /**
     * Get all servers.
     *
     * @return ServerCollection|PromiseInterface
     */
    public function all()
    {
        $response = $this->getRequest('servers', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $servers = array_map(fn (array $item) => Server::fromArray($item), $data['servers'] ?? []);

            return new ServerCollection($servers);
        });
    }

    /**
     * Alias for all().
     *
     * @return ServerCollection|PromiseInterface
     */
    public function get()
    {
        return $this->all();
    }

    /**
     * Paginate servers.
     *
     * @return PaginatedResponse|PromiseInterface
     */
    public function paginate(int $perPage = 25, int $page = 1)
    {
        $this->perPage($perPage)->page($page);
        $response = $this->getRequest('servers', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $servers = array_map(fn (array $item) => Server::fromArray($item), $data['servers'] ?? []);
            $meta = PaginationMeta::fromArray($data['meta']['pagination'] ?? []);

            return new PaginatedResponse(new ServerCollection($servers), $meta);
        });
    }

    /**
     * Find a server by ID.
     *
     * @return Server|PromiseInterface
     */
    public function find(int $id)
    {
        $response = $this->getRequest("servers/{$id}");

        return $this->hydrate($response, function (array $data) {
            return Server::fromArray($data['server'] ?? []);
        });
    }

    /**
     * Create a server.
     *
     * @return ServerCreateResponse|PromiseInterface
     */
    public function create(array $data)
    {
        $response = $this->postRequest('servers', $data);

        return $this->hydrate($response, function (array $data) {
            return ServerCreateResponse::fromArray($data);
        });
    }

    /**
     * Update a server.
     *
     * @return Server|PromiseInterface
     */
    public function update(int $id, array $data)
    {
        $response = $this->putRequest("servers/{$id}", $data);

        return $this->hydrate($response, function (array $data) {
            return Server::fromArray($data['server'] ?? []);
        });
    }

    /**
     * Delete a server.
     *
     * @return Action|null|PromiseInterface
     */
    public function delete(int $id)
    {
        $response = $this->deleteRequest("servers/{$id}");

        return $this->hydrate($response, function (array $data) {
            return isset($data['action']) ? Action::fromArray($data['action']) : null;
        });
    }

    /**
     * Get metrics for a server.
     *
     * @return array|PromiseInterface
     */
    public function metrics(int $id, array $params = [])
    {
        return $this->getRequest("servers/{$id}/metrics", $params);
    }

    /**
     * Get action history for a server.
     *
     * @return ActionCollection|PromiseInterface
     */
    public function actions(int $id)
    {
        $response = $this->getRequest("servers/{$id}/actions");

        return $this->hydrate($response, function (array $data) {
            $actions = array_map(fn (array $item) => Action::fromArray($item), $data['actions'] ?? []);

            return new ActionCollection($actions);
        });
    }

    /**
     * Power on server.
     *
     * @return Action|PromiseInterface
     */
    public function powerOn(int $id)
    {
        return $this->postAction($id, 'poweron');
    }

    /**
     * Power off server.
     *
     * @return Action|PromiseInterface
     */
    public function powerOff(int $id)
    {
        return $this->postAction($id, 'poweroff');
    }

    /**
     * Shutdown server.
     *
     * @return Action|PromiseInterface
     */
    public function shutdown(int $id)
    {
        return $this->postAction($id, 'shutdown');
    }

    /**
     * Reboot server.
     *
     * @return Action|PromiseInterface
     */
    public function reboot(int $id)
    {
        return $this->postAction($id, 'reboot');
    }

    /**
     * Reset server power.
     *
     * @return Action|PromiseInterface
     */
    public function reset(int $id)
    {
        return $this->postAction($id, 'reset');
    }

    /**
     * Rebuild server from image.
     *
     * @param  string|int  $image
     * @return Action|PromiseInterface
     */
    public function rebuild(int $id, $image)
    {
        return $this->postAction($id, 'rebuild', ['image' => $image]);
    }

    /**
     * Enable rescue mode.
     *
     * @return Action|PromiseInterface
     */
    public function enableRescue(int $id, array $params = [])
    {
        return $this->postAction($id, 'enable_rescue', $params);
    }

    /**
     * Disable rescue mode.
     *
     * @return Action|PromiseInterface
     */
    public function disableRescue(int $id)
    {
        return $this->postAction($id, 'disable_rescue');
    }

    /**
     * Enable backups.
     *
     * @return Action|PromiseInterface
     */
    public function enableBackup(int $id)
    {
        return $this->postAction($id, 'enable_backup');
    }

    /**
     * Disable backups.
     *
     * @return Action|PromiseInterface
     */
    public function disableBackup(int $id)
    {
        return $this->postAction($id, 'disable_backup');
    }

    /**
     * Attach ISO image.
     *
     * @param  string|int  $iso
     * @return Action|PromiseInterface
     */
    public function attachIso(int $id, $iso)
    {
        return $this->postAction($id, 'attach_iso', ['iso' => $iso]);
    }

    /**
     * Detach ISO image.
     *
     * @return Action|PromiseInterface
     */
    public function detachIso(int $id)
    {
        return $this->postAction($id, 'detach_iso');
    }

    /**
     * Attach to a network.
     *
     * @param  array|int  $network
     * @return Action|PromiseInterface
     */
    public function attachToNetwork(int $id, $network)
    {
        $params = is_array($network) ? $network : ['network' => (int) $network];

        return $this->postAction($id, 'attach_to_network', $params);
    }

    /**
     * Detach from a network.
     *
     * @param  array|int  $network
     * @return Action|PromiseInterface
     */
    public function detachFromNetwork(int $id, $network)
    {
        $params = is_array($network) ? $network : ['network' => (int) $network];

        return $this->postAction($id, 'detach_from_network', $params);
    }

    /**
     * Add server to a firewall.
     *
     * @param  array|int  $firewall
     * @return Action|PromiseInterface
     */
    public function addToFirewall(int $id, $firewall)
    {
        $params = is_array($firewall) ? $firewall : ['firewall' => (int) $firewall];

        return $this->postAction($id, 'add_to_firewall', $params);
    }

    /**
     * Remove server from a firewall.
     *
     * @param  array|int  $firewall
     * @return Action|PromiseInterface
     */
    public function removeFromFirewall(int $id, $firewall)
    {
        $params = is_array($firewall) ? $firewall : ['firewall' => (int) $firewall];

        return $this->postAction($id, 'remove_from_firewall', $params);
    }

    /**
     * Post a server action.
     */
    private function postAction(int $serverId, string $actionName, array $params = [])
    {
        $response = $this->postRequest("servers/{$serverId}/actions/{$actionName}", $params);

        return $this->hydrate($response, function (array $data) {
            return Action::fromArray($data['action'] ?? []);
        });
    }
}
