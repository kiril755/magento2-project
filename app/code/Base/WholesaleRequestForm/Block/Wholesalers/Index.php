<?php

declare(strict_types=1);

namespace Base\WholesaleRequestForm\Block\Wholesalers;

use Magento\Framework\View\Element\Template;
use Magento\Backend\Block\Template\Context;
use Base\UkrPoshta\Model\ResourceModel\State\CollectionFactory;
use Base\WholesaleRequestForm\Model\ResourceModel\Request\CollectionFactory as WholesaleCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class Index extends Template
{
    /**
     * @var CollectionFactory
     * @var WholesaleCollectionFactory
     * @var StoreManagerInterface
     */
    private $regionCollectionFactory;
    private $wholesaleCollectionFactory;
    private $storeManager;

    /**
     * @param Context $context
     * @param CollectionFactory $regionCollectionFactory
     * @param WholesaleCollectionFactory $wholesaleCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $regionCollectionFactory,
        WholesaleCollectionFactory $wholesaleCollectionFactory,
        StoreManagerInterface $storeManager,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->regionCollectionFactory = $regionCollectionFactory;
        $this->wholesaleCollectionFactory = $wholesaleCollectionFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @return array|null
     */
    public function getRegionCollection () : array|null {
        $collection = $this->regionCollectionFactory->create();
        $data = $collection->getData();
        return $data;
    }

    /**
     * @return array|null
     */
    public function getWholesalersCollection () : array|null {
        $collection = $this->wholesaleCollectionFactory->create();
        $collection->addFieldToFilter('status', 'success');
        $data = $collection->getData();
        return $data;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBaseUrlForImage() : string {
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA );
        return $mediaUrl;
    }
}
