<?php
declare(strict_types=1);

namespace Base\UkrPoshta\Plugin;

class QuoteSaveField
{
    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     * @var \Magento\Quote\Api\ShippingMethodManagementInterface
     */
    private $cartRepository;
    private $shippingMethodManagement;

    /**
     * @param \Magento\Quote\Api\CartRepositoryInterface $cartRepository
     * @param \Magento\Quote\Api\ShippingMethodManagementInterface $shippingMethodManagement
     */
    public function __construct(
        \Magento\Quote\Api\CartRepositoryInterface $cartRepository,
        \Magento\Quote\Api\ShippingMethodManagementInterface $shippingMethodManagement
    ) {
        $this->cartRepository = $cartRepository;
        $this->shippingMethodManagement = $shippingMethodManagement;
    }

    /**
     * @param \Magento\Checkout\Api\ShippingInformationManagementInterface $subject
     * @param $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Api\ShippingInformationManagementInterface $subject,
                                                                     $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) : array
    {
        $quote = $this->cartRepository->getActive($cartId);
//        $getShippingMethods = $this->shippingMethodManagement->getList($quote->getId());
        $extensionAttributes = $addressInformation->getShippingAddress();

        if ($addressInformation->getShippingMethodCode() == 'customshipping') {
            if ($extensionAttributes) {
                $street = $extensionAttributes->getStreet();
                $fullStreet = explode(',', $street[0]);
                $quote->setStateField($extensionAttributes->getRegion());
                $quote->setStreetField($fullStreet[0]);
                $quote->setHouseNumberField($fullStreet[1]);
                $quote->setApartmentNumberField(isset($fullStreet[2]) ? $fullStreet[2] : '');
            }
        } else {

        }

        return [$cartId, $addressInformation];
    }
}
