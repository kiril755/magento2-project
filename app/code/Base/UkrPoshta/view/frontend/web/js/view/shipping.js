
define(
    [
        'jquery',
        'underscore',
        'Magento_Ui/js/form/form',
        'ko',
        'Magento_Customer/js/model/customer',
        'Magento_Customer/js/model/address-list',
        'Magento_Checkout/js/model/address-converter',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/action/create-shipping-address',
        'Magento_Checkout/js/action/select-shipping-address',
        'Magento_Checkout/js/model/shipping-rates-validator',
        'Magento_Checkout/js/model/shipping-address/form-popup-state',
        'Magento_Checkout/js/model/shipping-service',
        'Magento_Checkout/js/action/select-shipping-method',
        'Magento_Checkout/js/model/shipping-rate-registry',
        'Magento_Checkout/js/action/set-shipping-information',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Ui/js/modal/modal',
        'Magento_Checkout/js/model/checkout-data-resolver',
        'Magento_Checkout/js/checkout-data',
        'uiRegistry',
        'mage/translate',
        'Magento_Checkout/js/model/shipping-rate-service'
    ],function (
        $,
        _,
        Component,
        ko,
        customer,
        addressList,
        addressConverter,
        quote,
        createShippingAddress,
        selectShippingAddress,
        shippingRatesValidator,
        formPopUpState,
        shippingService,
        selectShippingMethodAction,
        rateRegistry,
        setShippingInformationAction,
        stepNavigator,
        modal,
        checkoutDataResolver,
        checkoutData,
        registry,
        $t) {
        'use strict';

        var mixin = {
            initialize: function () {
                this._super();

                this.runMixinLogic();
            },

            runMixinLogic: function () {
                // Your mixin logic here
                if (addressList().length > 0) {
                    debugger
                    var customerState = addressList()[0].region;
                    var street = addressList()[0].street[0];

                    var statesArr = $('[name="extension_attributes[ukrposhta_state_field]"]').val();

                    var foundState = statesArr.find(function(state) {
                        return state.toLowerCase().includes(customerState.toLowerCase());
                    });

                    const prohibitedValues = ['Відділення', 'Отделение', 'Поштомат', 'Почтомат', 'Пункт'];

                    if (!prohibitedValues.some(value => street.includes(value))) {
                        var fullStreet = street.split(',');

                        $("[name='extension_attributes[ukrposhta_street_field]']").val(fullStreet[0]);
                        $("[name='extension_attributes[ukrposhta_house_number_field]']").val(fullStreet[1]);
                        $("[name='extension_attributes[ukrposhta_apartment_number_field]']").val(fullStreet[2]);
                    }

                }
            }
        };

        return function (target) {
            return target.extend(mixin);
        };
    });

