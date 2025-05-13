define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/resource-url-manager',
    'Magento_Checkout/js/model/quote',
], function ($, ko, Component, resourceUrlManager, quote) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Edifference_Sendy/checkout/shipping/pickupPoint'
        },
        availableCarriers: [],

        initObservable: function () {
            this.pickupPointLocation = ko.observable(null);
            this.hasSelectedPickupPoint = ko.observable(false);
            this.showButton = ko.observable(false);
            this.pickupPointRequiredMessage = ko.observable(false);
            this.errorValidationMessage = ko.observable(false);
            this.errorMessage = ko.observable(false);
            let _this = this;

            this.selectedMethod = ko.computed(function () {
                let method = quote.shippingMethod();
                let selectedMethod = method != null ? method.carrier_code : null;
                if (!selectedMethod) {
                    return selectedMethod;
                }
                this.showButton(false);
                if (
                    this.selectedMethod() !== null && // if not first empty load
                    this.selectedMethod() !== selectedMethod && // and selected method changed
                    this.selectedMethod().startsWith('sendypickuppoint_') // and old value is sendypickup point
                ) {
                    this.pickupPointLocation(null);
                    this.hasSelectedPickupPoint(false);
                    $.ajax({
                        url: '/' + _this.getPickupPointUrl(quote),
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({'id': ''})
                    });
                }
                if (selectedMethod.startsWith('sendypickuppoint_')) {
                    this.showButton(true);
                }
                return selectedMethod;
            }, this);
            this.selectedCarrier = ko.computed(function () {
                let method = quote.shippingMethod();
                if (method === null) {
                    return null;
                }
                switch (method.method_code) {
                    case 'sendypickuppoint_postnl':
                        return 'PostNL';
                    case 'sendypickuppoint_dhl':
                        return 'DHL';
                    case 'sendypickuppoint_dpd':
                        return 'DPD';
                    default:
                        return method.method_code;
                }
            }, this);

            return this;
        },

        /**
         * Get pickup point url
         */
        getPickupPointUrl: function (quote) {
            var params = resourceUrlManager.getCheckoutMethod() == 'guest' ?
                    {
                        quoteId: quote.getQuoteId()
                    } : {},
                urls = {
                    'guest': '/guest-carts/:quoteId/sendy/pickuppoint/save',
                    'customer': '/carts/mine/sendy/pickuppoint/save'
                };

            return resourceUrlManager.getUrl(urls, params);
        },

        /**
         * Show pickup point Mapbox on click
         */
        showPickupPointPicker : function () {
            let _this = this;
            let addressFormData = new FormData(document.getElementById('co-shipping-form'));
            let address = addressFormData.get('street[0]');
            let houseNumber = addressFormData.get('street[1]') ?? '';
            let addition = addressFormData.get('street[2]') ?? '';
            let zipCode = addressFormData.get('postcode') ?? '';
            let country = addressFormData.get('country_id') ?? '';

            if (!zipCode || !country) {
                this.errorValidationMessage(true);
            } else {
                this.errorValidationMessage(false);
                this.pickupPointRequiredMessage(false);
                this.errorMessage(false);
                Sendy.parcelShopPicker.open(
                    {
                        country: addressFormData.get('country_id'),
                        carriers: [this.selectedCarrier()],
                        address: address + ' ' + houseNumber + addition + ' ' + zipCode,
                    },
                    (data) => {
                        let pickupPointData = {
                            name: data.name,
                            street: data.street,
                            number: data.number,
                            postal_code: data.postal_code,
                            city: data.city,
                            id: data.id
                        };
                        $.ajax({
                            url: '/' + _this.getPickupPointUrl(quote),
                            type: 'POST',
                            contentType: 'application/json',
                            data: JSON.stringify(pickupPointData),
                            success: function (json) {
                                _this.pickupPointLocation(pickupPointData);
                                _this.hasSelectedPickupPoint(true);
                            },
                            error: function (xhr, status, error) {
                                _this.errorMessage(true);
                            }
                        });
                    },
                    (errors) => {
                        for (const error in errors) {
                            alert(error);
                        }
                    }
                );
            }
        },
    });
});
