define(
    [
        'jquery',
        'uiComponent',
        'ko',
        'Magento_Customer/js/customer-data',
        'Magento_Ui/js/modal/modal',
        'jquery/jquery.cookie',
        'Base_PopupGeneralModule/js/section',
        'domReady!'
    ]
    ,function ($, Component, ko, customerData, modal) {
        'use strict';
        return Component.extend({

            defaults: {
                template: 'Base_PopupGeneralModule/popup-template'
            },

            initialize: function (config) {
                this._super();
                this.cmsBlockContent = ko.observable(config.cmsBlock);
                this.pagesFromConfigArr = ko.observable(config.pagesFromConfig);
                this.cookieLifetimeValue = ko.observable(config.cookieLifetime);
                this.storeCode = ko.observable(config.currentStoreCode);
                this.custom_section_popup = customerData.get('custom_section_popup');
            },
            isPopupVisible: function () {
                var pagesFromScopeArr = this.custom_section_popup().pagesFromScope;
                var currentPageTitleValue = this.custom_section_popup().currentPageTitle;
                var popupWasClosedCheck = this.custom_section_popup().popupWasClosedCheck;
                if (pagesFromScopeArr.includes(currentPageTitleValue) && !popupWasClosedCheck.includes(currentPageTitleValue)) {
                    return true
                }
                return false;
            },
            popupAction: function () {
                var config = {
                    pagesFromConfig: this.pagesFromConfigArr(),
                    cookieLifetime: this.cookieLifetimeValue(),
                    storeCodeValue: this.storeCode()
                };
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
                var popupWasClosedCheckArr = $.cookie(`popupCookie${config.storeCodeValue}`) ? decodeURIComponent($.cookie(`popupCookie${config.storeCodeValue}`)).split(',') : [];
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
                    var cookieName = `popupCookie${config.storeCodeValue}`;
                    var existingValue = $.cookie(cookieName) || '';
                    var newValue = existingValue ? existingValue + `,${currentPageTitleValue}` : currentPageTitleValue;
                    $.cookie(cookieName, newValue, {expires: Number(config.cookieLifetime)/3600/24, path: '/'});
                });
            }
        });
    });
