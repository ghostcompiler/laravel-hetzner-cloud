<?php

namespace Vendor\HetznerCloud\Managers;

use Vendor\HetznerCloud\Collections\PrimaryIpCollection;
use Vendor\HetznerCloud\DTOs\Action;
use Vendor\HetznerCloud\DTOs\PaginationMeta;
use Vendor\HetznerCloud\DTOs\PrimaryIp;
use Vendor\HetznerCloud\Responses\PaginatedResponse;

class PrimaryIpManager extends AbstractManager
{
    public function all()
    {
        $response = $this->getRequest('primary_ips', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $ips = array_map(fn (array $item) => PrimaryIp::fromArray($item), $data['primary_ips'] ?? []);
            return new PrimaryIpCollection($ips);
        });
    }

    public function get()
    {
        return $this->all();
    }

    public function paginate(int $perPage = 25, int $page = 1)
    {
        $this->perPage($perPage)->page($page);
        $response = $this->getRequest('primary_ips', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $ips = array_map(fn (array $item) => PrimaryIp::fromArray($item), $data['primary_ips'] ?? []);
            $meta = PaginationMeta::fromArray($data['meta']['pagination'] ?? []);
            return new PaginatedResponse(new PrimaryIpCollection($ips), $meta);
        });
    }

    public function find(int $id)
    {
        $response = $this->getRequest("primary_ips/{$id}");

        return $this->hydrate($response, function (array $data) {
            return PrimaryIp::fromArray($data['primary_ip'] ?? []);
        });
    }

    public function create(array $data)
    {
        $response = $this->postRequest('primary_ips', $data);

        return $this->hydrate($response, function (array $data) {
            return PrimaryIp::fromArray($data['primary_ip'] ?? []);
        });
    }

    public function update(int $id, array $data)
    {
        $response = $this->putRequest("primary_ips/{$id}", $data);

        return $this->hydrate($response, function (array $data) {
            return PrimaryIp::fromArray($data['primary_ip'] ?? []);
        });
    }

    public function delete(int $id)
    {
        return $this->deleteRequest("primary_ips/{$id}");
    }

    public function assign($ipId, $serverId)
    {
        $params = [
            'assignee_id' => (int) $serverId,
            'assignee_type' => 'server',
        ];
        return $this->postAction((int) $ipId, 'assign', $params);
    }

    public function unassign($ipId)
    {
        return $this->postAction((int) $ipId, 'unassign');
    }

    public function changeDnsPtr($ipId, array $params = [])
    {
        return $this->postAction((int) $ipId, 'change_dns_ptr', $params);
    }

    private function postAction(int $ipId, string $actionName, array $params = [])
    {
        $response = $this->postRequest("primary_ips/{$ipId}/actions/{$actionName}", $params);

        return $this->hydrate($response, function (array $data) {
            return Action::fromArray($data['action'] ?? []);
        });
    }
}
