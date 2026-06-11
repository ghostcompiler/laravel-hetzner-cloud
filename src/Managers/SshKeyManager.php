<?php

namespace Vendor\HetznerCloud\Managers;

use Vendor\HetznerCloud\Collections\SshKeyCollection;
use Vendor\HetznerCloud\DTOs\PaginationMeta;
use Vendor\HetznerCloud\DTOs\SshKey;
use Vendor\HetznerCloud\Responses\PaginatedResponse;

class SshKeyManager extends AbstractManager
{
    public function all()
    {
        $response = $this->getRequest('ssh_keys', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $keys = array_map(fn (array $item) => SshKey::fromArray($item), $data['ssh_keys'] ?? []);
            return new SshKeyCollection($keys);
        });
    }

    public function get()
    {
        return $this->all();
    }

    public function paginate(int $perPage = 25, int $page = 1)
    {
        $this->perPage($perPage)->page($page);
        $response = $this->getRequest('ssh_keys', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $keys = array_map(fn (array $item) => SshKey::fromArray($item), $data['ssh_keys'] ?? []);
            $meta = PaginationMeta::fromArray($data['meta']['pagination'] ?? []);
            return new PaginatedResponse(new SshKeyCollection($keys), $meta);
        });
    }

    public function find(int $id)
    {
        $response = $this->getRequest("ssh_keys/{$id}");

        return $this->hydrate($response, function (array $data) {
            return SshKey::fromArray($data['ssh_key'] ?? []);
        });
    }

    public function create(array $data)
    {
        $response = $this->postRequest('ssh_keys', $data);

        return $this->hydrate($response, function (array $data) {
            return SshKey::fromArray($data['ssh_key'] ?? []);
        });
    }

    public function update(int $id, array $data)
    {
        $response = $this->putRequest("ssh_keys/{$id}", $data);

        return $this->hydrate($response, function (array $data) {
            return SshKey::fromArray($data['ssh_key'] ?? []);
        });
    }

    public function delete(int $id)
    {
        return $this->deleteRequest("ssh_keys/{$id}");
    }
}
