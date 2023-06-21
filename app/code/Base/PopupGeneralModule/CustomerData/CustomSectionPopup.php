<?php
declare(strict_types=1);

namespace Base\PopupGeneralModule\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
class CustomSectionPopup implements SectionSourceInterface
{
    /**
     * @var CookieManagerInterface
     * @var ScopeConfigInterface
     * @var StoreManagerInterface
     */
    private $cookieManager;
    private $scopeConfig;
    private $storeManager;

    /**
     * @param CookieManagerInterface $cookieManager
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        CookieManagerInterface $cookieManager,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    )
    {
        $this->cookieManager = $cookieManager;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * @return array
     */
    public function getSectionData() : array
    {
        return [
            'pagesFromScope' => $this->getPagesFromScope(),
            'currentPageTitle' => $this->getCurrentPageFromCookie(),
            'popupWasClosedCheck' => $this->popupWasClosedCheck()
        ];
    }

    /**
     * @return array
     */
    public function getPagesFromScope() : array{
        $pages = $this->scopeConfig->getValue('popup_general/general/display_pages', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $pagesArr = explode(",", $pages);
        return $pagesArr;
    }

    /**
     * @return string
     */
    public function getCurrentPageFromCookie () : string {
        if ($cookie = $this->cookieManager->getCookie('currentPageTitle')) {
            return urldecode($cookie);
        }
        return '';
    }

    /**
     * @return array
     * @throws NoSuchEntityException
     */
    public function popupWasClosedCheck () : array{
        $result = [];
        $storeCode = $this->storeManager->getStore()->getCode();
        if ($cookieEncode = $this->cookieManager->getCookie("popupCookie$storeCode")) {
            $cookie = urldecode($cookieEncode);
            if (str_contains($cookie, ',')) {
                $cookieArr = explode(",", $cookie);
                $result = $cookieArr;
                } else {
                $result[] = $cookie;
            }
        }
        return $result;
    }
}
