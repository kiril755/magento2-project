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
                $('#custom-shipping-method-fields-details-list-wrapper').show()
            } else {
                $('#custom-shipping-method-fields-details-list-wrapper').hide()
            }
            console.log('work')
        });
    };
});
