<?php
declare(strict_types=1);

namespace Base\HidePaymentMethod\Block\Adminhtml\FormCustom\FieldCustom;

use Magento\Framework\View\Element\Html\Select;
use Magento\Backend\Block\Template\Context;
use Magento\Shipping\Model\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Base\HidePaymentMethod\Api\SelectInterface;

class ShippingMethod extends Select implements SelectInterface
{
    /**
     * @var Config
     */
    private $shippingConfig;
    /**
     * @var ScopeConfigInterface
     */

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
    ) {
        parent::__construct($context, $data);
        $this->shippingConfig = $shippingConfig;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Set inpout name
     *
     * @param string $value
     * @return mixed
     */
    public function setInputName($value) : mixed
    {
        return $this->setName($value);
    }

    /**
     * Set input id
     *
     * @param string|integer $value
     * @return ShippingMethod
     */
    public function setInputId($value) : ShippingMethod
    {
        return $this->setId($value);
    }

    /**
     * Rendering html content
     *
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
     * Get source options
     *
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
