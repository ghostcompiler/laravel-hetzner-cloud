<?php

namespace Vendor\HetznerCloud\Managers;

use Vendor\HetznerCloud\Collections\ServerTypeCollection;
use Vendor\HetznerCloud\DTOs\ServerType;

class ServerTypeManager extends AbstractManager
{
    public function all()
    {
        $response = $this->getRequest('server_types', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $types = array_map(fn (array $item) => ServerType::fromArray($item), $data['server_types'] ?? []);
            return new ServerTypeCollection($types);
        });
    }

    public function get()
    {
        return $this->all();
    }

    public function find(int $id)
    {
        $response = $this->getRequest("server_types/{$id}");

        return $this->hydrate($response, function (array $data) {
            return ServerType::fromArray($data['server_type'] ?? []);
        });
    }
}
