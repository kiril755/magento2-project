define([
    'jquery',
    'uiComponent',
    'Magento_Customer/js/customer-data'
], function ($, Component, customerData) {
    'use strict';

    return Component.extend({
        initialize: function () {
            this._super();
            customerData.reload();
            this.custom_section_popup = customerData.get('custom_section_popup');

            var currentPageTitle = $('meta[name=title]').attr('content');
            $.cookie("currentPageTitle", currentPageTitle, { expires: 10/1440, path: '/' });
        }
    });
});
