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

            if (selectedMethod == 'ukrposhta_ukrposhta') {
                $('#ukrposhta-shipping-method-fields-details-list-wrapper *').show();
                $('#free-shipping-method-fields-details-list-wrapper *').hide();
            } else if (selectedMethod == 'freeshipping_freeshipping') {
                $('#free-shipping-method-fields-details-list-wrapper *').show();
                $('#ukrposhta-shipping-method-fields-details-list-wrapper *').hide();
            }
        });
    };
});
