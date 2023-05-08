<?php
declare(strict_types=1);

namespace Base\WholesaleRequestForm\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\App\RequestInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;

class ChangeCustomerGroup implements ObserverInterface
{
    /**
     * @var RequestInterface
     * @var CustomerRepositoryInterface
     * @var StoreManagerInterface
     */
    private $request;
    private $customerRepository;
    private $storeManager;

    /**
     * @param RequestInterface $request
     * @param CustomerRepositoryInterface $customerRepository
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        RequestInterface $request,
        CustomerRepositoryInterface $customerRepository,
        StoreManagerInterface $storeManager
    ) {
        $this->request = $request;
        $this->customerRepository = $customerRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer) : void
    {
        if (!($observer instanceof EventObserver)) {
            return;
        }

        $status = $observer->getEvent()->getObject()->getStatus();

        $customerId = $observer->getEvent()->getObject()->getCustomerId();
        $customer = $this->customerRepository->getById($customerId);

        $storeId = $observer->getEvent()->getObject()->getStoreId();

        if ($status === 'success') {
            $customer->setGroupId(2);
        } else {
            $groupId = $storeId == 1 ? 4 : 5;
            $customer->setGroupId($groupId);
        }

        $this->customerRepository->save($customer);
    }
}
