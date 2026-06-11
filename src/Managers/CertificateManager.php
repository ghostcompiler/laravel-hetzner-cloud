<?php

namespace Vendor\HetznerCloud\Managers;

use Vendor\HetznerCloud\Collections\CertificateCollection;
use Vendor\HetznerCloud\DTOs\Action;
use Vendor\HetznerCloud\DTOs\Certificate;
use Vendor\HetznerCloud\DTOs\PaginationMeta;
use Vendor\HetznerCloud\Responses\PaginatedResponse;

class CertificateManager extends AbstractManager
{
    public function all()
    {
        $response = $this->getRequest('certificates', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $certs = array_map(fn (array $item) => Certificate::fromArray($item), $data['certificates'] ?? []);

            return new CertificateCollection($certs);
        });
    }

    public function get()
    {
        return $this->all();
    }

    public function paginate(int $perPage = 25, int $page = 1)
    {
        $this->perPage($perPage)->page($page);
        $response = $this->getRequest('certificates', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $certs = array_map(fn (array $item) => Certificate::fromArray($item), $data['certificates'] ?? []);
            $meta = PaginationMeta::fromArray($data['meta']['pagination'] ?? []);

            return new PaginatedResponse(new CertificateCollection($certs), $meta);
        });
    }

    public function find(int $id)
    {
        $response = $this->getRequest("certificates/{$id}");

        return $this->hydrate($response, function (array $data) {
            return Certificate::fromArray($data['certificate'] ?? []);
        });
    }

    public function create(array $data)
    {
        $response = $this->postRequest('certificates', $data);

        return $this->hydrate($response, function (array $data) {
            return Certificate::fromArray($data['certificate'] ?? []);
        });
    }

    public function update(int $id, array $data)
    {
        $response = $this->putRequest("certificates/{$id}", $data);

        return $this->hydrate($response, function (array $data) {
            return Certificate::fromArray($data['certificate'] ?? []);
        });
    }

    public function delete(int $id)
    {
        return $this->deleteRequest("certificates/{$id}");
    }

    public function retry(int $id)
    {
        $response = $this->postRequest("certificates/{$id}/actions/retry");

        return $this->hydrate($response, function (array $data) {
            return Action::fromArray($data['action'] ?? []);
        });
    }
}
