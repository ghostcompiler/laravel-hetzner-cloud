<?php

namespace Vendor\HetznerCloud\DTOs;

class Pricing
{
    public string $currency;

    public float $vatRate;

    public array $serverTypes = [];

    public array $loadBalancerTypes = [];

    public array $floatingIp = [];

    public array $primaryIp = [];

    public array $volume = [];

    public array $image = [];

    public array $traffic = [];

    /**
     * Create a new DTO instance from array data.
     */
    public static function fromArray(array $data): self
    {
        $price = new self;
        $price->currency = (string) ($data['currency'] ?? 'EUR');
        $price->vatRate = (float) ($data['vat_rate'] ?? 0.0);
        $price->serverTypes = (array) ($data['server_types'] ?? []);
        $price->loadBalancerTypes = (array) ($data['load_balancer_types'] ?? []);
        $price->floatingIp = (array) ($data['floating_ip'] ?? []);
        $price->primaryIp = (array) ($data['primary_ip'] ?? []);
        $price->volume = (array) ($data['volume'] ?? []);
        $price->image = (array) ($data['image'] ?? []);
        $price->traffic = (array) ($data['traffic'] ?? []);

        return $price;
    }
}
