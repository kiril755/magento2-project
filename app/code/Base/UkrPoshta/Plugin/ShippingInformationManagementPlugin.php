<?php
declare(strict_types=1);

namespace Base\UkrPoshta\Plugin;

class ShippingInformationManagementPlugin
{

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     */
    public function __construct(
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
    )
    {
        $this->quoteRepository = $quoteRepository;
    }


    /**
     *
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param \Magento\Checkout\Api\Data\PaymentDetailsInterface $result
     * @param int $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     * @return \Magento\Checkout\Api\Data\PaymentDetailsInterface
     */
    public function afterSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
                                                              $result,
                                                              $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) : \Magento\Checkout\Api\Data\PaymentDetailsInterface
    {
        $quote = $this->quoteRepository->getActive($cartId);
        $shippingAddress = $quote->getShippingAddress();

        if ($addressInformation->getShippingMethodCode() == 'customshipping') {
            $shippingAddress->setStreet($shippingAddress->getStreet());
            $shippingAddress->setRegion($shippingAddress->getRegion());
            $shippingAddress->save();
        }
        return $result;
    }
}
