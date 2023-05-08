<?php
declare(strict_types=1);

namespace Base\WholesaleRequestForm\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\TransportBuilder;

class Email extends AbstractHelper
{
    /**
     * @var StateInterface
     * @var Escaper
     * @var TransportBuilder
     */
    private $inlineTranslation;
    private $escaper;
    private $transportBuilder;
    private $logger;

    /**
     * @param Context $context
     * @param StateInterface $inlineTranslation
     * @param Escaper $escaper
     * @param TransportBuilder $transportBuilder
     */
    public function __construct(
        Context $context,
        StateInterface $inlineTranslation,
        Escaper $escaper,
        TransportBuilder $transportBuilder,
    ) {
        parent::__construct($context);
        $this->inlineTranslation = $inlineTranslation;
        $this->escaper = $escaper;
        $this->transportBuilder = $transportBuilder;
        $this->logger = $context->getLogger();
    }

    /**
     * @param $newRequestArr
     * @param $lastFiveRequestArr
     * @return void
     */
    public function sendEmail($newRequestArr, $lastFiveRequestArr) : void
    {
        try {
            $this->inlineTranslation->suspend();
            $sender = [
                'name' => $this->escaper->escapeHtml('Request'),
                'email' => $this->escaper->escapeHtml('request@example.com'),
            ];
            $recipient = 'owner@example.com';

            $request = [
                'newRequest'  => $newRequestArr,
                'lastFiveRequests' => $lastFiveRequestArr,

            ];
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($request);

            $transport = $this->transportBuilder
                ->setTemplateIdentifier('email_request')
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars([
                    'data' => $postObject
                ])
                ->setFrom($sender)
                ->addTo($recipient)
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }
    }
}
