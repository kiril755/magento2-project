define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';

    return function (setShippingInformationAction) {

        return wrapper.wrap(setShippingInformationAction, function (originalAction) {
            const selectedMethodCode = quote.shippingMethod().method_code;
            const quoteAddressArr = [quote.shippingAddress(), quote.billingAddress()];
            var state, addressName, houseNumberField, apartmentNumberField, street;

            if (selectedMethodCode === 'ukrposhta') {
                state = $('[name="extension_attributes[ukrposhta_state_field]"] option:selected').text()
                addressName = $('[name="extension_attributes[ukrposhta_street_field]"]').val();
                houseNumberField = $('[name="extension_attributes[ukrposhta_house_number_field]"]').val();
                apartmentNumberField = $('[name="extension_attributes[ukrposhta_apartment_number_field]"]').val() === "" ? null : $('[name="extension_attributes[ukrposhta_apartment_number_field]"]').val();
            } else if (selectedMethodCode === 'freeshipping') {
                state = $('[name="extension_attributes[free_state_field]"] option:selected').text()
                addressName = $('[name="extension_attributes[free_street_field]"]').val();
                houseNumberField = $('[name="extension_attributes[free_house_number_field]"]').val();
                apartmentNumberField = $('[name="extension_attributes[free_apartment_number_field]"]').val() === "" ? null : $('[name="extension_attributes[free_apartment_number_field]"]').val();
            }
            street = `${addressName}, ${houseNumberField}, ${apartmentNumberField}`;
            quoteAddressArr.map((address) => {
                address.city = 'Київ';
                address.region = state;
                address.street = [street, ''];
                address['extensionAttributes'] = {};
            })
            return originalAction();
        });
    };
});
