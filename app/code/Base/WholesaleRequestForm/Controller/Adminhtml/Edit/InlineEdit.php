<?php
declare(strict_types=1);

namespace Base\WholesaleRequestForm\Controller\Adminhtml\Edit;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Base\WholesaleRequestForm\Model\RequestFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;

class InlineEdit implements HttpPostActionInterface
{
    /**
     * @var JsonFactory
     * @var RequestInterface
     * @var RequestFactory
     * @var EventManager
     */
    private $jsonFactory;
    private $_request;
    private $requestFactory;
    private $eventManager;

    /**
     * @param JsonFactory $jsonFactory
     * @param RequestInterface $request
     * @param RequestFactory $requestFactory
     * @param EventManager $eventManager
     */
    public function __construct(
        JsonFactory        $jsonFactory,
        RequestInterface $request,
        RequestFactory $requestFactory,
        EventManager $eventManager
    )
    {
        $this->jsonFactory = $jsonFactory;
        $this->_request = $request;
        $this->requestFactory = $requestFactory;
        $this->eventManager = $eventManager;
    }

    /**
     * @return Json
     */
    public function execute() : Json
    {
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->_request->isAjax()) {
            $postItems = $this->_request->getParam('items', []);
            foreach (array_keys($postItems) as $entityId) {
                $model = $this->requestFactory->create()->load($entityId);
                try {
                    $model->setData(array_merge($model->getData(), $postItems[$entityId]));
                    $model->save();

                    $this->eventManager->dispatch('wholesale_request_event_before', ['object' => $model]);
                } catch (\Exception $e) {
                    $messages[] = "[Error:] {$e->getMessage()}";
                    $error = true;
                }
            }
        }
        return $resultJson->setData(
            [
                'messages' => $messages,
                'error' => $error,
            ]
        );
    }
}
