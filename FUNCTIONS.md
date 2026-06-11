# Laravel Hetzner Cloud SDK - Functions Reference

This file documents every manager, method, helper, and facade call exposed by the package.

---

# Authentication

```php
Hetzner::authenticate($token);
```

---

# Servers

```php
Hetzner::servers()->all();

Hetzner::servers()->paginate();

Hetzner::servers()->find($id);

Hetzner::servers()->create([
    'name' => 'web-01',
    'server_type' => 'cx22',
    'image' => 'ubuntu-24.04',
]);

Hetzner::servers()->update($id, []);

Hetzner::servers()->delete($id);

Hetzner::servers()->metrics($id);

Hetzner::servers()->actions($id);

Hetzner::servers()->powerOn($id);

Hetzner::servers()->powerOff($id);

Hetzner::servers()->shutdown($id);

Hetzner::servers()->reboot($id);

Hetzner::servers()->reset($id);

Hetzner::servers()->rebuild($id, $image);

Hetzner::servers()->enableRescue($id);

Hetzner::servers()->disableRescue($id);

Hetzner::servers()->enableBackup($id);

Hetzner::servers()->disableBackup($id);

Hetzner::servers()->attachIso($id, $iso);

Hetzner::servers()->detachIso($id);

Hetzner::servers()->attachToNetwork($id, $network);

Hetzner::servers()->detachFromNetwork($id, $network);

Hetzner::servers()->addToFirewall($id, $firewall);

Hetzner::servers()->removeFromFirewall($id, $firewall);
```

---

# Volumes

```php
Hetzner::volumes()->all();

Hetzner::volumes()->find($id);

Hetzner::volumes()->create([]);

Hetzner::volumes()->update($id, []);

Hetzner::volumes()->delete($id);

Hetzner::volumes()->attach($volume, $server);

Hetzner::volumes()->detach($volume);

Hetzner::volumes()->resize($volume, $size);

Hetzner::volumes()->changeDnsPtr($volume, []);
```

---

# Networks

```php
Hetzner::networks()->all();

Hetzner::networks()->find($id);

Hetzner::networks()->create([]);

Hetzner::networks()->update($id, []);

Hetzner::networks()->delete($id);

Hetzner::networks()->addSubnet($id, []);

Hetzner::networks()->deleteSubnet($id, []);

Hetzner::networks()->addRoute($id, []);

Hetzner::networks()->deleteRoute($id, []);

Hetzner::networks()->changeIpRange($id, '10.0.0.0/16');
```

---

# Firewalls

```php
Hetzner::firewalls()->all();

Hetzner::firewalls()->find($id);

Hetzner::firewalls()->create([]);

Hetzner::firewalls()->update($id, []);

Hetzner::firewalls()->delete($id);

Hetzner::firewalls()->apply($firewall, $server);

Hetzner::firewalls()->remove($firewall, $server);

Hetzner::firewalls()->setRules($firewall, []);
```

---

# Floating IPs

```php
Hetzner::floatingIps()->all();

Hetzner::floatingIps()->find($id);

Hetzner::floatingIps()->create([]);

Hetzner::floatingIps()->update($id, []);

Hetzner::floatingIps()->delete($id);

Hetzner::floatingIps()->assign($ip, $server);

Hetzner::floatingIps()->unassign($ip);

Hetzner::floatingIps()->changeDnsPtr($ip, []);
```

---

# Primary IPs

```php
Hetzner::primaryIps()->all();

Hetzner::primaryIps()->find($id);

Hetzner::primaryIps()->create([]);

Hetzner::primaryIps()->update($id, []);

Hetzner::primaryIps()->delete($id);

Hetzner::primaryIps()->assign($ip, $server);

Hetzner::primaryIps()->unassign($ip);

Hetzner::primaryIps()->changeDnsPtr($ip, []);
```

---

# Load Balancers

```php
Hetzner::loadBalancers()->all();

Hetzner::loadBalancers()->find($id);

Hetzner::loadBalancers()->create([]);

Hetzner::loadBalancers()->update($id, []);

Hetzner::loadBalancers()->delete($id);

Hetzner::loadBalancers()->addTarget($id, []);

Hetzner::loadBalancers()->removeTarget($id);

Hetzner::loadBalancers()->addService($id, []);

Hetzner::loadBalancers()->updateService($id, $listenPort, []);

Hetzner::loadBalancers()->deleteService($id);

Hetzner::loadBalancers()->changeAlgorithm($id, $algorithm);

Hetzner::loadBalancers()->changeType($id, $type);

Hetzner::loadBalancers()->changeIpSupport($id, $ipSupport);

Hetzner::loadBalancers()->enablePublicInterface($id);

Hetzner::loadBalancers()->disablePublicInterface($id);

Hetzner::loadBalancers()->attachToNetwork($id, $network);

Hetzner::loadBalancers()->detachFromNetwork($id, $network);

Hetzner::loadBalancers()->changeDnsPtr($id, []);

Hetzner::loadBalancers()->metrics($id);
```

---

# SSH Keys

```php
Hetzner::sshKeys()->all();

Hetzner::sshKeys()->find($id);

Hetzner::sshKeys()->create([]);

Hetzner::sshKeys()->update($id, []);

Hetzner::sshKeys()->delete($id);
```

---

# Images

```php
Hetzner::images()->all();

Hetzner::images()->find($id);

Hetzner::images()->update($id, []);

Hetzner::images()->delete($id);

Hetzner::images()->changeProtection($id, []);
```

---

# Certificates

```php
Hetzner::certificates()->all();

Hetzner::certificates()->find($id);

Hetzner::certificates()->create([]);

Hetzner::certificates()->update($id, []);

Hetzner::certificates()->delete($id);

Hetzner::certificates()->retry($id);
```

---

# Placement Groups

```php
Hetzner::placementGroups()->all();

Hetzner::placementGroups()->find($id);

Hetzner::placementGroups()->create([]);

Hetzner::placementGroups()->update($id, []);

Hetzner::placementGroups()->delete($id);
```

---

# Locations

```php
Hetzner::locations()->all();

Hetzner::locations()->find($id);
```

---

# Datacenters

```php
Hetzner::datacenters()->all();

Hetzner::datacenters()->find($id);
```

---

# ISOs

```php
Hetzner::isos()->all();

Hetzner::isos()->find($id);
```

---

# Pricing

```php
Hetzner::pricing()->all();
```

---

# Actions

```php
Hetzner::actions()->all();

Hetzner::actions()->find($id);
```

---

# Server Types

```php
Hetzner::serverTypes()->all();

Hetzner::serverTypes()->find($id);
```

---

# Load Balancer Types

```php
Hetzner::loadBalancerTypes()->all();

Hetzner::loadBalancerTypes()->find($id);
```

---

# Common Query Builder Methods

```php
Hetzner::servers()
    ->filter([
        'name' => 'web-01'
    ])
    ->sort('created')
    ->perPage(50)
    ->page(1)
    ->get();
```

---

# Async Requests

```php
Hetzner::servers()->async()->all();

Hetzner::volumes()->async()->all();
```

---

# Batch Operations

```php
Hetzner::batch([
    fn () => Hetzner::servers()->find(1),
    fn () => Hetzner::servers()->find(2),
    fn () => Hetzner::servers()->find(3),
]);
```

---

# Helper Methods

```php
Hetzner::ping();

Hetzner::version();

Hetzner::rateLimit();

Hetzner::health();

Hetzner::config();

Hetzner::client();
```
