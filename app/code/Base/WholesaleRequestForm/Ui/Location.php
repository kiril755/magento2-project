<?php

namespace Base\WholesaleRequestForm\Ui;

use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class Location extends Column
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * Order Id constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param string[] $components
     * @param string[] $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['location_latitude']) && isset($item['location_longitude'])) {
//                    $url = $this->urlBuilder->getUrl('sales/order/view', ['order_id' => $item['order_id']]);
                    $url = "https://www.google.com.ua/maps/@";
                    $link = '<a href="' . $url . $item['location_latitude'] . ',' . $item['location_longitude'] . ',11z?hl=ru">' . 'location' . '</a>';
                    $item['location_latitude'] = $link;
                }
            }
        }
        return $dataSource;
    }
}
