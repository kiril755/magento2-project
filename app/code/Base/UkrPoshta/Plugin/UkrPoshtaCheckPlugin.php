<?php
declare(strict_types=1);

namespace Base\UkrPoshta\Plugin;


use Magento\Framework\App\RequestInterface;

class UkrPoshtaCheckPlugin
{
    /**
     * @var \Magento\Framework\Webapi\Rest\Request
     * @var \Magento\Quote\Api\Data\AddressInterface
     */
    private $request;
    private $address;

    /**
     * @param \Magento\Framework\Webapi\Rest\Request   $request
     * @param \Magento\Quote\Api\Data\AddressInterface $address
     */
    public function __construct(
        \Magento\Framework\Webapi\Rest\Request   $request,
        \Magento\Quote\Api\Data\AddressInterface $address
    )
    {
        $this->request = $request;
        $this->address = $address;
    }

    /**
     * @param \Magento\Quote\Api\ShipmentEstimationInterface $subject
     * @param $result
     * @param $cartId
     * @param $addressId
     * @return array
     */
    public function afterEstimateByExtendedAddress(
        \Magento\Quote\Api\ShipmentEstimationInterface $subject,
                                                       $result,
                                                       $cartId,
                                                       $addressId
    ) : array
    {
        $ukrposhtaCheck = 0;
        $methods = [];
        /** @var \Magento\Quote\Api\Data\ShippingMethodInterface $item */
        $params = $this->request->getRequestData();
        if (isset($params['address']['custom_attributes'])) {
            foreach ($params['address']['custom_attributes'] as $param) {
                if ($param['attribute_code'] == 'ukrposhta_check' && !empty($param['value'])) {
                    $ukrposhtaCheck = 1;
                }
            }
        }

        foreach ($result as $item) {
            if ($item->getCarrierCode() == 'customshipping' && $ukrposhtaCheck == 1) {
                $methods[] = $item;
            }

            if ($item->getCarrierCode() !== 'customshipping' && $ukrposhtaCheck == 0) {
                $methods[] = $item;
            }
        }

        return $methods;
    }
}
