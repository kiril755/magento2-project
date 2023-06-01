<?php
declare(strict_types=1);

namespace Base\PopupGeneralModule\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Popup extends Template
{
    /**
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context                $context,
        array                  $data = []
    )
    {
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function pagesFromConfig () : string{
        $result = '';
        $pages = $this->_scopeConfig->getValue('popup_general/general/display_pages', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if (!empty($pages)) {
            $result = $pages;
        }
        return $result;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public  function getBlockFromScope () : string {
        $result = '';
        $blockId = $this->_scopeConfig->getValue('popup_general/general/cms_block_popup', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if (!empty($blockId)) {
            $result = $this->getLayout()
                ->createBlock('Magento\Cms\Block\Block')
                ->setBlockId($blockId)
                ->toHtml();
        }
        return $result;
    }
}
