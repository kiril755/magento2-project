<?php
declare(strict_types=1);

namespace Base\HidePaymentMethod\Block\Adminhtml\FormCustom\FieldCustom;

use Magento\Framework\View\Element\Html\Select;

use Magento\Backend\Block\Template\Context;
use Magento\Shipping\Model\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ShippingMethod extends Select
{
    /**
     * @var Config
     * @var ScopeConfigInterface
     */
    private $shippingConfig;
    private $scopeConfig;

    /**
     * @param Config $shippingConfig
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        Config $shippingConfig,
        Context $context,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->shippingConfig = $shippingConfig;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function setInputName($value) : mixed
    {
        return $this->setName($value);
    }

    /**
     * @param $value
     * @return ShippingMethod
     */
    public function setInputId($value) : ShippingMethod
    {
        return $this->setId($value);
    }

    /**
     * @return string
     */
    public function _toHtml() : string
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->getSourceOptions());
        }
        return parent::_toHtml();
    }

    /**
     * @return array
     */
    private function getSourceOptions() : array
    {
        $options = [];
        $carriers = $this->shippingConfig->getActiveCarriers();
        foreach ($carriers as $carrier) {
            $carrierId = $carrier->getId();
            $carrierTitle = $this->scopeConfig->getValue(
                'carriers/' . $carrierId . '/title',
                ScopeInterface::SCOPE_STORE
            );

            $options[] = [
                'value' => $carrierId . "_" . $carrierId,
                'label' => $carrierTitle
                ];
        }
        return $options;
    }
}
