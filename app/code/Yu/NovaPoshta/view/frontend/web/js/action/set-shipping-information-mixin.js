define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';

    return function (setShippingInformationAction) {

        return wrapper.wrap(setShippingInformationAction, function (originalAction) {
            var shippingAddress = quote.shippingAddress();
            var billingAddress = quote.billingAddress();
            if (shippingAddress['extension_attributes'] == undefined) {
                shippingAddress['extension_attributes'] = {};
            }
            if (quote.shippingMethod().method_code == 'customshipping') {
                return originalAction();
            }

            var city = $('[name="shippingAddress.city_novaposhta_ref"]').find('.select2-selection__rendered').text();

            if (quote.shippingMethod().method_code == 'novaposhta_to_warehouse') {
                var street = $('[name="warehouse_novaposhta_id"] option:selected').text();

                shippingAddress.region = '';
                shippingAddress.street = [street, ''];
                shippingAddress.city = city;
                shippingAddress['extension_attributes']['warehouse_novaposhta_address'] = $('[name="warehouse_novaposhta_id"] option:selected').text();

                billingAddress.region = '';
                billingAddress.street = [street, ''];
                billingAddress.city = city;
            } else if (quote.shippingMethod().method_code == 'novaposhta_to_door') {
                var street = $('[name="novaposhta_door"]').val();

                shippingAddress.region = '';
                shippingAddress.street = [street, ''];
                shippingAddress.city = city;

                billingAddress.region = '';
                billingAddress.street = [street, ''];
                billingAddress.city = city;
            }

            shippingAddress['extension_attributes']['city_novaposhta_ref'] = $('[name="city_novaposhta_ref"]').val();
            shippingAddress['extension_attributes']['warehouse_novaposhta_id'] = $('[name="warehouse_novaposhta_id"]').val();

            return originalAction();
        });
    };
});
