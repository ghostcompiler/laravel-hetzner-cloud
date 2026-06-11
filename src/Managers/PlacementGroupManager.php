<?php

namespace Vendor\HetznerCloud\Managers;

use Vendor\HetznerCloud\Collections\PlacementGroupCollection;
use Vendor\HetznerCloud\DTOs\PaginationMeta;
use Vendor\HetznerCloud\DTOs\PlacementGroup;
use Vendor\HetznerCloud\Responses\PaginatedResponse;

class PlacementGroupManager extends AbstractManager
{
    public function all()
    {
        $response = $this->getRequest('placement_groups', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $groups = array_map(fn (array $item) => PlacementGroup::fromArray($item), $data['placement_groups'] ?? []);

            return new PlacementGroupCollection($groups);
        });
    }

    public function get()
    {
        return $this->all();
    }

    public function paginate(int $perPage = 25, int $page = 1)
    {
        $this->perPage($perPage)->page($page);
        $response = $this->getRequest('placement_groups', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $groups = array_map(fn (array $item) => PlacementGroup::fromArray($item), $data['placement_groups'] ?? []);
            $meta = PaginationMeta::fromArray($data['meta']['pagination'] ?? []);

            return new PaginatedResponse(new PlacementGroupCollection($groups), $meta);
        });
    }

    public function find(int $id)
    {
        $response = $this->getRequest("placement_groups/{$id}");

        return $this->hydrate($response, function (array $data) {
            return PlacementGroup::fromArray($data['placement_group'] ?? []);
        });
    }

    public function create(array $data)
    {
        $response = $this->postRequest('placement_groups', $data);

        return $this->hydrate($response, function (array $data) {
            return PlacementGroup::fromArray($data['placement_group'] ?? []);
        });
    }

    public function update(int $id, array $data)
    {
        $response = $this->putRequest("placement_groups/{$id}", $data);

        return $this->hydrate($response, function (array $data) {
            return PlacementGroup::fromArray($data['placement_group'] ?? []);
        });
    }

    public function delete(int $id)
    {
        return $this->deleteRequest("placement_groups/{$id}");
    }
}
