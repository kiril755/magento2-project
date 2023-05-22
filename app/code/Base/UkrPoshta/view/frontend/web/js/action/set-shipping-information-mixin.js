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
            // shippingAddress['extensionAttributes'] = {};
            // if (quote.shippingMethod().method_code == 'novaposhta_to_warehouse' || quote.shippingMethod().method_code == 'novaposhta_to_door') {
            //     shippingAddress['extensionAttributes'] = {};
            //     billingAddress['extensionAttributes'] = {};
            // } else if (quote.shippingMethod().method_code == 'customshipping') {
            //     shippingAddress['extensionAttributes'] = {
            //         'state_field': $('[name="extension_attributes[ukrposhta_state_field]"] option:selected').text(),
            //         'street_field': $('[name="extension_attributes[ukrposhta_street_field]"]').val(),
            //         'house_number_field': $('[name="extension_attributes[ukrposhta_house_number_field]"]').val(),
            //         'apartment_number_field': $('[name="extension_attributes[ukrposhta_apartment_number_field]"]').val() == "" ? null : $('[name="extension_attributes[ukrposhta_apartment_number_field]"]').val()
            //     };
            // } else {
            //     shippingAddress['extensionAttributes'] = {
            //         'state_field': $('[name="extension_attributes[free_state_field]"] option:selected').text(),
            //         'street_field': $('[name="extension_attributes[free_street_field]"]').val(),
            //         'house_number_field': $('[name="extension_attributes[free_house_number_field]"]').val(),
            //         'apartment_number_field': $('[name="extension_attributes[free_apartment_number_field]"]').val() == "" ? null : $('[name="extension_attributes[free_apartment_number_field]"]').val()
            //     };
            // }
            //
            // const state = shippingAddress['extensionAttributes']['state_field'];
            // const street = shippingAddress['extensionAttributes']['street_field'];
            // const houseNumber = shippingAddress['extensionAttributes']['house_number_field'];
            // const apartmentNumber = shippingAddress['extensionAttributes']['apartment_number_field'] !== null && shippingAddress['extensionAttributes']['ukrposhta_apartment_number_field'] !== "" ? shippingAddress['extensionAttributes']['ukrposhta_apartment_number_field'] : null;
            // const streetStreetData = `${street}, ${houseNumber}, ${apartmentNumber}`;

            if (quote.shippingMethod().method_code == 'customshipping') {
                var state = $('[name="extension_attributes[ukrposhta_state_field]"] option:selected').text()
                const address = $('[name="extension_attributes[ukrposhta_street_field]"]').val();
                const houseNumberField = $('[name="extension_attributes[ukrposhta_house_number_field]"]').val();
                const apartmentNumberField = $('[name="extension_attributes[ukrposhta_apartment_number_field]"]').val() == "" ? null : $('[name="extension_attributes[ukrposhta_apartment_number_field]"]').val();

                var street = `${address}, ${houseNumberField}, ${apartmentNumberField}`;
            } else if (quote.shippingMethod().method_code == 'freeshipping') {
                var state = $('[name="extension_attributes[free_state_field]"] option:selected').text()
                const address = $('[name="extension_attributes[free_street_field]"]').val();
                const houseNumberField = $('[name="extension_attributes[free_house_number_field]"]').val();
                const apartmentNumberField = $('[name="extension_attributes[free_apartment_number_field]"]').val() == "" ? null : $('[name="extension_attributes[free_apartment_number_field]"]').val();

                var street = `${address}, ${houseNumberField}, ${apartmentNumberField}`;
            }
            debugger
            shippingAddress.city = 'Київ';
            shippingAddress.region = state;
            shippingAddress.street = [street, ''];

            billingAddress.city = 'Київ';
            billingAddress.region = state;
            billingAddress.street = [street, ''];

            shippingAddress['extensionAttributes'] = {};
            billingAddress['extensionAttributes'] = {};
            return originalAction();
        });
    };
});
