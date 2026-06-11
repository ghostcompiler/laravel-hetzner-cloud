# Hetzner Cloud API Endpoints to SDK Methods Mapping

This document maps every endpoint in the Hetzner Cloud API to its corresponding SDK manager method.

## Actions

| HTTP Method | API Endpoint | SDK Method |
| ----------- | ------------ | ---------- |
| `GET` | `/actions` | `Hetzner::actions()->all()` |
| `GET` | `/actions/{id}` | `Hetzner::actions()->find($id)` |

## Servers

| HTTP Method | API Endpoint | SDK Method |
| ----------- | ------------ | ---------- |
| `GET` | `/servers` | `Hetzner::servers()->all()` |
| `POST` | `/servers` | `Hetzner::servers()->create($data)` |
| `GET` | `/servers/{id}` | `Hetzner::servers()->find($id)` |
| `PUT` | `/servers/{id}` | `Hetzner::servers()->update($id, $data)` |
| `DELETE` | `/servers/{id}` | `Hetzner::servers()->delete($id)` |
| `GET` | `/servers/{id}/metrics` | `Hetzner::servers()->metrics($id, $params)` |
| `GET` | `/servers/{id}/actions` | `Hetzner::servers()->actions($id)` |
| `POST` | `/servers/{id}/actions/poweron` | `Hetzner::servers()->powerOn($id)` |
| `POST` | `/servers/{id}/actions/poweroff` | `Hetzner::servers()->powerOff($id)` |
| `POST` | `/servers/{id}/actions/shutdown` | `Hetzner::servers()->shutdown($id)` |
| `POST` | `/servers/{id}/actions/reboot` | `Hetzner::servers()->reboot($id)` |
| `POST` | `/servers/{id}/actions/reset` | `Hetzner::servers()->reset($id)` |
| `POST` | `/servers/{id}/actions/rebuild` | `Hetzner::servers()->rebuild($id, $image)` |
| `POST` | `/servers/{id}/actions/enable_rescue` | `Hetzner::servers()->enableRescue($id, $params)` |
| `POST` | `/servers/{id}/actions/disable_rescue` | `Hetzner::servers()->disableRescue($id)` |
| `POST` | `/servers/{id}/actions/enable_backup` | `Hetzner::servers()->enableBackup($id)` |
| `POST` | `/servers/{id}/actions/disable_backup` | `Hetzner::servers()->disableBackup($id)` |
| `POST` | `/servers/{id}/actions/attach_iso` | `Hetzner::servers()->attachIso($id, $iso)` |
| `POST` | `/servers/{id}/actions/detach_iso` | `Hetzner::servers()->detachIso($id)` |
| `POST` | `/servers/{id}/actions/attach_to_network` | `Hetzner::servers()->attachToNetwork($id, $network)` |
| `POST` | `/servers/{id}/actions/detach_from_network` | `Hetzner::servers()->detachFromNetwork($id, $network)` |
| `POST` | `/servers/{id}/actions/add_to_firewall` | `Hetzner::servers()->addToFirewall($id, $firewall)` |
| `POST` | `/servers/{id}/actions/remove_from_firewall` | `Hetzner::servers()->removeFromFirewall($id, $firewall)` |

## Volumes

| HTTP Method | API Endpoint | SDK Method |
| ----------- | ------------ | ---------- |
| `GET` | `/volumes` | `Hetzner::volumes()->all()` |
| `POST` | `/volumes` | `Hetzner::volumes()->create($data)` |
| `GET` | `/volumes/{id}` | `Hetzner::volumes()->find($id)` |
| `PUT` | `/volumes/{id}` | `Hetzner::volumes()->update($id, $data)` |
| `DELETE` | `/volumes/{id}` | `Hetzner::volumes()->delete($id)` |
| `POST` | `/volumes/{id}/actions/attach` | `Hetzner::volumes()->attach($volume, $server)` |
| `POST` | `/volumes/{id}/actions/detach` | `Hetzner::volumes()->detach($volume)` |
| `POST` | `/volumes/{id}/actions/resize` | `Hetzner::volumes()->resize($volume, $size)` |
| `POST` | `/volumes/{id}/actions/change_dns_ptr` | `Hetzner::volumes()->changeDnsPtr($volume, $params)` |

## Networks

| HTTP Method | API Endpoint | SDK Method |
| ----------- | ------------ | ---------- |
| `GET` | `/networks` | `Hetzner::networks()->all()` |
| `POST` | `/networks` | `Hetzner::networks()->create($data)` |
| `GET` | `/networks/{id}` | `Hetzner::networks()->find($id)` |
| `PUT` | `/networks/{id}` | `Hetzner::networks()->update($id, $data)` |
| `DELETE` | `/networks/{id}` | `Hetzner::networks()->delete($id)` |
| `POST` | `/networks/{id}/actions/add_subnet` | `Hetzner::networks()->addSubnet($id, $subnet)` |
| `POST` | `/networks/{id}/actions/delete_subnet` | `Hetzner::networks()->deleteSubnet($id, $subnet)` |
| `POST` | `/networks/{id}/actions/add_route` | `Hetzner::networks()->addRoute($id, $route)` |
| `POST` | `/networks/{id}/actions/delete_route` | `Hetzner::networks()->deleteRoute($id, $route)` |
| `POST` | `/networks/{id}/actions/change_ip_range` | `Hetzner::networks()->changeIpRange($id, $ipRange)` |

## Firewalls

| HTTP Method | API Endpoint | SDK Method |
| ----------- | ------------ | ---------- |
| `GET` | `/firewalls` | `Hetzner::firewalls()->all()` |
| `POST` | `/firewalls` | `Hetzner::firewalls()->create($data)` |
| `GET` | `/firewalls/{id}` | `Hetzner::firewalls()->find($id)` |
| `PUT` | `/firewalls/{id}` | `Hetzner::firewalls()->update($id, $data)` |
| `DELETE` | `/firewalls/{id}` | `Hetzner::firewalls()->delete($id)` |
| `POST` | `/firewalls/{id}/actions/apply_to_resources` | `Hetzner::firewalls()->apply($firewall, $server)` |
| `POST` | `/firewalls/{id}/actions/remove_from_resources` | `Hetzner::firewalls()->remove($firewall, $server)` |
| `POST` | `/firewalls/{id}/actions/set_rules` | `Hetzner::firewalls()->setRules($firewall, $rules)` |

## Floating IPs

| HTTP Method | API Endpoint | SDK Method |
| ----------- | ------------ | ---------- |
| `GET` | `/floating_ips` | `Hetzner::floatingIps()->all()` |
| `POST` | `/floating_ips` | `Hetzner::floatingIps()->create($data)` |
| `GET` | `/floating_ips/{id}` | `Hetzner::floatingIps()->find($id)` |
| `PUT` | `/floating_ips/{id}` | `Hetzner::floatingIps()->update($id, $data)` |
| `DELETE` | `/floating_ips/{id}` | `Hetzner::floatingIps()->delete($id)` |
| `POST` | `/floating_ips/{id}/actions/assign` | `Hetzner::floatingIps()->assign($ip, $server)` |
| `POST` | `/floating_ips/{id}/actions/unassign` | `Hetzner::floatingIps()->unassign($ip)` |
| `POST` | `/floating_ips/{id}/actions/change_dns_ptr` | `Hetzner::floatingIps()->changeDnsPtr($ip, $params)` |

## Primary IPs

| HTTP Method | API Endpoint | SDK Method |
| ----------- | ------------ | ---------- |
| `GET` | `/primary_ips` | `Hetzner::primaryIps()->all()` |
| `POST` | `/primary_ips` | `Hetzner::primaryIps()->create($data)` |
| `GET` | `/primary_ips/{id}` | `Hetzner::primaryIps()->find($id)` |
| `PUT` | `/primary_ips/{id}` | `Hetzner::primaryIps()->update($id, $data)` |
| `DELETE` | `/primary_ips/{id}` | `Hetzner::primaryIps()->delete($id)` |
| `POST` | `/primary_ips/{id}/actions/assign` | `Hetzner::primaryIps()->assign($ip, $server)` |
| `POST` | `/primary_ips/{id}/actions/unassign` | `Hetzner::primaryIps()->unassign($ip)` |
| `POST` | `/primary_ips/{id}/actions/change_dns_ptr` | `Hetzner::primaryIps()->changeDnsPtr($ip, $params)` |

## Load Balancers

| HTTP Method | API Endpoint | SDK Method |
| ----------- | ------------ | ---------- |
| `GET` | `/load_balancers` | `Hetzner::loadBalancers()->all()` |
| `POST` | `/load_balancers` | `Hetzner::loadBalancers()->create($data)` |
| `GET` | `/load_balancers/{id}` | `Hetzner::loadBalancers()->find($id)` |
| `PUT` | `/load_balancers/{id}` | `Hetzner::loadBalancers()->update($id, $data)` |
| `DELETE` | `/load_balancers/{id}` | `Hetzner::loadBalancers()->delete($id)` |
| `POST` | `/load_balancers/{id}/actions/add_target` | `Hetzner::loadBalancers()->addTarget($id, $target)` |
| `POST` | `/load_balancers/{id}/actions/remove_target` | `Hetzner::loadBalancers()->removeTarget($id, $target)` |
| `POST` | `/load_balancers/{id}/actions/add_service` | `Hetzner::loadBalancers()->addService($id, $service)` |
| `POST` | `/load_balancers/{id}/actions/update_service` | `Hetzner::loadBalancers()->updateService($id, $listenPort, $service)` |
| `POST` | `/load_balancers/{id}/actions/delete_service` | `Hetzner::loadBalancers()->deleteService($id, $listenPort)` |
| `POST` | `/load_balancers/{id}/actions/change_algorithm` | `Hetzner::loadBalancers()->changeAlgorithm($id, $algorithm)` |
| `POST` | `/load_balancers/{id}/actions/change_type` | `Hetzner::loadBalancers()->changeType($id, $type)` |
| `POST` | `/load_balancers/{id}/actions/change_ip_support` | `Hetzner::loadBalancers()->changeIpSupport($id, $ipSupport)` |
| `POST` | `/load_balancers/{id}/actions/enable_public_interface` | `Hetzner::loadBalancers()->enablePublicInterface($id)` |
| `POST` | `/load_balancers/{id}/actions/disable_public_interface` | `Hetzner::loadBalancers()->disablePublicInterface($id)` |
| `POST` | `/load_balancers/{id}/actions/attach_to_network` | `Hetzner::loadBalancers()->attachToNetwork($id, $network)` |
| `POST` | `/load_balancers/{id}/actions/detach_from_network` | `Hetzner::loadBalancers()->detachFromNetwork($id, $network)` |
| `POST` | `/load_balancers/{id}/actions/change_dns_ptr` | `Hetzner::loadBalancers()->changeDnsPtr($id, $params)` |
| `GET` | `/load_balancers/{id}/metrics` | `Hetzner::loadBalancers()->metrics($id)` |

## SSH Keys

| HTTP Method | API Endpoint | SDK Method |
| ----------- | ------------ | ---------- |
| `GET` | `/ssh_keys` | `Hetzner::sshKeys()->all()` |
| `POST` | `/ssh_keys` | `Hetzner::sshKeys()->create($data)` |
| `GET` | `/ssh_keys/{id}` | `Hetzner::sshKeys()->find($id)` |
| `PUT` | `/ssh_keys/{id}` | `Hetzner::sshKeys()->update($id, $data)` |
| `DELETE` | `/ssh_keys/{id}` | `Hetzner::sshKeys()->delete($id)` |

## Images

| HTTP Method | API Endpoint | SDK Method |
| ----------- | ------------ | ---------- |
| `GET` | `/images` | `Hetzner::images()->all()` |
| `GET` | `/images/{id}` | `Hetzner::images()->find($id)` |
| `PUT` | `/images/{id}` | `Hetzner::images()->update($id, $data)` |
| `DELETE` | `/images/{id}` | `Hetzner::images()->delete($id)` |
| `POST` | `/images/{id}/actions/change_protection` | `Hetzner::images()->changeProtection($id, $params)` |

## Certificates

| HTTP Method | API Endpoint | SDK Method |
| ----------- | ------------ | ---------- |
| `GET` | `/certificates` | `Hetzner::certificates()->all()` |
| `POST` | `/certificates` | `Hetzner::certificates()->create($data)` |
| `GET` | `/certificates/{id}` | `Hetzner::certificates()->find($id)` |
| `PUT` | `/certificates/{id}` | `Hetzner::certificates()->update($id, $data)` |
| `DELETE` | `/certificates/{id}` | `Hetzner::certificates()->delete($id)` |
| `POST` | `/certificates/{id}/actions/retry` | `Hetzner::certificates()->retry($id)` |

## Placement Groups

| HTTP Method | API Endpoint | SDK Method |
| ----------- | ------------ | ---------- |
| `GET` | `/placement_groups` | `Hetzner::placementGroups()->all()` |
| `POST` | `/placement_groups` | `Hetzner::placementGroups()->create($data)` |
| `GET` | `/placement_groups/{id}` | `Hetzner::placementGroups()->find($id)` |
| `PUT` | `/placement_groups/{id}` | `Hetzner::placementGroups()->update($id, $data)` |
| `DELETE` | `/placement_groups/{id}` | `Hetzner::placementGroups()->delete($id)` |

## Locations

| HTTP Method | API Endpoint | SDK Method |
| ----------- | ------------ | ---------- |
| `GET` | `/locations` | `Hetzner::locations()->all()` |
| `GET` | `/locations/{id}` | `Hetzner::locations()->find($id)` |

## Datacenters

| HTTP Method | API Endpoint | SDK Method |
| ----------- | ------------ | ---------- |
| `GET` | `/datacenters` | `Hetzner::datacenters()->all()` |
| `GET` | `/datacenters/{id}` | `Hetzner::datacenters()->find($id)` |

## ISOs

| HTTP Method | API Endpoint | SDK Method |
| ----------- | ------------ | ---------- |
| `GET` | `/isos` | `Hetzner::isos()->all()` |
| `GET` | `/isos/{id}` | `Hetzner::isos()->find($id)` |

## Pricing

| HTTP Method | API Endpoint | SDK Method |
| ----------- | ------------ | ---------- |
| `GET` | `/pricing` | `Hetzner::pricing()->all()` |

## Server Types

| HTTP Method | API Endpoint | SDK Method |
| ----------- | ------------ | ---------- |
| `GET` | `/server_types` | `Hetzner::serverTypes()->all()` |
| `GET` | `/server_types/{id}` | `Hetzner::serverTypes()->find($id)` |

## Load Balancer Types

| HTTP Method | API Endpoint | SDK Method |
| ----------- | ------------ | ---------- |
| `GET` | `/load_balancer_types` | `Hetzner::loadBalancerTypes()->all()` |
| `GET` | `/load_balancer_types/{id}` | `Hetzner::loadBalancerTypes()->find($id)` |
