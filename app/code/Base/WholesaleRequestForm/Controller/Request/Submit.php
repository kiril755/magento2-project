<?php
declare(strict_types=1);

namespace Base\WholesaleRequestForm\Controller\Request;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\App\RequestInterface;
use Base\WholesaleRequestForm\Model\RequestFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Event\ManagerInterface;
use Magento\Customer\Model\Session;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Filesystem;

class Submit implements HttpPostActionInterface
{
    /**
     * @var JsonFactory
     * @var RequestInterface
     * @var RequestFactory
     * @var PageFactory
     * @var ManagerInterface
     * @var Session
     * @var StoreManagerInterface
     * @var UploaderFactory
     * @var Filesystem
     */
    private $jsonFactory;
    private $request;
    private $requestFactory;
    private $resultPageFactory;
    private $eventManager;
    private $customerSession;
    private $storeManager;
    private $fileUploaderFactory;
    private $filesystem;

    /**
     * @param JsonFactory $jsonFactory
     * @param RequestInterface $request
     * @param RequestFactory $requestFactory
     * @param PageFactory $resultPageFactory
     * @param ManagerInterface $eventManager
     * @param Session $customerSession
     * @param StoreManagerInterface $storeManager
     * @param UploaderFactory $fileUploaderFactory
     * @param Filesystem $filesystem
     */
    public function __construct(
        JsonFactory $jsonFactory,
        RequestInterface $request,
        RequestFactory $requestFactory,
        PageFactory $resultPageFactory,
        ManagerInterface $eventManager,
        Session $customerSession,
        StoreManagerInterface $storeManager,
        UploaderFactory $fileUploaderFactory,
        Filesystem $filesystem
    )
    {
        $this->jsonFactory = $jsonFactory;
        $this->request = $request;
        $this->requestFactory = $requestFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->eventManager = $eventManager;
        $this->customerSession = $customerSession;
        $this->storeManager = $storeManager;
        $this->fileUploaderFactory = $fileUploaderFactory;
        $this->filesystem = $filesystem;
    }

    /**
     * @return Json
     */
    public function execute() : Json
    {


        $result = $this->jsonFactory->create();
        $resultPage = $this->resultPageFactory->create();

        try {

            $data = $this->request->getParams();

            $storeName = $this->storeManager->getStore()->getName();
            $storeId = $this->storeManager->getStore()->getId();

            if ($this->customerSession->isLoggedIn()) {
                $customerId = $this->customerSession->getCustomer()->getId();
                $customerName = $this->customerSession->getCustomer()->getName();
                $customerEmail = $this->customerSession->getCustomer()->getEmail();
            }

            if(isset($_FILES['id-image']['name']) && $_FILES['id-image']['name'] != '') {
                $uploader = $this->fileUploaderFactory->create(['fileId' => 'id-image']);
                $uploader->setAllowedExtensions(['jpg', 'jpeg', 'png']);
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
                $destinationPath = $mediaDirectory->getAbsolutePath('base/wholesalerequestform');
                $resultOfUploader = $uploader->save($destinationPath);
                $imagePath = 'base/wholesalerequestform/'.$resultOfUploader['file'];
                $IdCardImage = $imagePath;
            } else {
                $IdCardImage = null;
            }

            if ($data['latitude'] != "" && $data['longitude'] != "") {
                $latitude = $data['latitude'];
                $longitude = $data['longitude'];
            } else {
                $latitude = "50.450001";
                $longitude = "30.523333";
            }

            $item = $this->requestFactory->create();
            $item->setStore($storeName);
            $item->setStoreId($storeId);
            $item->setCustomerId($customerId);
            $item->setName($customerName);
            $item->setEmail($customerEmail);
            $item->setPersonalIncomeTax($data['inn']);
            if ($IdCardImage != null) {
                $item->setImage($IdCardImage);
            }
            $item->setRegion($data['region']);
            $item->setCity($data['city']);
            $item->setLocationLatitude($latitude);
            $item->setLocationLongitude($longitude);
            $item->setCompany($data['company']);
            $item->setText($data['text']);
            $item->save();

            $this->eventManager->dispatch('request_send_email', ['object' => $item]);

            $block = $resultPage->getLayout()
                ->createBlock('Base\WholesaleRequestForm\Block\Index')
                ->setTemplate('Base_WholesaleRequestForm::requestResult.phtml')
                ->setData('name', $customerName)
                ->toHtml();

            $result->setData(['success' => $block]);
            return $result;
        } catch (\Exception $e) {
            return $result->setData(['error' => $e->getMessage()]);
        }
    }

}
