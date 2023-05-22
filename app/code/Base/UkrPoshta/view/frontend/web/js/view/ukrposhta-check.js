define([
    'jquery',
    'underscore',
    'Magento_Ui/js/form/element/single-checkbox',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/shipping-rate-registry',
    'uiRegistry'
], function ($, _, Checkbox, quote, rateRegistry, uiRegistry) {
    'use strict';

    return Checkbox.extend({

        defaults: {

            ukrPoshtaState: false,
            ukrPoshtaStreet: false,
            ukrPoshtaHouseNumber: false,
            ukrPoshtaApartmentNumber: false,
            street: '',
            value: 0,
            valueMap: {false: '', true: 'Укрпошта'},
            exports: {
                "cityDefault": "${ $.parentName }.city:visible",
                "state": "${ $.parentName }.state:visible",
                "cityNovaposhta": "${ $.parentName }.city_novaposhta_ref:visible",
                "ukrPoshtaState": "${ $.parentName }.ukrposhta_state_field:visible",
                "ukrPoshtaStreet": "${ $.parentName }.extension_attributes.ukrposhta_street_field:visible",
                "ukrPoshtaHouseNumber": "${ $.parentName }.extension_attributes.ukrposhta_house_number_field:visible",
                "ukrPoshtaApartmentNumber": "${ $.parentName }.extension_attributes.ukrposhta_apartment_number_field:visible",
                "streetDefault": "${ $.parentName }.street:visible",
                "street": "${ $.parentName }.street.0:value"
            }
        },

        initialize: function () {
            this._super();
            return this;
        },

        initObservable: function () {
            this._super();
            this.observe('cityNovaposhta');
            this.observe('ukrPoshtaState');
            this.observe('ukrPoshtaStreet');
            this.observe('ukrPoshtaHouseNumber');
            this.observe('ukrPoshtaApartmentNumber');
            this.observe('street');
            this.observe('value');
            return this;
        },

        onExtendedValueChanged: function (newExportedValue) {
            this._super(newExportedValue);
            var check = 0;
            if (this.value() !== '') {
                this.cityNovaposhta(false);
                this.ukrPoshtaState(true);
                this.ukrPoshtaStreet(true);
                this.ukrPoshtaHouseNumber(true);
                this.ukrPoshtaApartmentNumber(true);
                // $("[name='custom_attributes[novaposhta_check]']").prop('checked', false);
                // $("[name='custom_attributes[novaposhta_check]']").attr('value', 0);
                setTimeout(function () {
                    $("[name='custom_attributes[novaposhta_check]']").prop('checked', false);
                    $("[name='custom_attributes[novaposhta_check]']").val('0');
                }, 1100);
                check = 1;
            } else {
                this.cityNovaposhta(true);
                this.ukrPoshtaState(false);
                this.ukrPoshtaStreet(false);
                this.ukrPoshtaHouseNumber(false);
                this.ukrPoshtaApartmentNumber(false);
            }
            var address = quote.shippingAddress();
            rateRegistry.set(address.getKey(), null);
            rateRegistry.set(address.getCacheKey(), null);

                address.customAttributes = {}
            // address.customAttributes = _.extend(address.customAttributes, [{
            //     "attribute_code": "ukrposhta_check",
            //     "value": this.value()
            // }]);
            address.customAttributes = [{
                "attribute_code": "ukrposhta_check",
                "value": this.value()
            }];
            uiRegistry.set('shippingAddress', address);
            quote.shippingAddress(address);
        }
    });
});
