"use strict";
define(['jquery','domReady', 'mage/url'], function ($, domReady, urlBuilder) {
    domReady(function () {
        $('#my-form').submit(  function () {
            if ($('#my-form').valid()) {
                const url = urlBuilder.build('wholesale/request/submit');
                const requestData = {
                    'personalIncomeTax': $("input[name='inn']").val(),
                    'text': $("textarea[name='text']").val(),
                };
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    delay: 100,
                    params: {
                        contentType: 'application/json; charset=utf-8'
                    },
                    data: requestData,
                    success: function (response) {
                        if (response.success) {
                            $('#my-form').after(response.success)
                            $('#my-form').hide();
                        } else {
                            console.log('response.error');
                        }
                    },
                    error: function (error) {
                        console.log("An error occurred: " + error);
                    }
                });
            } else {
                console.log('form is not validate!');
            }
            return false;
        });
    })
});
