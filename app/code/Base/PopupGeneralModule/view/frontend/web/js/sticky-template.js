define(
    [
        'jquery',
        'uiComponent',
        'ko',
        'Magento_Customer/js/customer-data',
        'Base_PopupGeneralModule/js/section'
    ]
    ,function ($, Component, ko, customerData) {
        'use strict';
            return Component.extend({

                defaults: {
                    template: 'Base_PopupGeneralModule/sticky-template'
                },

                initialize: function (config) {
                    this._super();
                    customerData.reload();
                    this.custom_section_popup = customerData.get('custom_section_popup');
                    this.storeCode = ko.observable(config.currentStoreCode);
                    this.isStickyVisibleComputed = ko.computed(this.isStickyVisible, this);

                },
                isStickyVisible: function () {
                    var pagesFromScopeArr = this.custom_section_popup().pagesFromScope;
                    var currentPageTitleValue = $.cookie("currentPageTitle") ? decodeURIComponent($.cookie("currentPageTitle")) : '';
                    var popupWasClosedCheck = $.cookie(`popupCookie${this.storeCode()}`) ? decodeURIComponent($.cookie(`popupCookie${this.storeCode()}`)).split(',') : [];
                    if (pagesFromScopeArr.includes(currentPageTitleValue) && popupWasClosedCheck.includes(currentPageTitleValue)) {
                        return true
                    }
                    return false;
                }
            });
    });
