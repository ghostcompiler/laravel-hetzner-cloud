<?php

namespace Vendor\HetznerCloud\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Vendor\HetznerCloud\Managers\HetznerManager authenticate(string $token)
 * @method static \Vendor\HetznerCloud\Managers\ServerManager servers()
 * @method static \Vendor\HetznerCloud\Managers\VolumeManager volumes()
 * @method static \Vendor\HetznerCloud\Managers\NetworkManager networks()
 * @method static \Vendor\HetznerCloud\Managers\FirewallManager firewalls()
 * @method static \Vendor\HetznerCloud\Managers\FloatingIpManager floatingIps()
 * @method static \Vendor\HetznerCloud\Managers\PrimaryIpManager primaryIps()
 * @method static \Vendor\HetznerCloud\Managers\LoadBalancerManager loadBalancers()
 * @method static \Vendor\HetznerCloud\Managers\SshKeyManager sshKeys()
 * @method static \Vendor\HetznerCloud\Managers\ImageManager images()
 * @method static \Vendor\HetznerCloud\Managers\CertificateManager certificates()
 * @method static \Vendor\HetznerCloud\Managers\PlacementGroupManager placementGroups()
 * @method static \Vendor\HetznerCloud\Managers\LocationManager locations()
 * @method static \Vendor\HetznerCloud\Managers\DatacenterManager datacenters()
 * @method static \Vendor\HetznerCloud\Managers\IsoManager isos()
 * @method static \Vendor\HetznerCloud\Managers\PricingManager pricing()
 * @method static \Vendor\HetznerCloud\Managers\ActionManager actions()
 * @method static \Vendor\HetznerCloud\Managers\ServerTypeManager serverTypes()
 * @method static \Vendor\HetznerCloud\Managers\LoadBalancerTypeManager loadBalancerTypes()
 * @method static bool ping()
 * @method static string version()
 * @method static array rateLimit()
 * @method static array health()
 * @method static array config()
 * @method static \Vendor\HetznerCloud\Http\Client\HetznerClient client()
 * @method static array batch(array $callbacks)
 *
 * @see \Vendor\HetznerCloud\Managers\HetznerManager
 */
class Hetzner extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'hetzner';
    }
}
