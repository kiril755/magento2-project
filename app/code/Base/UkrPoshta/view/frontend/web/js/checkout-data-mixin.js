/*jshint browser:true jquery:true*/
/*global alert*/
define([
    'jquery',
    'Magento_Customer/js/customer-data'
], function ($, storage) {
    'use strict';


    return function (checkoutData) {

        var mixin = {

            myCustomFunction: function() {
                debugger
                var ex = $('[name="shippingAddress.extension_attributes.ukrposhta_state_field"]').show();
            }

        };

        return $.extend(checkoutData, mixin);
    };
});
