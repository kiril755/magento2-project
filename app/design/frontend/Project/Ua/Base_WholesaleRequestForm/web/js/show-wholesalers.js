"use strict";
define(['jquery','domReady', 'mage/url'], function ($, domReady, urlBuilder) {
    domReady(function () {
        $('.region-item').on('click', function () {
                const url = urlBuilder.build('wholesale/wholesalers/ajax');
                const currentItem = $(this);
                const requestData = {
                    'state': $(this).data('state')
                };
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    delay: 100,
                    params: {
                        contentType: 'application/json; charset=utf-8'
                    },
                    data: requestData,
                    showLoader: true,
                    success: function (response) {
                        if (response.success) {
                            $(".wholesalers-list").empty()
                            $(".wholesalers-list").append(response.success)
                            $(".active").removeClass("active");
                            currentItem.addClass("active");
                            window.scrollTo(0, 0);
                            console.log('success')
                        } else {
                            console.log('response.error');
                        }
                    },
                    error: function (error) {
                        console.log("An error occurred: " + error);
                    }
                });
            return false;
        });
    })
});
