<?php

namespace Vendor\HetznerCloud\Tests\Unit;

use Vendor\HetznerCloud\DTOs\Action;
use Vendor\HetznerCloud\DTOs\Certificate;
use Vendor\HetznerCloud\DTOs\Datacenter;
use Vendor\HetznerCloud\DTOs\Firewall;
use Vendor\HetznerCloud\DTOs\FloatingIp;
use Vendor\HetznerCloud\DTOs\Image;
use Vendor\HetznerCloud\DTOs\Iso;
use Vendor\HetznerCloud\DTOs\LoadBalancer;
use Vendor\HetznerCloud\DTOs\LoadBalancerType;
use Vendor\HetznerCloud\DTOs\Location;
use Vendor\HetznerCloud\DTOs\Network;
use Vendor\HetznerCloud\DTOs\PaginationMeta;
use Vendor\HetznerCloud\DTOs\PlacementGroup;
use Vendor\HetznerCloud\DTOs\Pricing;
use Vendor\HetznerCloud\DTOs\PrimaryIp;
use Vendor\HetznerCloud\DTOs\Server;
use Vendor\HetznerCloud\DTOs\ServerType;
use Vendor\HetznerCloud\DTOs\SshKey;
use Vendor\HetznerCloud\DTOs\Volume;
use Vendor\HetznerCloud\Tests\TestCase;

class DTOTest extends TestCase
{
    public function test_server_dto_hydration()
    {
        $data = [
            'id' => 123,
            'name' => 'web-01',
            'status' => 'running',
            'created' => '2026-06-11T12:00:00Z',
            'public_net' => ['ipv4' => ['ip' => '1.2.3.4']],
            'private_net' => [['network' => 12]],
            'server_type' => ['name' => 'cx22'],
            'image' => ['name' => 'ubuntu-24.04'],
            'datacenter' => ['name' => 'fsn1-dc14'],
            'volumes' => [1, 2],
            'firewalls' => [['firewall' => 1]],
            'rescue_enabled' => true,
            'locked' => false,
            'backup_window' => '22-02',
            'outgoing_traffic' => 1000,
            'ingoing_traffic' => 500,
            'labels' => ['env' => 'prod'],
        ];

        $server = Server::fromArray($data);

        $this->assertEquals(123, $server->id);
        $this->assertEquals('web-01', $server->name);
        $this->assertEquals('running', $server->status);
        $this->assertEquals('2026-06-11T12:00:00Z', $server->created);
        $this->assertEquals(['ipv4' => ['ip' => '1.2.3.4']], $server->publicNet);
        $this->assertEquals([['network' => 12]], $server->privateNet);
        $this->assertEquals(['name' => 'cx22'], $server->serverType);
        $this->assertEquals(['name' => 'ubuntu-24.04'], $server->image);
        $this->assertEquals(['name' => 'fsn1-dc14'], $server->datacenter);
        $this->assertEquals([1, 2], $server->volumes);
        $this->assertEquals([['firewall' => 1]], $server->firewalls);
        $this->assertTrue($server->rescueEnabled);
        $this->assertFalse($server->locked);
        $this->assertEquals('22-02', $server->backupWindow);
        $this->assertEquals(1000, $server->outgoingTraffic);
        $this->assertEquals(500, $server->ingoingTraffic);
        $this->assertEquals(['env' => 'prod'], $server->labels);
    }

    public function test_volume_dto_hydration()
    {
        $data = [
            'id' => 456,
            'name' => 'volume-01',
            'size' => 50,
            'status' => 'available',
            'server' => 123,
            'location' => ['name' => 'nbg1'],
            'created' => '2026-06-11T12:00:00Z',
            'protection' => ['delete' => false],
            'labels' => ['backup' => 'true'],
            'linux_device' => '/dev/sdb',
        ];

        $volume = Volume::fromArray($data);

        $this->assertEquals(456, $volume->id);
        $this->assertEquals('volume-01', $volume->name);
        $this->assertEquals(50, $volume->size);
        $this->assertEquals('available', $volume->status);
        $this->assertEquals(123, $volume->server);
        $this->assertEquals(['name' => 'nbg1'], $volume->location);
        $this->assertEquals('2026-06-11T12:00:00Z', $volume->created);
        $this->assertEquals(['delete' => false], $volume->protection);
        $this->assertEquals(['backup' => 'true'], $volume->labels);
        $this->assertEquals('/dev/sdb', $volume->linuxDevice);
    }

    public function test_network_dto_hydration()
    {
        $data = [
            'id' => 789,
            'name' => 'net-01',
            'ip_range' => '10.0.0.0/16',
            'subnets' => [['type' => 'cloud']],
            'routes' => [['destination' => '0.0.0.0/0']],
            'servers' => [123],
            'protection' => ['delete' => false],
            'created' => '2026-06-11T12:00:00Z',
            'labels' => [],
        ];

        $net = Network::fromArray($data);

        $this->assertEquals(789, $net->id);
        $this->assertEquals('net-01', $net->name);
        $this->assertEquals('10.0.0.0/16', $net->ipRange);
        $this->assertEquals([['type' => 'cloud']], $net->subnets);
        $this->assertEquals([['destination' => '0.0.0.0/0']], $net->routes);
        $this->assertEquals([123], $net->servers);
        $this->assertEquals(['delete' => false], $net->protection);
        $this->assertEquals('2026-06-11T12:00:00Z', $net->created);
        $this->assertEquals([], $net->labels);
    }

    public function test_firewall_dto_hydration()
    {
        $data = [
            'id' => 999,
            'name' => 'firewall-01',
            'rules' => [['direction' => 'in']],
            'applied_to' => [['server' => 123]],
            'created' => '2026-06-11T12:00:00Z',
            'labels' => [],
        ];

        $fw = Firewall::fromArray($data);

        $this->assertEquals(999, $fw->id);
        $this->assertEquals('firewall-01', $fw->name);
        $this->assertEquals([['direction' => 'in']], $fw->rules);
        $this->assertEquals([['server' => 123]], $fw->appliedTo);
        $this->assertEquals('2026-06-11T12:00:00Z', $fw->created);
    }

    public function test_floating_ip_dto_hydration()
    {
        $data = [
            'id' => 111,
            'name' => 'ip-01',
            'ip' => '1.2.3.4',
            'type' => 'ipv4',
            'server' => 123,
            'description' => 'Floating IP',
            'dns_ptr' => [['ip' => '1.2.3.4']],
            'blocked' => false,
            'home_location' => ['name' => 'fsn1'],
            'created' => '2026-06-11T12:00:00Z',
            'protection' => [],
            'labels' => [],
        ];

        $ip = FloatingIp::fromArray($data);

        $this->assertEquals(111, $ip->id);
        $this->assertEquals('ip-01', $ip->name);
        $this->assertEquals('1.2.3.4', $ip->ip);
        $this->assertEquals('ipv4', $ip->type);
        $this->assertEquals(123, $ip->server);
        $this->assertEquals('Floating IP', $ip->description);
        $this->assertEquals([['ip' => '1.2.3.4']], $ip->dnsPtr);
        $this->assertFalse($ip->blocked);
        $this->assertEquals(['name' => 'fsn1'], $ip->homeLocation);
        $this->assertEquals('2026-06-11T12:00:00Z', $ip->created);
    }

    public function test_primary_ip_dto_hydration()
    {
        $data = [
            'id' => 222,
            'name' => 'pip-01',
            'ip' => '5.6.7.8',
            'type' => 'ipv4',
            'assignee_id' => 123,
            'assignee_type' => 'server',
            'datacenter' => ['name' => 'fsn1-dc14'],
            'dns_ptr' => [],
            'blocked' => false,
            'created' => '2026-06-11T12:00:00Z',
            'protection' => [],
            'labels' => [],
            'auto_delete' => true,
        ];

        $ip = PrimaryIp::fromArray($data);

        $this->assertEquals(222, $ip->id);
        $this->assertEquals('pip-01', $ip->name);
        $this->assertEquals('5.6.7.8', $ip->ip);
        $this->assertEquals('ipv4', $ip->type);
        $this->assertEquals(123, $ip->assigneeId);
        $this->assertEquals('server', $ip->assigneeType);
        $this->assertEquals(['name' => 'fsn1-dc14'], $ip->datacenter);
        $this->assertTrue($ip->autoDelete);
    }

    public function test_load_balancer_dto_hydration()
    {
        $data = [
            'id' => 333,
            'name' => 'lb-01',
            'public_net' => [],
            'private_net' => [],
            'load_balancer_type' => ['name' => 'lb11'],
            'location' => [],
            'algorithm' => [],
            'services' => [],
            'targets' => [],
            'created' => '2026-06-11T12:00:00Z',
            'protection' => [],
            'labels' => [],
        ];

        $lb = LoadBalancer::fromArray($data);

        $this->assertEquals(333, $lb->id);
        $this->assertEquals('lb-01', $lb->name);
    }

    public function test_ssh_key_dto_hydration()
    {
        $data = [
            'id' => 444,
            'name' => 'key-01',
            'fingerprint' => 'aa:bb:cc',
            'public_key' => 'ssh-rsa...',
            'created' => '2026-06-11T12:00:00Z',
            'labels' => [],
        ];

        $key = SshKey::fromArray($data);

        $this->assertEquals(444, $key->id);
        $this->assertEquals('key-01', $key->name);
        $this->assertEquals('aa:bb:cc', $key->fingerprint);
        $this->assertEquals('ssh-rsa...', $key->publicKey);
    }

    public function test_image_dto_hydration()
    {
        $data = [
            'id' => 555,
            'type' => 'system',
            'status' => 'available',
            'name' => 'ubuntu-24.04',
            'description' => 'Ubuntu 24.04 LTS',
            'image_size' => 2.5,
            'disk_size' => 20.0,
            'created' => '2026-06-11T12:00:00Z',
            'created_from' => null,
            'bound_to' => null,
            'os_flavor' => 'ubuntu',
            'os_version' => '24.04',
            'rapid_rebuild' => true,
            'protection' => [],
            'deprecated' => null,
            'labels' => [],
        ];

        $img = Image::fromArray($data);

        $this->assertEquals(555, $img->id);
        $this->assertEquals('ubuntu-24.04', $img->name);
        $this->assertEquals(2.5, $img->imageSize);
        $this->assertTrue($img->rapidRebuild);
    }

    public function test_certificate_dto_hydration()
    {
        $data = [
            'id' => 666,
            'name' => 'cert-01',
            'labels' => [],
            'type' => 'uploaded',
            'created' => '2026-06-11T12:00:00Z',
            'domain_names' => ['example.com'],
            'fingerprint' => 'xx:yy',
            'status' => null,
            'not_before' => null,
            'not_after' => null,
        ];

        $cert = Certificate::fromArray($data);

        $this->assertEquals(666, $cert->id);
        $this->assertEquals('cert-01', $cert->name);
    }

    public function test_placement_group_dto_hydration()
    {
        $data = [
            'id' => 777,
            'name' => 'group-01',
            'type' => 'spread',
            'servers' => [1, 2],
            'created' => '2026-06-11T12:00:00Z',
            'labels' => [],
        ];

        $group = PlacementGroup::fromArray($data);

        $this->assertEquals(777, $group->id);
        $this->assertEquals('spread', $group->type);
    }

    public function test_location_dto_hydration()
    {
        $data = [
            'id' => 1,
            'name' => 'fsn1',
            'description' => 'Falkenstein',
            'country' => 'DE',
            'city' => 'Falkenstein',
            'latitude' => 50.4,
            'longitude' => 12.3,
            'network_zone' => 'eu-central',
        ];

        $loc = Location::fromArray($data);

        $this->assertEquals(1, $loc->id);
        $this->assertEquals('DE', $loc->country);
    }

    public function test_datacenter_dto_hydration()
    {
        $data = [
            'id' => 2,
            'name' => 'fsn1-dc14',
            'description' => 'Falkenstein 1 DC14',
            'location' => ['id' => 1],
            'server_types' => [['id' => 1]],
        ];

        $dc = Datacenter::fromArray($data);

        $this->assertEquals(2, $dc->id);
        $this->assertEquals(['id' => 1], $dc->location);
    }

    public function test_iso_dto_hydration()
    {
        $data = [
            'id' => 3,
            'name' => 'ubuntu-iso',
            'description' => 'Ubuntu ISO',
            'type' => 'public',
            'deprecated' => null,
        ];

        $iso = Iso::fromArray($data);

        $this->assertEquals(3, $iso->id);
        $this->assertEquals('Ubuntu ISO', $iso->description);
    }

    public function test_pricing_dto_hydration()
    {
        $data = [
            'currency' => 'EUR',
            'vat_rate' => 19.0,
            'server_types' => [['id' => 1]],
            'load_balancer_types' => [],
            'floating_ip' => [],
            'primary_ip' => [],
            'volume' => [],
            'image' => [],
            'traffic' => [],
        ];

        $price = Pricing::fromArray($data);

        $this->assertEquals('EUR', $price->currency);
        $this->assertEquals(19.0, $price->vatRate);
        $this->assertEquals([['id' => 1]], $price->serverTypes);
    }

    public function test_server_type_dto_hydration()
    {
        $data = [
            'id' => 4,
            'name' => 'cx22',
            'description' => 'CX22',
            'cores' => 2,
            'memory' => 4.0,
            'disk' => 40,
            'deprecated' => null,
            'prices' => [],
            'storage_type' => 'local',
            'cpu_type' => 'shared',
        ];

        $type = ServerType::fromArray($data);

        $this->assertEquals(4, $type->id);
        $this->assertEquals(2, $type->cores);
    }

    public function test_load_balancer_type_dto_hydration()
    {
        $data = [
            'id' => 5,
            'name' => 'lb11',
            'description' => 'LB11',
            'deprecated' => null,
            'prices' => [],
            'max_connections' => 20000,
            'max_services' => 5,
            'max_targets' => 25,
        ];

        $type = LoadBalancerType::fromArray($data);

        $this->assertEquals(5, $type->id);
        $this->assertEquals(20000, $type->maxConnections);
    }

    public function test_pagination_meta_dto_hydration()
    {
        $data = [
            'page' => 2,
            'per_page' => 25,
            'previous_page' => 1,
            'next_page' => 3,
            'last_page' => 4,
            'total_entries' => 100,
        ];

        $meta = PaginationMeta::fromArray($data);

        $this->assertEquals(2, $meta->page);
        $this->assertEquals(25, $meta->perPage);
        $this->assertEquals(1, $meta->previousPage);
        $this->assertEquals(3, $meta->nextPage);
        $this->assertEquals(4, $meta->lastPage);
        $this->assertEquals(100, $meta->totalEntries);
    }

    public function test_action_dto_hydration()
    {
        $data = [
            'id' => 12,
            'command' => 'start_server',
            'status' => 'success',
            'progress' => 100,
            'started' => '2026-06-11T12:00:00Z',
            'finished' => '2026-06-11T12:01:00Z',
            'resources' => [['id' => 1, 'type' => 'server']],
            'error' => null,
        ];

        $action = Action::fromArray($data);

        $this->assertEquals(12, $action->id);
        $this->assertEquals('start_server', $action->command);
        $this->assertEquals('success', $action->status);
        $this->assertEquals(100, $action->progress);
        $this->assertEquals('2026-06-11T12:00:00Z', $action->started);
        $this->assertEquals('2026-06-11T12:01:00Z', $action->finished);
        $this->assertEquals([['id' => 1, 'type' => 'server']], $action->resources);
        $this->assertNull($action->error);
    }
}
