<?php
declare(strict_types=1);

namespace Base\UkrPoshta\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Model\AddressFactory;
use Magento\Customer\Api\AddressRepositoryInterface;

class OrderSaveAfter implements ObserverInterface
{
    /**
     * @var \Magento\Quote\Model\QuoteRepository
     * @var \Magento\Sales\Model\OrderRepository
     * @var CustomerSession
     * @var AddressFactory
     * @var AddressRepositoryInterface
     */
    private $quoteRepository;
    private $orderRepository;
    private $customerSession;
    private $addressFactory;
    private $addressRepository;

    /**
     * @param \Magento\Quote\Model\QuoteRepository $quoteRepository
     * @param \Magento\Sales\Model\OrderRepository $orderRepository
     * @param CustomerSession $customerSession
     * @param AddressFactory $addressFactory
     * @param AddressRepositoryInterface $addressRepository
     */
    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        \Magento\Sales\Model\OrderRepository $orderRepository,
        CustomerSession $customerSession,
        AddressFactory $addressFactory,
        AddressRepositoryInterface $addressRepository
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->orderRepository = $orderRepository;
        $this->customerSession = $customerSession;
        $this->addressFactory = $addressFactory;
        $this->addressRepository = $addressRepository;
    }

    /**
     * @param EventObserver $observer
     * @return void
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(EventObserver $observer) : void
    {
        $order = $observer->getOrder();
        $quoteId = $order->getQuoteId();

        $quote = $this->quoteRepository->get($quoteId);


        if ($this->customerSession->isLoggedIn()){
            $customerId = $this->customerSession->getCustomer()->getId();
            $customerAddresses = $this->customerSession->getCustomer()->getAddresses();
            if ($customerAddresses) {
                foreach ($customerAddresses as $customerAddress) {
                    $addressId = $customerAddress->getId();
                    $this->addressRepository->deleteById($addressId);
                }
                $address = $this->addressFactory->create();

                $address->setCustomerId($customerId)
                    ->setFirstname($quote->getBillingAddress()->getFirstname())
                    ->setLastname($quote->getBillingAddress()->getLastname())
                    ->setCountryId($quote->getBillingAddress()->getCountryId())
                    ->setPostcode($quote->getBillingAddress()->getPostcode())
                    ->setCity($quote->getBillingAddress()->getCity())
                    ->setTelephone($quote->getBillingAddress()->getTelephone())
                    ->setCompany($quote->getBillingAddress()->getCompany())
                    ->setStreet($quote->getBillingAddress()->getStreet())
                    ->setIsDefaultBilling('1')
                    ->setIsDefaultShipping('1')
                    ->setSaveInAddressBook('1');
                    $address->save();
            }

            $state = $quote->getBillingAddress()->getRegion();
            $street = $quote->getBillingAddress()->getStreet();
        } else {
            $state = $quote->getShippingAddress()->getRegion();
            $street = $quote->getShippingAddress()->getStreet();
        }

            $shippingAddress = $order->getShippingAddress();
            $shippingAddress->setStreet($street);
            $shippingAddress->setState($state);


            $this->orderRepository->save($order);
//        }
    }
}
