<?php
declare(strict_types=1);

namespace Base\WholesaleRequestForm\Controller\Request;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\App\RequestInterface;
use Base\WholesaleRequestForm\Model\RequestFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Event\ManagerInterface;
use Magento\Customer\Model\Session;
use Magento\Store\Model\StoreManagerInterface;

class Submit implements HttpPostActionInterface
{
    /**
     * @var JsonFactory
     * @var RequestInterface
     * @var RequestFactory
     * @var PageFactory
     * @var ManagerInterface
     * @var Session
     * @var StoreManagerInterface
     */
    private $jsonFactory;
    private $request;
    private $requestFactory;
    private $resultPageFactory;
    private $eventManager;
    private $customerSession;
    private $storeManager;

    /**
     * @param JsonFactory $jsonFactory
     * @param RequestInterface $request
     * @param RequestFactory $requestFactory
     * @param PageFactory $resultPageFactory
     * @param ManagerInterface $eventManager
     * @param Session $customerSession
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        JsonFactory $jsonFactory,
        RequestInterface $request,
        RequestFactory $requestFactory,
        PageFactory $resultPageFactory,
        ManagerInterface $eventManager,
        Session $customerSession,
        StoreManagerInterface $storeManager
    )
    {
        $this->jsonFactory = $jsonFactory;
        $this->request = $request;
        $this->requestFactory = $requestFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->eventManager = $eventManager;
        $this->customerSession = $customerSession;
        $this->storeManager = $storeManager;
    }

    /**
     * @return Json
     */
    public function execute() : Json
    {


        $result = $this->jsonFactory->create();
        $resultPage = $this->resultPageFactory->create();
        try {

            $personalIncomeTax = $this->request->getParam('personalIncomeTax');
            $text = $this->request->getParam('text');

            $storeName = $this->storeManager->getStore()->getName();
            $storeId = $this->storeManager->getStore()->getId();

            if ($this->customerSession->isLoggedIn()) {
                $customerId = $this->customerSession->getCustomer()->getId();
                $customerName = $this->customerSession->getCustomer()->getName();
                $customerEmail = $this->customerSession->getCustomer()->getEmail();
            }

            $item = $this->requestFactory->create();
            $item->setStore($storeName);
            $item->setStoreId($storeId);
            $item->setCustomerId($customerId);
            $item->setName($customerName);
            $item->setEmail($customerEmail);
            $item->setPersonalIncomeTax($personalIncomeTax);
            $item->setText($text);
            $item->save();

            $this->eventManager->dispatch('request_send_email', ['object' => $item]);

            $block = $resultPage->getLayout()
                ->createBlock('Base\WholesaleRequestForm\Block\Index')
                ->setTemplate('Base_WholesaleRequestForm::requestResult.phtml')
                ->setData('name', $customerName)
                ->toHtml();

            $result->setData(['success' => $block]);
            return $result;
        } catch (\Exception $e) {
            return $result->setData(['error' => $e->getMessage()]);
        }
    }

}
