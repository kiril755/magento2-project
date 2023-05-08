<?php
declare(strict_types=1);

namespace Base\WholesaleRequestForm\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Base\WholesaleRequestForm\Model\ResourceModel\Request\CollectionFactory;
use Base\WholesaleRequestForm\Helper\Email;

class SendEmail implements ObserverInterface
{
    /**
     * @var CollectionFactory
     * @var Email
     */
    private $requestCollectionFactory;
    private $emailHelper;

    /**
     * @param CollectionFactory $requestCollectionFactory
     * @param Email $emailHelper
     */
    public function __construct(CollectionFactory $requestCollectionFactory, Email $emailHelper) {
        $this->requestCollectionFactory = $requestCollectionFactory;
        $this->emailHelper = $emailHelper;
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

        $request = $observer->getEvent()->getObject();

        $newRequest = [
            'name' => $request->getName(),
            'inn' => $request->getPersonalIncomeTax(),
            'text' => $request->getText()
        ];

        $requestCollection = $this->requestCollectionFactory->create();
        $requestCollection
            ->addFieldToFilter('status', 'pending')
            ->setOrder('create_at', 'desc')
            ->setPageSize(5)
            ->load();
        $lastFiveRequest = [];

        foreach ($requestCollection as $request) {
            $eachRequest = [
                'id' => $request->getId(),
                'name' => $request->getName(),
                'inn' => $request->getPersonalIncomeTax(),
                'text' => $request->getText()
            ];


            array_push($lastFiveRequest, $eachRequest);
        }

        $this->emailHelper->sendEmail($newRequest, $lastFiveRequest);
    }
}
