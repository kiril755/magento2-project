<?php
declare(strict_types=1);

namespace Base\PopupGeneralModule\Model\Config;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Cms\Model\ResourceModel\Block\CollectionFactory;


class CmsBlocks implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    )
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray() : array
    {
        $options = [];
        $collection = $this->collectionFactory->create();
        $blocks = $collection->addFieldToFilter('identifier', ['like' => 'popup%'])->getItems();
        foreach ($blocks as $block) {
            $options[] = ['value' => $block->getId(), 'label' => $block->getTitle()];
        }
        return $options;
    }
}
