<?php
namespace Base\WholesaleRequestForm\Block\Header;

use Magento\Store\Model\StoreManagerInterface;
class Link extends \Magento\Framework\View\Element\Html\Link
{
    private $storeManager;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        StoreManagerInterface $storeManager,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->storeManager = $storeManager;
    }
    /**
     * @return string
     */
    protected function _toHtml()
    {
        $baseUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
        $store = $this->storeManager->getStore()->getName();

        if (false != $this->getTemplate()) {
            return parent::_toHtml();
        }

        if ($store == "Ua Store View" || $store == "Ru Store View") {
            if ($store == "Ua Store View") {
                $storeCode = "ru";
                $linkValue = "Ru store";
            } elseif ($store == "Ru Store View") {
                $storeCode = "ua";
                $linkValue = "Ua store";
            }
        } else {
            return parent::_toHtml();
        }



        return '<li><a href="' . $baseUrl . $storeCode . '"' . ' >' . $linkValue . '</a></li>';
    }
}
