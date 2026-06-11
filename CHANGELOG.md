# Changelog

All notable changes to this project will be documented in this file.

## [1.0.0] - 2026-06-12

### Added
- Initial release of the production-ready Hetzner Cloud Laravel SDK.
- 100% coverage of all Hetzner Cloud API resources: Servers, Volumes, Networks, Firewalls, Floating IPs, Primary IPs, Load Balancers, Certificates, Placement Groups, SSH Keys, Datacenters, Locations, Images, ISOs, Pricing, Actions, Server Types, and Load Balancer Types.
- Retry mechanisms with exponential backoff and `RateLimit-Reset` header handling.
- Concurrency via Guzzle promise mapping for asynchronous calls and batch requests.
- Custom exception mapping for all HTTP error statuses.
- Configuration publish, service provider container binds, and Laravel Facade support.
- Fully compatible with PHP 7.4 through PHP 8.5.
- Supported on Laravel 8 through Laravel 13.
