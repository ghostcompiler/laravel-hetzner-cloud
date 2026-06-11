<?php

namespace Vendor\HetznerCloud\Managers;

use Vendor\HetznerCloud\Collections\VolumeCollection;
use Vendor\HetznerCloud\DTOs\Action;
use Vendor\HetznerCloud\DTOs\PaginationMeta;
use Vendor\HetznerCloud\DTOs\Volume;
use Vendor\HetznerCloud\Responses\PaginatedResponse;
use Vendor\HetznerCloud\Responses\VolumeCreateResponse;

class VolumeManager extends AbstractManager
{
    public function all()
    {
        $response = $this->getRequest('volumes', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $volumes = array_map(fn (array $item) => Volume::fromArray($item), $data['volumes'] ?? []);
            return new VolumeCollection($volumes);
        });
    }

    public function get()
    {
        return $this->all();
    }

    public function paginate(int $perPage = 25, int $page = 1)
    {
        $this->perPage($perPage)->page($page);
        $response = $this->getRequest('volumes', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $volumes = array_map(fn (array $item) => Volume::fromArray($item), $data['volumes'] ?? []);
            $meta = PaginationMeta::fromArray($data['meta']['pagination'] ?? []);
            return new PaginatedResponse(new VolumeCollection($volumes), $meta);
        });
    }

    public function find(int $id)
    {
        $response = $this->getRequest("volumes/{$id}");

        return $this->hydrate($response, function (array $data) {
            return Volume::fromArray($data['volume'] ?? []);
        });
    }

    public function create(array $data)
    {
        $response = $this->postRequest('volumes', $data);

        return $this->hydrate($response, function (array $data) {
            return VolumeCreateResponse::fromArray($data);
        });
    }

    public function update(int $id, array $data)
    {
        $response = $this->putRequest("volumes/{$id}", $data);

        return $this->hydrate($response, function (array $data) {
            return Volume::fromArray($data['volume'] ?? []);
        });
    }

    public function delete(int $id)
    {
        return $this->deleteRequest("volumes/{$id}");
    }

    public function attach($volumeId, $serverId, array $params = [])
    {
        $params['server'] = (int) $serverId;
        return $this->postAction((int) $volumeId, 'attach', $params);
    }

    public function detach($volumeId)
    {
        return $this->postAction((int) $volumeId, 'detach');
    }

    public function resize($volumeId, int $size)
    {
        return $this->postAction((int) $volumeId, 'resize', ['size' => $size]);
    }

    public function changeDnsPtr($volumeId, array $params = [])
    {
        return $this->postAction((int) $volumeId, 'change_dns_ptr', $params);
    }

    private function postAction(int $volumeId, string $actionName, array $params = [])
    {
        $response = $this->postRequest("volumes/{$volumeId}/actions/{$actionName}", $params);

        return $this->hydrate($response, function (array $data) {
            return Action::fromArray($data['action'] ?? []);
        });
    }
}
