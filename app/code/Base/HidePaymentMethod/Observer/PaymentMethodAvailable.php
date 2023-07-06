<?php
declare(strict_types=1);

namespace Base\HidePaymentMethod\Observer;

use Magento\Framework\Event\ObserverInterface;
use \Magento\Checkout\Model\Cart;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class PaymentMethodAvailable implements ObserverInterface
{

    /**
     * @var Cart
     */
    protected $cart;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param Cart $cart
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Cart $cart,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->cart = $cart;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Hide payment method for specific (in config) shipping method
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer) : void
    {
        $moduleEnable = $this->scopeConfig->getValue(
            'hide_payment_method/general/enable',
            ScopeInterface::SCOPE_STORE,
        );
        if (!$moduleEnable) {
            return;
        }
        $shippingMethod = $this->cart->getQuote()->getShippingAddress()->getShippingMethod();
        $paymentMethod = $observer->getEvent()->getMethodInstance()->getCode();

        $configValue = $this->scopeConfig->getValue(
            'hide_payment_method/general/hide_fields',
            ScopeInterface::SCOPE_STORE
        );

        $configData = json_decode($configValue, true);
        foreach ($configData as $rowId => $row) {
            $shippingMethodConfig = $row['shipping_method'];
            $paymentMethodConfig = $row['payment_method'];

            if ($paymentMethod == $paymentMethodConfig && $shippingMethod == $shippingMethodConfig) {
                $checkResult = $observer->getEvent()->getResult();
                $checkResult->setData('is_available', false);
            }
        }
    }
}
