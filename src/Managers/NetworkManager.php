<?php

namespace Vendor\HetznerCloud\Managers;

use Vendor\HetznerCloud\Collections\NetworkCollection;
use Vendor\HetznerCloud\DTOs\Action;
use Vendor\HetznerCloud\DTOs\Network;
use Vendor\HetznerCloud\DTOs\PaginationMeta;
use Vendor\HetznerCloud\Responses\PaginatedResponse;

class NetworkManager extends AbstractManager
{
    public function all()
    {
        $response = $this->getRequest('networks', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $networks = array_map(fn (array $item) => Network::fromArray($item), $data['networks'] ?? []);

            return new NetworkCollection($networks);
        });
    }

    public function get()
    {
        return $this->all();
    }

    public function paginate(int $perPage = 25, int $page = 1)
    {
        $this->perPage($perPage)->page($page);
        $response = $this->getRequest('networks', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $networks = array_map(fn (array $item) => Network::fromArray($item), $data['networks'] ?? []);
            $meta = PaginationMeta::fromArray($data['meta']['pagination'] ?? []);

            return new PaginatedResponse(new NetworkCollection($networks), $meta);
        });
    }

    public function find(int $id)
    {
        $response = $this->getRequest("networks/{$id}");

        return $this->hydrate($response, function (array $data) {
            return Network::fromArray($data['network'] ?? []);
        });
    }

    public function create(array $data)
    {
        $response = $this->postRequest('networks', $data);

        return $this->hydrate($response, function (array $data) {
            return Network::fromArray($data['network'] ?? []);
        });
    }

    public function update(int $id, array $data)
    {
        $response = $this->putRequest("networks/{$id}", $data);

        return $this->hydrate($response, function (array $data) {
            return Network::fromArray($data['network'] ?? []);
        });
    }

    public function delete(int $id)
    {
        return $this->deleteRequest("networks/{$id}");
    }

    public function addSubnet($id, array $subnet)
    {
        $params = ['subnet' => $subnet];

        return $this->postAction((int) $id, 'add_subnet', $params);
    }

    public function deleteSubnet($id, array $subnet)
    {
        $params = ['subnet' => $subnet];

        return $this->postAction((int) $id, 'delete_subnet', $params);
    }

    public function addRoute($id, array $route)
    {
        $params = ['route' => $route];

        return $this->postAction((int) $id, 'add_route', $params);
    }

    public function deleteRoute($id, array $route)
    {
        $params = ['route' => $route];

        return $this->postAction((int) $id, 'delete_route', $params);
    }

    public function changeIpRange($id, string $ipRange)
    {
        $params = ['ip_range' => $ipRange];

        return $this->postAction((int) $id, 'change_ip_range', $params);
    }

    private function postAction(int $networkId, string $actionName, array $params = [])
    {
        $response = $this->postRequest("networks/{$networkId}/actions/{$actionName}", $params);

        return $this->hydrate($response, function (array $data) {
            return Action::fromArray($data['action'] ?? []);
        });
    }
}
