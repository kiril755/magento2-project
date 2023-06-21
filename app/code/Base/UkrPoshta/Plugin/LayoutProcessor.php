<?php
declare(strict_types=1);

namespace Base\UkrPoshta\Plugin;

use Magento\Framework\HTTP\Client\Curl;
use Base\UkrStatesImport\Model\ResourceModel\State\CollectionFactory;
use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Quote\Api\ShippingMethodManagementInterface;
use Magento\Checkout\Block\Checkout\LayoutProcessor as CheckoutLayoutProcessor;
use Magento\Framework\Exception\LocalizedException;

class LayoutProcessor
{
    /**
     * @var CollectionFactory
     * @var AddressRepositoryInterface
     * @var CustomerSession
     * @var ShippingMethodManagementInterface
     * @var CheckoutSession
     */
    private $collectionFactory;
    private $addressRepository;
    private $customerSession;
    private $shippingManage;
    private $checkoutSession;

    /**
     * @param CollectionFactory $collectionFactory
     * @param AddressRepositoryInterface $addressRepository
     * @param CustomerSession $customerSession
     * @param ShippingMethodManagementInterface $shippingManage
     * @param CheckoutSession $checkoutSession
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        AddressRepositoryInterface $addressRepository,
        CustomerSession $customerSession,
        ShippingMethodManagementInterface $shippingManage,
        CheckoutSession $checkoutSession
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->addressRepository = $addressRepository;
        $this->customerSession = $customerSession;
        $this->shippingManage = $shippingManage;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @param CheckoutLayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     * @throws LocalizedException
     */
    public function afterProcess(
        CheckoutLayoutProcessor $subject,
        array                                            $jsLayout
    ) : array
    {
        $collection = $this->collectionFactory->create();
        $options = [];

        $states = $collection->getData();
        foreach ($states as $state) {
            $options[] = [
                'value' => __($state['state']),
                'label' => __($state['state']),
            ];
        }

        if ($this->customerSession->isLoggedIn()) {
            $customerId = $this->customerSession->getCustomer()->getId();

            $currentCustomerAddressArr = $this->customerSession->getCustomer()->getAddresses();
            if (!empty($currentCustomerAddressArr)) {
                $currentCustomerAddress = end($currentCustomerAddressArr);

                $address = $this->addressRepository->getById($currentCustomerAddress->getId())->setCustomerId($customerId);
                $address->setIsDefaultShipping(true);
                $this->addressRepository->save($address);
            } else {
                $currentCustomerAddress = false;
            }
        } else {
            $currentCustomerAddress = false;
        }

        $foundState = false;
        $city = '';
        $containsProhibitedValue = false;

        if ($currentCustomerAddress) {
            $region = $currentCustomerAddress->getRegion();
            $city = $currentCustomerAddress->getCity();
            $street = $currentCustomerAddress->getStreet()[0];

            $prohibitedValues = ['Відділення', 'Отделение', 'Поштомат', 'Почтомат', 'Пункт'];

            if ($region !== null) {
                foreach ($options as $optionState) {
                    if (mb_stripos($optionState['value']->__toString(), $region) !== false) {
                        $foundState = $optionState;
                        break;
                    }
                }
            }

            foreach ($prohibitedValues as $prohibitedValue) {
                if (mb_stripos($street, $prohibitedValue) !== false) {
                    $containsProhibitedValue = true;
                    break;
                } elseif (!$containsProhibitedValue) {
                    $fullStreet = explode(',', $street);
                }
            }
        }

        $validation['required-entry'] = true;
        $visible = false;

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['ukrposhta-shipping-method-fields']['children']['ukrposhta_state_field'] = [
            'component' => "Magento_Ui/js/form/element/select",
            'config' => [
                'customScope' => 'shippingAddress.extension_attributes',
                'template' => 'ui/form/field',
                'elementTmpl' => "ui/form/element/select"
            ],
            'dataScope' => 'shippingAddress.extension_attributes.ukrposhta_state_field',
            'label' => "Область",
            'provider' => 'checkoutProvider',
            'visible' => $visible,
            'validation' => $validation,
            'sortOrder' => 2,
            'options' => $options,
            'value' => $foundState && isset($foundState['value']) ? $foundState['value'] : $options[0]
        ];

        // ukrposhta city field
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['ukrposhta-shipping-method-fields']['children']['ukrposhta_city_field'] = [
            'component' => "Magento_Ui/js/form/element/abstract",
            'config' => [
                'customScope' => 'shippingAddress.extension_attributes',
                'template' => 'ui/form/field',
                'elementTmpl' => "ui/form/element/input"
            ],
            'dataScope' => 'shippingAddress.extension_attributes.ukrposhta_city_field',
            'label' => "Місто",
            'provider' => 'checkoutProvider',
            'visible' => $visible,
            'validation' => $validation,
            'value' => $city
        ];

        // ukrposhta_street_field

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['ukrposhta-shipping-method-fields']['children']['ukrposhta_street_field'] = [
            'component' => "Magento_Ui/js/form/element/abstract",
            'config' => [
                'customScope' => 'shippingAddress.extension_attributes',
                'template' => 'ui/form/field',
                'elementTmpl' => "ui/form/element/input"
            ],
            'dataScope' => 'shippingAddress.extension_attributes.ukrposhta_street_field',
            'label' => "Вулиця",
            'provider' => 'checkoutProvider',
            'visible' => $visible,
            'validation' => $validation,
            'value' => !$containsProhibitedValue && isset($fullStreet[0])? $fullStreet[0] : ''
        ];

        //ukrposhta_house_number_field

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['ukrposhta-shipping-method-fields']['children']['ukrposhta_house_number_field'] = [
            'component' => "Magento_Ui/js/form/element/abstract",
            'config' => [
                'customScope' => 'shippingAddress.extension_attributes',
                'template' => 'ui/form/field',
                'elementTmpl' => "ui/form/element/input"
            ],
            'dataScope' => 'shippingAddress.extension_attributes.ukrposhta_house_number_field',
            'label' => "Номер будинку",
            'provider' => 'checkoutProvider',
            'visible' => $visible,
            'validation' => [
                'required-entry' => $validation,
                'validate-number' => true,
            ],
            'value' => !$containsProhibitedValue && isset($fullStreet[1]) ? $fullStreet[1] : null
        ];

        // ukrposhta_apartment_number_field

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['ukrposhta-shipping-method-fields']['children']['ukrposhta_apartment_number_field'] = [
            'component' => "Magento_Ui/js/form/element/abstract",
            'config' => [
                'customScope' => 'shippingAddress.extension_attributes',
                'template' => 'ui/form/field',
                'elementTmpl' => "ui/form/element/input"
            ],
            'dataScope' => 'shippingAddress.extension_attributes.ukrposhta_apartment_number_field',
            'label' => "Номер квартири",
            'provider' => 'checkoutProvider',
            'visible' => $visible,
            'validation' => [
                'required-entry' => false,
                'validate-number' => true,
            ],
            'value' => !$containsProhibitedValue && isset($fullStreet[2]) ? $fullStreet[2] : null
        ];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['free-shipping-method-fields']['children']['free_state_field'] = [
            'component' => "Magento_Ui/js/form/element/select",
            'config' => [
                'customScope' => 'shippingAddress.extension_attributes',
                'template' => 'ui/form/field',
                'elementTmpl' => "ui/form/element/select"
            ],
            'dataScope' => 'shippingAddress.extension_attributes.free_state_field',
            'label' => "Область",
            'provider' => 'checkoutProvider',
            'visible' => $visible,
            'validation' => $validation,
            'sortOrder' => 2,
            'options' => $options,
            'value' => $foundState && isset($foundState['value']) ? $foundState['value'] : $options[0]
        ];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['free-shipping-method-fields']['children']['free_city_field'] = [
            'component' => "Magento_Ui/js/form/element/abstract",
            'config' => [
                'customScope' => 'shippingAddress.extension_attributes',
                'template' => 'ui/form/field',
                'elementTmpl' => "ui/form/element/input"
            ],
            'dataScope' => 'shippingAddress.extension_attributes.free_city_field',
            'label' => "Місто",
            'provider' => 'checkoutProvider',
            'visible' => $visible,
            'validation' => $validation,
            'value' => $city
        ];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['free-shipping-method-fields']['children']['free_street_field'] = [
            'component' => "Magento_Ui/js/form/element/abstract",
            'config' => [
                'customScope' => 'shippingAddress.extension_attributes',
                'template' => 'ui/form/field',
                'elementTmpl' => "ui/form/element/input"
            ],
            'dataScope' => 'shippingAddress.extension_attributes.free_street_field',
            'label' => "Вулиця",
            'provider' => 'checkoutProvider',
            'visible' => $visible,
            'validation' => $validation,
            'value' => !$containsProhibitedValue && isset($fullStreet[0])? $fullStreet[0] : ''
        ];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['free-shipping-method-fields']['children']['free_house_number_field'] = [
            'component' => "Magento_Ui/js/form/element/abstract",
            'config' => [
                'customScope' => 'shippingAddress.extension_attributes',
                'template' => 'ui/form/field',
                'elementTmpl' => "ui/form/element/input"
            ],
            'dataScope' => 'shippingAddress.extension_attributes.free_house_number_field',
            'label' => "Номер будинку",
            'provider' => 'checkoutProvider',
            'visible' => $visible,
            'validation' => [
                'required-entry' => $validation,
                'validate-number' => true,
            ],
            'value' => !$containsProhibitedValue && isset($fullStreet[1]) ? $fullStreet[1] : null
        ];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['free-shipping-method-fields']['children']['free_apartment_number_field'] = [
            'component' => "Magento_Ui/js/form/element/abstract",
            'config' => [
                'customScope' => 'shippingAddress.extension_attributes',
                'template' => 'ui/form/field',
                'elementTmpl' => "ui/form/element/input"
            ],
            'dataScope' => 'shippingAddress.extension_attributes.free_apartment_number_field',
            'label' => "Номер квартири",
            'provider' => 'checkoutProvider',
            'visible' => $visible,
            'validation' => [
                'required-entry' => false,
                'validate-number' => true,
            ],
            'value' => !$containsProhibitedValue && isset($fullStreet[2]) ? $fullStreet[2] : null
        ];



        return $jsLayout;
    }
}
