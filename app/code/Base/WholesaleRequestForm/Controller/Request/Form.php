<?php
declare(strict_types=1);

namespace Base\WholesaleRequestForm\Controller\Request;

use Magento\Framework\App\ActionInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\View\Result\Page;

class Form implements ActionInterface
{
    /**
     * @var CustomerSession
     * @var RedirectFactory
     * @var PageFactory
     */
    private $customerSession;
    private $redirectFactory;
    private $pageFactory;

    /**
     * @param CustomerSession $customerSession
     * @param RedirectFactory $redirectFactory
     * @param PageFactory $pageFactory
     */
    public function __construct(
        CustomerSession $customerSession,
        RedirectFactory $redirectFactory,
        PageFactory $pageFactory
    )
    {
        $this->customerSession = $customerSession;
        $this->redirectFactory = $redirectFactory;
        $this->pageFactory = $pageFactory;
    }

    /**
     * @return Redirect|Page
     */
    public function execute() : Redirect|Page
    {
        if (!$this->customerSession->isLoggedIn()) {
            return $this->redirectFactory->create()->setPath('home');
        }
        $resultPage = $this->pageFactory->create();
        $resultPage->initLayout();
        return $resultPage;
    }
}

