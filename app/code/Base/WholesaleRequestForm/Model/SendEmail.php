<?php
declare(strict_types=1);

namespace Base\WholesaleRequestForm\Model;

use Base\WholesaleRequestForm\Api\SendEmailInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class SendEmail implements SendEmailInterface
{
    /**
     * @var StateInterface
     * @var Escaper
     * @var TransportBuilder
     * @var LoggerInterface
     * @var ScopeConfigInterface
     */
    private $inlineTranslation;
    private $escaper;
    private $transportBuilder;
    private $logger;
    private $scopeConfig;

    /**
     * @param StateInterface $inlineTranslation
     * @param Escaper $escaper
     * @param TransportBuilder $transportBuilder
     * @param LoggerInterface $logger
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        StateInterface $inlineTranslation,
        Escaper $escaper,
        TransportBuilder $transportBuilder,
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->inlineTranslation = $inlineTranslation;
        $this->escaper = $escaper;
        $this->transportBuilder = $transportBuilder;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param array $newRequestArr
     * @param array $lastFiveRequestArr
     * @return void
     */
    public function sendEmail(array $newRequestArr, array $lastFiveRequestArr) : void
    {
        try {
            $this->inlineTranslation->suspend();
            $sender = [
                'name' => $this->escaper->escapeHtml('Request'),
                'email' => $this->escaper->escapeHtml(self::SENDER_EMAIL),
            ];
            $recipient = $this->scopeConfig->getValue('trans_email/ident_general/email', ScopeInterface::SCOPE_STORE);

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
