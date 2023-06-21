<?php
declare(strict_types=1);

namespace Base\UkrPoshta\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Quote\Model\QuoteRepository;
use Magento\Sales\Model\OrderRepository;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Model\AddressFactory;
use Magento\Customer\Model\ResourceModel\Address as AddressResource;
use Magento\Customer\Api\AddressRepositoryInterface;

use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;
class OrderSaveAfter implements ObserverInterface
{
    /**
     * @var QuoteRepository
     * @var OrderRepository
     * @var CustomerSession
     * @var AddressFactory
     * @var AddressRepositoryInterface
     * @var AddressResource
     */
    private $quoteRepository;
    private $orderRepository;
    private $customerSession;
    private $addressFactory;
    private $addressRepository;
    private $addressResource;

    /**
     * @param QuoteRepository $quoteRepository
     * @param OrderRepository $orderRepository
     * @param CustomerSession $customerSession
     * @param AddressFactory $addressFactory
     * @param AddressRepositoryInterface $addressRepository
     * @param AddressResource $addressResource
     */
    public function __construct(
        QuoteRepository $quoteRepository,
        OrderRepository $orderRepository,
        CustomerSession $customerSession,
        AddressFactory $addressFactory,
        AddressRepositoryInterface $addressRepository,
        AddressResource $addressResource
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->orderRepository = $orderRepository;
        $this->customerSession = $customerSession;
        $this->addressFactory = $addressFactory;
        $this->addressRepository = $addressRepository;
        $this->addressResource = $addressResource;
    }

    /**
     * @param $customerId
     * @param $customerAddresses
     * @param $quote
     * @return void
     * @throws AlreadyExistsException
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    private function addressManipulation ($customerId, $customerAddresses, $quote) : void {
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
                ->setRegion($quote->getBillingAddress()->getRegion())
                ->setIsDefaultBilling('1')
                ->setIsDefaultShipping('1')
                ->setSaveInAddressBook('1');
            $this->addressResource->save($address);
        }
    }
    /**
     * @param EventObserver $observer
     * @return void
     * @throws AlreadyExistsException
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function execute(EventObserver $observer) : void
    {
        $order = $observer->getOrder();
        $quoteId = $order->getQuoteId();

        $quote = $this->quoteRepository->get($quoteId);

        if ($this->customerSession->isLoggedIn()){
            $customerId = $this->customerSession->getCustomer()->getId();
            $customerAddresses = $this->customerSession->getCustomer()->getAddresses();
            $this->addressManipulation($customerId, $customerAddresses, $quote);

            $state = $quote->getBillingAddress()->getRegion();
            $city = $quote->getBillingAddress()->getCity();
            $street = $quote->getBillingAddress()->getStreet();
        } else {
            $state = $quote->getShippingAddress()->getRegion();
            $city = $quote->getShippingAddress()->getCity();
            $street = $quote->getShippingAddress()->getStreet();
        }
            $shippingAddress = $order->getShippingAddress();
            $shippingAddress->setStreet($street);
            $shippingAddress->setCity($city);
            $shippingAddress->setRegion($state);

            $this->orderRepository->save($order);
    }
}
