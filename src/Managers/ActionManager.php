<?php

namespace Vendor\HetznerCloud\Managers;

use Vendor\HetznerCloud\Collections\ActionCollection;
use Vendor\HetznerCloud\DTOs\Action;
use Vendor\HetznerCloud\DTOs\PaginationMeta;
use Vendor\HetznerCloud\Responses\PaginatedResponse;

class ActionManager extends AbstractManager
{
    public function all()
    {
        $response = $this->getRequest('actions', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $actions = array_map(fn (array $item) => Action::fromArray($item), $data['actions'] ?? []);

            return new ActionCollection($actions);
        });
    }

    public function get()
    {
        return $this->all();
    }

    public function paginate(int $perPage = 25, int $page = 1)
    {
        $this->perPage($perPage)->page($page);
        $response = $this->getRequest('actions', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $actions = array_map(fn (array $item) => Action::fromArray($item), $data['actions'] ?? []);
            $meta = PaginationMeta::fromArray($data['meta']['pagination'] ?? []);

            return new PaginatedResponse(new ActionCollection($actions), $meta);
        });
    }

    public function find(int $id)
    {
        $response = $this->getRequest("actions/{$id}");

        return $this->hydrate($response, function (array $data) {
            return Action::fromArray($data['action'] ?? []);
        });
    }
}
