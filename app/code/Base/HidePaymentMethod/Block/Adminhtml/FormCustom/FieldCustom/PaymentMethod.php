<?php
declare(strict_types=1);

namespace Base\HidePaymentMethod\Block\Adminhtml\FormCustom\FieldCustom;

use Magento\Framework\View\Element\Html\Select;
use Magento\Backend\Block\Template\Context;
use Magento\Payment\Model\Config;

class PaymentMethod extends Select
{
    /**
     * @var Config
     */
    private $paymentConfig;

    /**
     * @param Config $paymentConfig
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Config $paymentConfig,
        Context $context,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->paymentConfig = $paymentConfig;
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
     * @return PaymentMethod
     */
    public function setInputId($value) : PaymentMethod
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
        $payments = $this->paymentConfig->getActiveMethods();
        foreach ($payments as $payment) {
            $paymentTitle = $payment->getTitle();
            $paymentCode = $payment->getCode();
            if (is_object($paymentCode)) {
                break;
            }

            $options[] = [
                'value' => $paymentCode,
                'label' => $paymentTitle
            ];
        }
        return $options;
    }
}
