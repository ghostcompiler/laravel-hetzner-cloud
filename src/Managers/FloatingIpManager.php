<?php

namespace Vendor\HetznerCloud\Managers;

use Vendor\HetznerCloud\Collections\FloatingIpCollection;
use Vendor\HetznerCloud\DTOs\Action;
use Vendor\HetznerCloud\DTOs\FloatingIp;
use Vendor\HetznerCloud\DTOs\PaginationMeta;
use Vendor\HetznerCloud\Responses\PaginatedResponse;

class FloatingIpManager extends AbstractManager
{
    public function all()
    {
        $response = $this->getRequest('floating_ips', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $ips = array_map(fn (array $item) => FloatingIp::fromArray($item), $data['floating_ips'] ?? []);

            return new FloatingIpCollection($ips);
        });
    }

    public function get()
    {
        return $this->all();
    }

    public function paginate(int $perPage = 25, int $page = 1)
    {
        $this->perPage($perPage)->page($page);
        $response = $this->getRequest('floating_ips', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $ips = array_map(fn (array $item) => FloatingIp::fromArray($item), $data['floating_ips'] ?? []);
            $meta = PaginationMeta::fromArray($data['meta']['pagination'] ?? []);

            return new PaginatedResponse(new FloatingIpCollection($ips), $meta);
        });
    }

    public function find(int $id)
    {
        $response = $this->getRequest("floating_ips/{$id}");

        return $this->hydrate($response, function (array $data) {
            return FloatingIp::fromArray($data['floating_ip'] ?? []);
        });
    }

    public function create(array $data)
    {
        $response = $this->postRequest('floating_ips', $data);

        return $this->hydrate($response, function (array $data) {
            return FloatingIp::fromArray($data['floating_ip'] ?? []);
        });
    }

    public function update(int $id, array $data)
    {
        $response = $this->putRequest("floating_ips/{$id}", $data);

        return $this->hydrate($response, function (array $data) {
            return FloatingIp::fromArray($data['floating_ip'] ?? []);
        });
    }

    public function delete(int $id)
    {
        return $this->deleteRequest("floating_ips/{$id}");
    }

    public function assign($ipId, $serverId)
    {
        $params = ['server' => (int) $serverId];

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
        $response = $this->postRequest("floating_ips/{$ipId}/actions/{$actionName}", $params);

        return $this->hydrate($response, function (array $data) {
            return Action::fromArray($data['action'] ?? []);
        });
    }
}
