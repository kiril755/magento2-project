<?php
declare(strict_types=1);

namespace Base\UkrPoshta\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Psr\Log\LoggerInterface;
use Magento\Shipping\Model\Rate\Result;

class Ukrposhta extends AbstractCarrier implements CarrierInterface
{
    /**
     * @var string
     * @var bool
     * @var ResultFactory
     * @var MethodFactory
     */
    protected $_code = 'ukrposhta';
    protected $_isFixed = true;
    private $rateResultFactory;
    private $rateMethodFactory;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param ResultFactory $rateResultFactory
     * @param MethodFactory $rateMethodFactory
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        ResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
    }

    /**
     * @param RateRequest $request
     * @return Result|bool
     */
    public function collectRates(RateRequest $request) : Result|bool
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $result = $this->rateResultFactory->create();

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
