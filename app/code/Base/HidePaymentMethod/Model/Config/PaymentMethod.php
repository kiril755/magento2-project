<?php
declare(strict_types=1);

namespace Base\HidePaymentMethod\Model\Config;

use Magento\Framework\Data\OptionSourceInterface;
use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Payment\Model\Config;

class PaymentMethod implements OptionSourceInterface
{
    /**
     * @var ScopeConfigInterface
     */
    protected $_appConfigScopeConfigInterface;
    /**
     * @var Config
     */
    protected $_paymentModelConfig;

    /**
     * @param ScopeConfigInterface $appConfigScopeConfigInterface
     * @param Config               $paymentModelConfig
     */
    public function __construct(
        ScopeConfigInterface $appConfigScopeConfigInterface,
        Config $paymentModelConfig
    ) {

        $this->_appConfigScopeConfigInterface = $appConfigScopeConfigInterface;
        $this->_paymentModelConfig = $paymentModelConfig;
    }

    /**
     * @return array
     */
    public function toOptionArray() : array
    {
        $payments = $this->_paymentModelConfig->getActiveMethods();
        $methods = array();
        foreach ($payments as $paymentCode => $paymentModel) {
            $paymentTitle = $this->_appConfigScopeConfigInterface
                ->getValue('payment/'.$paymentCode.'/title');
            $methods[$paymentCode] = array(
                'label' => $paymentTitle,
                'value' => $paymentCode
            );
        }
        return $methods;
    }
}
