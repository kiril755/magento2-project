define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';

    return function (selectShippingMethodAction) {

        return wrapper.wrap(selectShippingMethodAction, function (originalAction) {
            originalAction();
            var method = quote.shippingMethod();
            var selectedMethod = method != null ? method.carrier_code + '_' + method.method_code : null;

            if (selectedMethod == 'customshipping_customshipping') {
                $('[name="shippingAddress.extension_attributes.ukrposhta_state_field"]').show()
                $('[name="shippingAddress.extension_attributes.ukrposhta_street_field"]').show()
                $('[name="shippingAddress.extension_attributes.ukrposhta_house_number_field"]').show()
                $('[name="shippingAddress.extension_attributes.ukrposhta_apartment_number_field"]').show()

                $('[name="shippingAddress.extension_attributes.free_state_field"]').hide()
                $('[name="shippingAddress.extension_attributes.free_street_field"]').hide()
                $('[name="shippingAddress.extension_attributes.free_house_number_field"]').hide()
                $('[name="shippingAddress.extension_attributes.free_apartment_number_field"]').hide()
            } else if (selectedMethod == 'freeshipping_freeshipping') {
                $('[name="shippingAddress.extension_attributes.free_state_field"]').show()
                $('[name="shippingAddress.extension_attributes.free_street_field"]').show()
                $('[name="shippingAddress.extension_attributes.free_house_number_field"]').show()
                $('[name="shippingAddress.extension_attributes.free_apartment_number_field"]').show()

                $('[name="shippingAddress.extension_attributes.ukrposhta_state_field"]').hide()
                $('[name="shippingAddress.extension_attributes.ukrposhta_street_field"]').hide()
                $('[name="shippingAddress.extension_attributes.ukrposhta_house_number_field"]').hide()
                $('[name="shippingAddress.extension_attributes.ukrposhta_apartment_number_field"]').hide()
            }
        });
    };
});
