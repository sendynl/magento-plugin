# Sendy for Magento 2

Sendy plug-in for Magento 2 enables integration with the [Sendy][sendy] shipment platform.

## Installation

### Magento Marketplace

The recommended way of installing is through Magento Marketplace, where you can
find [Sendy][marketplace].

### Activate module

1. Enter following commands to enable extension:
   ```bash
   php bin/magento module:enable Edifference_Sendy
   php bin/magento setup:upgrade
   php bin/magento cache:clean
   ```
2. Configure extension as per configuration instructions

## Configuration
1. Log in to Magento Admin
2. Go to Stores > Configuration > Sendy and configure settings

[sendy]: https://sendy.nl
[marketplace]: https://marketplace.magento.com/
