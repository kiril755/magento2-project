<?php
declare(strict_types=1);

namespace Base\WholesaleRequestForm\Controller\Wholesalers;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\RequestInterface;
use Base\WholesaleRequestForm\Model\ResourceModel\Request\CollectionFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\Json;

class Ajax implements HttpGetActionInterface
{
    /**
     * @var JsonFactory
     * @var RequestInterface
     * @var CollectionFactory
     * @var PageFactory
     */
    private $jsonFactory;
    private $request;
    private $wholesalersCollectionFactory;
    private $pageFactory;

    /**
     * @param JsonFactory $jsonFactory
     * @param RequestInterface $request
     * @param CollectionFactory $wholesalersCollectionFactory
     * @param PageFactory $pageFactory
     */
    public function __construct(
        JsonFactory $jsonFactory,
        RequestInterface $request,
        CollectionFactory $wholesalersCollectionFactory,
        PageFactory $pageFactory
    )
    {
        $this->jsonFactory = $jsonFactory;
        $this->request = $request;
        $this->wholesalersCollectionFactory = $wholesalersCollectionFactory;
        $this->pageFactory = $pageFactory;
    }

    /**
     * @return Json
     */
    public function execute() : Json
    {
        $result = $this->jsonFactory->create();
        $resultPage = $this->pageFactory->create();

        try {
            $state = $this->request->getParam('state');

            $collection = $this->wholesalersCollectionFactory->create();
            $collection->addFieldToFilter('status', 'success');
            if ($state != 0) {
                $collection->addFieldToFilter('region', $state);
            }

            $data = $collection->getData();

            $block = $resultPage->getLayout()
                ->createBlock('Base\WholesaleRequestForm\Block\Wholesalers\Request')
                ->setTemplate('Base_WholesaleRequestForm::wholesalersRequest.phtml')
                ->setData('wholesalers', $data)
                ->toHtml();

            $result->setData(['success' => $block]);
            return $result;
        } catch (\Exception $e) {
            return $result->setData(['error' => $e->getMessage()]);
        }
    }
}
