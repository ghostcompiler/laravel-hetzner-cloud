<?php

namespace Vendor\HetznerCloud\Managers;

use Vendor\HetznerCloud\Collections\ImageCollection;
use Vendor\HetznerCloud\DTOs\Action;
use Vendor\HetznerCloud\DTOs\Image;
use Vendor\HetznerCloud\DTOs\PaginationMeta;
use Vendor\HetznerCloud\Responses\PaginatedResponse;

class ImageManager extends AbstractManager
{
    public function all()
    {
        $response = $this->getRequest('images', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $images = array_map(fn (array $item) => Image::fromArray($item), $data['images'] ?? []);

            return new ImageCollection($images);
        });
    }

    public function get()
    {
        return $this->all();
    }

    public function paginate(int $perPage = 25, int $page = 1)
    {
        $this->perPage($perPage)->page($page);
        $response = $this->getRequest('images', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $images = array_map(fn (array $item) => Image::fromArray($item), $data['images'] ?? []);
            $meta = PaginationMeta::fromArray($data['meta']['pagination'] ?? []);

            return new PaginatedResponse(new ImageCollection($images), $meta);
        });
    }

    public function find(int $id)
    {
        $response = $this->getRequest("images/{$id}");

        return $this->hydrate($response, function (array $data) {
            return Image::fromArray($data['image'] ?? []);
        });
    }

    public function update(int $id, array $data)
    {
        $response = $this->putRequest("images/{$id}", $data);

        return $this->hydrate($response, function (array $data) {
            return Image::fromArray($data['image'] ?? []);
        });
    }

    public function delete(int $id)
    {
        return $this->deleteRequest("images/{$id}");
    }

    public function changeProtection(int $id, array $params = [])
    {
        $response = $this->postRequest("images/{$id}/actions/change_protection", $params);

        return $this->hydrate($response, function (array $data) {
            return Action::fromArray($data['action'] ?? []);
        });
    }
}
