define([
    'jquery',
    'ko',
    'uiRegistry',
    'mage/translate',
    'Magento_Checkout/js/model/quote',
], function ($, ko, registry, $t, quote) {
    'use strict';

    return function (Target) {
        return Target.extend({
            validateShippingInformation: function () {
                let originalResult = this._super();
                if (!originalResult) {
                    return originalResult;
                }
                if (!quote.shippingMethod()['carrier_code'].startsWith('sendypickuppoint_')) {
                    return true;
                }
                let pickupPointComponent = registry.get('checkout.steps.shipping-step.shippingAddress.shippingAdditional.pickup-point');
                if (!pickupPointComponent.hasSelectedPickupPoint()) {
                    pickupPointComponent.pickupPointRequiredMessage(true);
                    return false;
                }
                return true;
            }
        });
    }
});
