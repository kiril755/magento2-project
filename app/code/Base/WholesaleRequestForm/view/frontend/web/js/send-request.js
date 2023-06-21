"use strict";
define(['jquery','domReady', 'mage/url'], function ($, domReady, urlBuilder) {
    domReady(function () {
        $('#my-form').submit(  function (e) {
            e.preventDefault();
            if ($('#my-form').valid()) {
                const url = urlBuilder.build('wholesale/request/submit');
                var personalIncomeTax = $("input[name='inn']").val();
                var image = $("input[name='id-image']").val() ? $("input[name='id-image']").val() : null;
                var region = $("select[name='region']").val();
                var city = $("input[name='city']").val();
                var company = $("input[name='company']").val();
                var text = $("textarea[name='text']").val();

                var formData = new FormData(this);
                var file_obj = document.getElementById("id-image");
                formData.append('id-image', file_obj.files[0]);
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    delay: 100,
                    contentType: false,
                    data: formData,
                    processData: false,
                    success: function (response) {
                        if (response.success) {
                            $('#my-form').after(response.success)
                            $('#my-form').hide();
                            $('.custom-google_maps').hide();
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
