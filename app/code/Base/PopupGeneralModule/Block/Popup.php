<?php
declare(strict_types=1);

namespace Base\PopupGeneralModule\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Cms\Model\Page;

class Popup extends Template
{
    /**
     * @var CookieManagerInterface
     * @var Page
     */
    private $cookieManager;
    private $page;

    /**
     * @param Context $context
     * @param CookieManagerInterface $cookieManager
     * @param Page $page
     * @param array $data
     */
    public function __construct(
        Context                $context,
        CookieManagerInterface $cookieManager,
        Page $page,
        array                  $data = []
    )
    {
        parent::__construct($context, $data);
        $this->cookieManager = $cookieManager;
        $this->page = $page;
    }

    /**
     * @return bool
     */
    public function canShowPopup() : bool{
        $currentPageName = $this->page->getIdentifier();

        $pages = $this->_scopeConfig->getValue('popup_general/general/display_pages', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if (!empty($pages)) {
            $pages = explode(",", $pages);
            if (in_array($currentPageName, $pages)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isPopupEnabled() : bool
    {
        $currentPageName = $this->page->getIdentifier();
        if ($cookie = $this->cookieManager->getCookie('popupCookie')) {
            if (str_contains(urldecode($cookie), ',')) {
                $cookieArr = explode(",", urldecode($cookie));
                if (in_array($currentPageName, $cookieArr)) {
                    return false;
                }
            }
            if ($cookie == $currentPageName) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return string
     */
    public function currentUrl() : string{
        return $this->page->getIdentifier();
    }
}
