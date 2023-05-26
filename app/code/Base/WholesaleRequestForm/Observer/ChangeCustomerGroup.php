<?php
declare(strict_types=1);

namespace Base\WholesaleRequestForm\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\App\RequestInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\ResourceModel\Group\CollectionFactory as CustomerGroupFactory;

class ChangeCustomerGroup implements ObserverInterface
{
    /**
     * @var RequestInterface
     * @var CustomerRepositoryInterface
     * @var StoreManagerInterface
     * @var CustomerGroupFactory
     */
    private $request;
    private $customerRepository;
    private $storeManager;
    private $customerGroupFactory;

    /**
     * @param RequestInterface $request
     * @param CustomerRepositoryInterface $customerRepository
     * @param StoreManagerInterface $storeManager
     * @param CustomerGroupFactory $customerGroupFactory
     */
    public function __construct(
        RequestInterface $request,
        CustomerRepositoryInterface $customerRepository,
        StoreManagerInterface $storeManager,
        CustomerGroupFactory $customerGroupFactory
    ) {
        $this->request = $request;
        $this->customerRepository = $customerRepository;
        $this->storeManager = $storeManager;
        $this->customerGroupFactory = $customerGroupFactory;
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

        $collection = $this->customerGroupFactory->create();
        $uaData = $collection->addFieldToFilter('customer_group_code', 'ua')->getData();
        $ruData = $collection->addFieldToFilter('customer_group_code', 'ru')->getData();


        if ($status === 'success') {
            $customer->setGroupId(2);
        } else {
            if ($uaData && $ruData) {
                $groupId = $storeId == 1 ? $uaData[0]['customer_group_id'] : $ruData[0]['customer_group_id'];
                $customer->setGroupId($groupId);
            }
        }

        $this->customerRepository->save($customer);
    }
}
