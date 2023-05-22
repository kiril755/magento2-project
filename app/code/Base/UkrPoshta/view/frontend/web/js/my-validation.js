define([
    'jquery',
    'moment'
], function ($, moment) {
    'use strict';


    return function (validator) {

        validator.addRule(
            'customValidate',
            function (value, params) {

                var phoneNumberPattern = /^\+38 \([0-9]{3}\) [0-9]{3} [0-9]{2} [0-9]{2}$/;
                if (value.match(phoneNumberPattern)) {
                    return true;
                }
            },
            $.mage.__("Please enter phone number in form of +38 (999) 999 99 99.")
        );

        return validator;
    };
});
