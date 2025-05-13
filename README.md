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
3. Connect to the Sendy application

   After enabling the module, you must connect the magento module to the sendy application with oauth.
   Press the “connect to sendy” link to go to the sendy application. There you will need to authorize your magento store. After this you can start using the sendy module in your magento store.

[sendy]: https://sendy.nl
[marketplace]: https://commercemarketplace.adobe.com/edifference-sendy.html
