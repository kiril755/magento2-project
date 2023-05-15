<?php

namespace Base\UkrPoshta\Plugin;

use Magento\Framework\HTTP\Client\Curl;
use Base\UkrPoshta\Model\ResourceModel\State\CollectionFactory;

class LayoutProcessor
{
    private $collectionFactory;
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }
    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array                                            $jsLayout
    )
    {
        $collection = $this->collectionFactory->create();
        $options = [];

        $states = $collection->getData();
        foreach ($states as $state) {
            $options[] = [
                'value' => $state['state'],
                'label' => $state['state'],
            ];
        }

        $validation['required-entry'] = true;
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['custom-shipping-method-fields']['children']['ukrposhta_state_field'] = [
            'component' => "Magento_Ui/js/form/element/select",
            'config' => [
                'customScope' => 'shippingAddress.extension_attributes',
                'template' => 'ui/form/field',
                'elementTmpl' => "ui/form/element/select"
            ],
            'dataScope' => 'shippingAddress.extension_attributes.ukrposhta_state_field',
            'label' => "Область",
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => $validation,
            'sortOrder' => 2,
            'options' => $options
        ];

        return $jsLayout;
    }
}
