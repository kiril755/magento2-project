<?php
declare(strict_types=1);

namespace Base\PopupGeneralModule\Model\Config;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class PagesOption implements OptionSourceInterface
{
    /**
     * @var PageRepositoryInterface
     * @var SearchCriteriaBuilder
     */
    private $pageRepositoryInterface;
    private $searchCriteriaBuilder;

    /**
     * @param PageRepositoryInterface $pageRepositoryInterface
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        PageRepositoryInterface $pageRepositoryInterface,
        SearchCriteriaBuilder $searchCriteriaBuilder
    )
    {
        $this->pageRepositoryInterface = $pageRepositoryInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function toOptionArray() : array
    {
        $options = [];
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $pages = $this->pageRepositoryInterface->getList($searchCriteria)->getItems();
        foreach ($pages as $page) {
            $options[] = ['value' => $page->getTitle(), 'label' => $page->getTitle()];
        }
        return $options;
    }
}
