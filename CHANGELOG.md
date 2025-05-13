# Changelog
## [1.0.0] - 2025-01-08
### Added
- Initial version

## [1.0.1] - 2025-01-14
### Improvements
- Address improvements for creating shipments
- Prevent disabled module status when finalizing oauth flow

## [1.0.2] - 2025-01-14
### Fixes
- Fix config value bug breaking the module

## [1.0.3] - 2025-01-28
### Improvements
- Add logging of post requests
- Add ability to logout from sendy

### Fixes
- Fix issues when module is disabled
- Fix shipments when magento has only 1 street line

## [1.0.4] - 2025-03-18
### Fixes
- Fix issues with changing oauth client id and secret

## [1.0.5] - 2025-04-07
### Improvements
- Allow shipping label to be created on a shipped or partially shipped order
- New feature which allows a order status change on creating a sendy shipment

## [1.0.6] - 2025-05-12
### Improvements
- Implement sendy webhooks for handling orders in sendy app

## [1.0.7] - 2025-05-13
### Improvements
- Improved logging of webhooks
### Fixes
- Fixed logging not working correctly

## [1.0.8] - 2025-05-13
### Improvements
- Add logging of put requests
### Fixes
- Refactor carriers to prevent mismatch of shipping methods with sendy app

## [1.0.9] - 2025-05-13
### Fixes
- Catch shipment.generated webhook
