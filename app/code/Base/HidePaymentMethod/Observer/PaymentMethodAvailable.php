<?php
namespace Base\HidePaymentMethod\Observer;

use Magento\Framework\Event\ObserverInterface;
use \Magento\Checkout\Model\Cart;


class PaymentMethodAvailable implements ObserverInterface
{

    /**
     * @var Cart
     */
    protected $cart;

    /**
     * PaymentMethodAvailable constructor.
     * @param Cart $cart
     */
    public function __construct(
        Cart $cart ){
        $this->cart = $cart;
    }

    /**
     * payment_method_is_active event handler.
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $shippingMethod = $this->cart->getQuote()->getShippingAddress()->getShippingMethod();
        $paymentMethod = $observer->getEvent()->getMethodInstance()->getCode();

        if ($paymentMethod == "braintree" && $shippingMethod == 'freeshipping_freeshipping') {
            $checkResult = $observer->getEvent()->getResult();
            $checkResult->setData('is_available', false);
        }
    }
}