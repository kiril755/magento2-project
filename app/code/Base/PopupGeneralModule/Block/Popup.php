<?php
declare(strict_types=1);

namespace Base\PopupGeneralModule\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Popup extends Template
{
    const POPUP_CONFIG_PATH_PAGES = 'popup_general/general/display_pages';
    const POPUP_CONFIG_PATH_BLOCK = 'popup_general/general/cms_block_popup';
    const GENERAL_COOKIE_LIFETIME = 'web/cookie/cookie_lifetime';
    const BLOCK_TYPE = 'Magento\Cms\Block\Block';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        Context                $context,
        StoreManagerInterface $storeManager,
        array                  $data = []
    )
    {
        parent::__construct($context, $data);
        $this->storeManager = $storeManager;
    }

    /**
     * @return string
     */
    public function pagesFromConfig () : string{
        $result = '';
        $pages = $this->_scopeConfig->getValue(self::POPUP_CONFIG_PATH_PAGES, ScopeInterface::SCOPE_STORE);
        if (!empty($pages)) {
            $result = $pages;
        }
        return $result;
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public  function getBlockFromScope () : string {
        $result = '';
        $blockId = $this->_scopeConfig->getValue(self::POPUP_CONFIG_PATH_BLOCK, ScopeInterface::SCOPE_STORE);
        if (!empty($blockId)) {
            $result = $this->getLayout()
                ->createBlock(self::BLOCK_TYPE)
                ->setBlockId($blockId)
                ->toHtml();
        }
        return $result;
    }

    /**
     * @return int
     */
    public function getCookieLifetime() : int {
        $cookieLifetime = $this->_scopeConfig->getValue(self::GENERAL_COOKIE_LIFETIME, ScopeInterface::SCOPE_STORE);
        return (int)$cookieLifetime;
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getStoreCode() : string {
        $storeCode = $this->storeManager->getStore()->getCode();
        return $storeCode;
    }
}
