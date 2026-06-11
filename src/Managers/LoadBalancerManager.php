<?php

namespace Vendor\HetznerCloud\Managers;

use Vendor\HetznerCloud\Collections\LoadBalancerCollection;
use Vendor\HetznerCloud\DTOs\Action;
use Vendor\HetznerCloud\DTOs\LoadBalancer;
use Vendor\HetznerCloud\DTOs\PaginationMeta;
use Vendor\HetznerCloud\Responses\PaginatedResponse;

class LoadBalancerManager extends AbstractManager
{
    public function all()
    {
        $response = $this->getRequest('load_balancers', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $lbs = array_map(fn (array $item) => LoadBalancer::fromArray($item), $data['load_balancers'] ?? []);

            return new LoadBalancerCollection($lbs);
        });
    }

    public function get()
    {
        return $this->all();
    }

    public function paginate(int $perPage = 25, int $page = 1)
    {
        $this->perPage($perPage)->page($page);
        $response = $this->getRequest('load_balancers', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $lbs = array_map(fn (array $item) => LoadBalancer::fromArray($item), $data['load_balancers'] ?? []);
            $meta = PaginationMeta::fromArray($data['meta']['pagination'] ?? []);

            return new PaginatedResponse(new LoadBalancerCollection($lbs), $meta);
        });
    }

    public function find(int $id)
    {
        $response = $this->getRequest("load_balancers/{$id}");

        return $this->hydrate($response, function (array $data) {
            return LoadBalancer::fromArray($data['load_balancer'] ?? []);
        });
    }

    public function create(array $data)
    {
        $response = $this->postRequest('load_balancers', $data);

        return $this->hydrate($response, function (array $data) {
            return LoadBalancer::fromArray($data['load_balancer'] ?? []);
        });
    }

    public function update(int $id, array $data)
    {
        $response = $this->putRequest("load_balancers/{$id}", $data);

        return $this->hydrate($response, function (array $data) {
            return LoadBalancer::fromArray($data['load_balancer'] ?? []);
        });
    }

    public function delete(int $id)
    {
        return $this->deleteRequest("load_balancers/{$id}");
    }

    public function addTarget($id, array $target)
    {
        if (! isset($target['target']) && ! isset($target['type'])) {
            $params = ['target' => $target];
        } else {
            $params = $target;
        }

        return $this->postAction((int) $id, 'add_target', $params);
    }

    public function removeTarget($id, $target = [])
    {
        $params = (array) $target;
        if (! empty($params) && ! isset($params['target']) && ! isset($params['type'])) {
            $params = ['target' => $params];
        }

        return $this->postAction((int) $id, 'remove_target', $params);
    }

    public function addService($id, array $service)
    {
        $params = isset($service['service']) ? $service : ['service' => $service];

        return $this->postAction((int) $id, 'add_service', $params);
    }

    public function deleteService($id, $listenPort = null)
    {
        $params = is_array($listenPort) ? $listenPort : ['listen_port' => (int) $listenPort];

        return $this->postAction((int) $id, 'delete_service', $params);
    }

    public function updateService($id, int $listenPort, array $service)
    {
        $params = ['listen_port' => $listenPort, 'service' => $service];

        return $this->postAction((int) $id, 'update_service', $params);
    }

    public function changeAlgorithm($id, string $algorithm)
    {
        return $this->postAction((int) $id, 'change_algorithm', ['algorithm' => $algorithm]);
    }

    public function changeType($id, string $lbType)
    {
        return $this->postAction((int) $id, 'change_type', ['load_balancer_type' => $lbType]);
    }

    public function changeIpSupport($id, string $ipSupport)
    {
        return $this->postAction((int) $id, 'change_ip_support', ['ip_support' => $ipSupport]);
    }

    public function enablePublicInterface($id)
    {
        return $this->postAction((int) $id, 'enable_public_interface');
    }

    public function disablePublicInterface($id)
    {
        return $this->postAction((int) $id, 'disable_public_interface');
    }

    public function attachToNetwork($id, $network)
    {
        $params = is_array($network) ? $network : ['network' => (int) $network];

        return $this->postAction((int) $id, 'attach_to_network', $params);
    }

    public function detachFromNetwork($id, $network)
    {
        $params = is_array($network) ? $network : ['network' => (int) $network];

        return $this->postAction((int) $id, 'detach_from_network', $params);
    }

    public function changeDnsPtr($id, array $params = [])
    {
        return $this->postAction((int) $id, 'change_dns_ptr', $params);
    }

    public function metrics(int $id, array $params = [])
    {
        return $this->getRequest("load_balancers/{$id}/metrics", $params);
    }

    private function postAction(int $lbId, string $actionName, array $params = [])
    {
        $response = $this->postRequest("load_balancers/{$lbId}/actions/{$actionName}", $params);

        return $this->hydrate($response, function (array $data) {
            return Action::fromArray($data['action'] ?? []);
        });
    }
}
