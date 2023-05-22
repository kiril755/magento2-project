define(['jquery'], function ($) {
    'use strict';

    return function () {
        $('[name="telephone"]').on('input', function (e) {
            var input = e.target;
            var value = input.value.replace(/\D/g, '');

            if (value.startsWith('38')) {
                value = value.substring(2);
            }

            var formattedValue = '';

            if (value.length > 0) {
                if (value.length >= 4) {
                    formattedValue = '+38 (' + value.substring(0, 3) + ') ';
                    value = value.substring(3);
                } else {
                    formattedValue = '+38 ' + value.substring(0, 3);
                    value = value.substring(3);
                }

                if (value.length > 0) {
                    formattedValue += value.substring(0, 3);

                    if (value.length > 3) {
                        formattedValue += ' ' + value.substring(3, 5);

                        if (value.length > 5) {
                            formattedValue += ' ' + value.substring(5);
                        }
                    }
                }
            }



            input.value = formattedValue;
        });
    };
});
