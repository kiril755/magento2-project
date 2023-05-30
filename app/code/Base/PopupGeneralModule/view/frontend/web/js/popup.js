define(
    [
        'jquery',
        'Magento_Ui/js/modal/modal',
        'jquery/jquery.cookie'
    ],
    function (
        $,
        modal
    ) {
        'use strict';
        return function (config) {
            var modaloption = {
                type: 'popup',
                modalClass: 'modal-popup',
                responsive: true,
                innerScroll: true,
                clickableOverlay: true,
                title: 'Simple Modal'
            };
            var callforoption = modal(modaloption, $('.callfor-popup'));
            $('.callfor-popup').modal('openModal');

            $('.modal-popup').on('modalclosed', function () {
                var currentUrl = config.currentPageName;
                var existingValue = $.cookie("popupCookie") || '';
                var newValue = existingValue ? existingValue + `,${currentUrl}` : currentUrl;
                $.cookie("popupCookie", encodeURIComponent(newValue), { expires: 10/1440, path: '/' });
            });
        }
    }
);
