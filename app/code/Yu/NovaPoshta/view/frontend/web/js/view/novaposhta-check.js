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
            cityDefault: true,
            state: true,
            cityNovaposhta: false,
            streetDefault: true,
            street: '',
            value: 0,
            valueMap: {false: '', true: 'Нова Пошта'},
            exports: {
                "cityDefault": "${ $.parentName }.city:visible",
                "state": "${ $.parentName }.state:visible",
                "cityNovaposhta": "${ $.parentName }.city_novaposhta_ref:visible",
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
            this.observe('cityDefault');
            this.observe('state');
            this.observe('cityNovaposhta');
            this.observe('streetDefault');
            this.observe('street');
            this.observe('value');
            return this;
        },


        onExtendedValueChanged: function (newExportedValue) {
            this._super(newExportedValue);
            var check = 0;
            if (this.value() !== '') {
                this.cityDefault(false);
                this.state(false);
                this.streetDefault(false);
                this.cityNovaposhta(true);
                this.street('-');
                $(".street").hide();
                // $("[name='custom_attributes[ukrposhta_check]']").prop('checked', false);
                // $("[name='custom_attributes[ukrposhta_check]']").attr('value', 0);
                setTimeout(function () {
                    $("[name='custom_attributes[ukrposhta_check]']").prop('checked', false);
                    $("[name='custom_attributes[ukrposhta_check]']").val('0');
                }, 300);
                check = 1;
            } else {
                this.cityDefault(true);
                this.state(true);
                this.streetDefault(true);
                this.cityNovaposhta(false);
                $(".street").show();
            }
            var address = quote.shippingAddress();
            rateRegistry.set(address.getKey(), null);
            rateRegistry.set(address.getCacheKey(), null);

                address.customAttributes = {}
            // address.customAttributes = _.extend(address.customAttributes, [{
            //     "attribute_code": "novaposhta_check",
            //     "value": this.value()
            // }]);
            address.customAttributes = [{
                "attribute_code": "novaposhta_check",
                "value": this.value()
            }];
            uiRegistry.set('shippingAddress', address);
            quote.shippingAddress(address);
        }
    });
});
