<?php

namespace Vendor\HetznerCloud\Managers;

use Vendor\HetznerCloud\Collections\LoadBalancerTypeCollection;
use Vendor\HetznerCloud\DTOs\LoadBalancerType;

class LoadBalancerTypeManager extends AbstractManager
{
    public function all()
    {
        $response = $this->getRequest('load_balancer_types', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $types = array_map(fn (array $item) => LoadBalancerType::fromArray($item), $data['load_balancer_types'] ?? []);
            return new LoadBalancerTypeCollection($types);
        });
    }

    public function get()
    {
        return $this->all();
    }

    public function find(int $id)
    {
        $response = $this->getRequest("load_balancer_types/{$id}");

        return $this->hydrate($response, function (array $data) {
            return LoadBalancerType::fromArray($data['load_balancer_type'] ?? []);
        });
    }
}
