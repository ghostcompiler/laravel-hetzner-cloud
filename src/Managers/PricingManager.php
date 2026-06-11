<?php

namespace Vendor\HetznerCloud\Managers;

use Vendor\HetznerCloud\DTOs\Pricing;

class PricingManager extends AbstractManager
{
    public function all()
    {
        $response = $this->getRequest('pricing');

        return $this->hydrate($response, function (array $data) {
            return Pricing::fromArray($data['pricing'] ?? []);
        });
    }

    public function get()
    {
        return $this->all();
    }
}
