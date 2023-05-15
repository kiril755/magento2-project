<?php
namespace Base\UkrPoshta\Plugin;

class QuoteSaveField
{
    protected $cartRepository;

    public function __construct(
        \Magento\Quote\Api\CartRepositoryInterface $cartRepository
    ) {
        $this->cartRepository = $cartRepository;
    }

    public function beforeSaveAddressInformation(
        \Magento\Checkout\Api\ShippingInformationManagementInterface $subject,
                                                                     $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        $quote = $this->cartRepository->getActive($cartId);
        $extensionAttributes = $addressInformation->getShippingAddress()->getExtensionAttributes();

        if ($extensionAttributes) {
            $quote->setUkrposhtaStateField($extensionAttributes->getUkrposhtaStateField());
            $quote->setUkrposhtaStreetField($extensionAttributes->getUkrposhtaStreetField());
            $quote->setUkrposhtaHouseNumberField($extensionAttributes->getUkrposhtaHouseNumberField());

            if ($extensionAttributes->getUkrposhtaApartmentNumberField()) {
                $quote->setUkrposhtaApartmentNumberField($extensionAttributes->getUkrposhtaApartmentNumberField());
            }
        }

        return [$cartId, $addressInformation];
    }
}
