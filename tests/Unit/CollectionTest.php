<?php

namespace Vendor\HetznerCloud\Tests\Unit;

use Vendor\HetznerCloud\Collections\ServerCollection;
use Vendor\HetznerCloud\Collections\VolumeCollection;
use Vendor\HetznerCloud\DTOs\Server;
use Vendor\HetznerCloud\DTOs\Volume;
use Vendor\HetznerCloud\Tests\TestCase;

class CollectionTest extends TestCase
{
    public function test_server_collection()
    {
        $server1 = Server::fromArray(['id' => 1, 'name' => 'server-1']);
        $server2 = Server::fromArray(['id' => 2, 'name' => 'server-2']);

        $col = new ServerCollection([$server1, $server2]);

        $this->assertCount(2, $col);
        $this->assertEquals('server-1', $col->first()->name);
        $this->assertEquals(2, $col->last()->id);
    }

    public function test_volume_collection()
    {
        $vol1 = Volume::fromArray(['id' => 1, 'name' => 'vol-1']);
        $col = new VolumeCollection([$vol1]);

        $this->assertCount(1, $col);
        $this->assertEquals('vol-1', $col->first()->name);
    }
}
