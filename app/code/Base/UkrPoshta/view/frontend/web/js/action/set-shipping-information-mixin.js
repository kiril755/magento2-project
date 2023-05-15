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

            if (quote.shippingMethod().method_code == 'novaposhta_to_warehouse') {
                shippingAddress['extensionAttributes'] = {};
                billingAddress['extensionAttributes'] = {};
            } else {
                if (!shippingAddress['extensionAttributes']) {
                    shippingAddress['extensionAttributes'] = {
                        'ukrposhta_state_field': $('[name="extension_attributes[ukrposhta_state_field]"] option:selected').text(),
                        'ukrposhta_street_field': $('[name="extension_attributes[ukrposhta_street_field]"]').val(),
                        'ukrposhta_house_number_field': $('[name="extension_attributes[ukrposhta_house_number_field]"]').val(),
                        'ukrposhta_apartment_number_field': $('[name="extension_attributes[ukrposhta_apartment_number_field]"]').val() == "" ? null : $('[name="extension_attributes[ukrposhta_apartment_number_field]"]').val()
                    };
                }
                const state = shippingAddress['extensionAttributes']['ukrposhta_state_field'];
                const street = shippingAddress['extensionAttributes']['ukrposhta_street_field'];
                const houseNumber = shippingAddress['extensionAttributes']['ukrposhta_house_number_field'];
                const apartmentNumber = shippingAddress['extensionAttributes']['ukrposhta_apartment_number_field'] !== null ? shippingAddress['extensionAttributes']['ukrposhta_apartment_number_field'] : '';
                debugger
                const streetStreetData = `${street}, ${houseNumber}, ${apartmentNumber}`;

                shippingAddress.city = 'Київ';
                shippingAddress.region = state;
                shippingAddress.street = [streetStreetData, ''];

                billingAddress.city = 'Київ';
                billingAddress.region = state;
                billingAddress.street = [streetStreetData, ''];
            }

            return originalAction();
        });
    };
});
