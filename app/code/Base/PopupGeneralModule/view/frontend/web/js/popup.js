define(
    [
        'jquery',
        'Magento_Ui/js/modal/modal',
        'jquery/jquery.cookie',
        'Base_PopupGeneralModule/js/section'
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

            var pagesFromScopeArr = config.pagesFromConfig.split(',');
            var popupWasClosedCheckArr = $.cookie("popupCookie") ? decodeURIComponent($.cookie("popupCookie")).split(',') : [];
            var currentPageTitleValue = $.cookie("currentPageTitle") ? decodeURIComponent($.cookie("currentPageTitle")) : '';
            if (pagesFromScopeArr.includes(currentPageTitleValue)) {
                if (popupWasClosedCheckArr.length) {
                    if (!popupWasClosedCheckArr.includes(currentPageTitleValue)) {
                        $('.callfor-popup').modal('openModal');
                    }
                } else {
                    $('.callfor-popup').modal('openModal');
                }
            }
            $('.modal-popup').on('modalclosed', function () {
                var existingValue = $.cookie("popupCookie") || '';
                var newValue = existingValue ? existingValue + `,${currentPageTitleValue}` : currentPageTitleValue;
                $.cookie("popupCookie", newValue, {expires: 10 / 1440, path: '/'});
            });
        }
    }
);
