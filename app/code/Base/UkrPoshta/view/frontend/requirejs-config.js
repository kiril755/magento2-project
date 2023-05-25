var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/set-shipping-information': {
                'Base_UkrPoshta/js/action/set-shipping-information-mixin': true
            },
            'Magento_Checkout/js/action/select-shipping-method': {
                'Base_UkrPoshta/js/action/select-shipping-method-mixin': true
            },
            'Magento_Ui/js/lib/validation/validator': {
                'Base_UkrPoshta/js/my-validation': true
            }

        }
    }
};
