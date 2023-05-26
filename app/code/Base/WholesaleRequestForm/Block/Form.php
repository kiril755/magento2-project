<?php

declare(strict_types=1);

namespace Base\WholesaleRequestForm\Block;

use Magento\Framework\View\Element\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Store\Model\StoreManagerInterface;
use Base\WholesaleRequestForm\Model\RequestFactory;
use Base\UkrStatesImport\Model\ResourceModel\State\CollectionFactory as StateCollectionFactory;

class Form extends Template
{
    const SUCCESS = 'Your request is success, now you are in wholesale group!';
    const PENDING = 'Your request on pending status, please, wait for the answer.';
    const REJECT = 'Your request has been denied.';
    /**
     * @var CustomerSession
     * @var storeManager
     * @var RequestFactory
     * @var StateCollectionFactory
     */
    private $customerSession;
    private $storeManager;
    private $requestFactory;
    private $stateCollection;

    /**
     * @param Context $context
     * @param CustomerSession $customerSession
     * @param StoreManagerInterface $storeManager
     * @param RequestFactory $requestFactory
     * @param StateCollectionFactory $stateCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        StoreManagerInterface $storeManager,
        RequestFactory $requestFactory,
        StateCollectionFactory $stateCollection,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->customerSession = $customerSession;
        $this->storeManager = $storeManager;
        $this->requestFactory = $requestFactory;
        $this->stateCollection = $stateCollection;
    }

    public function statesCollection () : array {
        $collection = $this->stateCollection->create();
        $stateArr = $collection->getData();
        return $stateArr;
    }
    public function isRequestSent() : ?string {
        $customerId = $this->customerSession->getCustomer()->getId();
        $requestItem = $this->requestFactory->create()->load($customerId, 'customer_id');
        return $requestItem->getId();
    }
    public function requestStatusMessage() : string
    {
        $customerId = $this->customerSession->getCustomer()->getId();
        $requestItem = $this->requestFactory->create()->load($customerId, 'customer_id');
        $message = '';
        switch ($requestItem->getStatus()) {
            case 'pending':
                $message = self::PENDING;
                break;
            case 'success':
                $message = self::SUCCESS;
                break;
            case 'reject':
                $message = self::REJECT;
                break;
        }
        return $message;
    }
}
