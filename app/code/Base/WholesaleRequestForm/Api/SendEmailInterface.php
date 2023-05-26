<?php
declare(strict_types=1);

namespace Base\WholesaleRequestForm\Api;

interface SendEmailInterface
{
    public const SENDER_EMAIL = 'request@example.com';
    /**
     * @param array $newRequestArr
     * @param array $lastFiveRequestArr
     * @return void
     */
    public function sendEmail(array $newRequestArr, array $lastFiveRequestArr) : void;
}
