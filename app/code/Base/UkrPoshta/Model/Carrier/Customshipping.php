<?php
declare(strict_types=1);

namespace Base\UkrPoshta\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;

class Customshipping extends AbstractCarrier implements CarrierInterface
{
    /**
     * @var string
     * @var bool
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    protected $_code = 'customshipping';
    protected $_isFixed = true;
    private $rateResultFactory;
    private $rateMethodFactory;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);

        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
    }

    /**
     * @param RateRequest $request
     * @return \Magento\Shipping\Model\Rate\Result|bool
     */
    public function collectRates(RateRequest $request) : \Magento\Shipping\Model\Rate\Result|bool
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->rateResultFactory->create();

        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->rateMethodFactory->create();

        $totalAmount = $request->getPackageValue();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod($this->_code);
        $method->setMethodTitle($this->getConfigData('name'));

        $shippingCost = (float)$this->getConfigData('shipping_cost');

        if ($totalAmount > $this->getConfigData('free_shipping_min_amount')) {
            $method->setPrice(0.00);
            $method->setCost(0.00);
        } else {
            $method->setPrice($shippingCost);
            $method->setCost($shippingCost);
        }

        $result->append($method);

        return $result;
    }

    /**
     * @return array
     */
    public function getAllowedMethods() : array
    {
        return [$this->_code => $this->getConfigData('name')];
    }
}
