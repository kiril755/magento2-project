<?php
declare(strict_types=1);

namespace Base\WholesaleRequestForm\Ui;

use Base\UkrPoshta\Model\ResourceModel\State\CollectionFactory;
class Region implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param CollectionFactory $collectionFactory
     */

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }
    /**
     * @return array[]
     */
    public function toOptionArray() : array
    {
        $collection = $this->collectionFactory->create();
        $states = $collection->getData();

        $resultArr = [];

        foreach ($states as $state) {
            array_push($resultArr, ['value' => $state['state'], 'label' => $state['state']]);
        }
        return $resultArr;
    }
}
