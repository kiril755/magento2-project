<?php
declare(strict_types=1);

namespace Base\HidePaymentMethod\Api;

interface SelectInterface
{
    /**
     * Set input name
     *
     * @param string $value
     * @return mixed
     */
    public function setInputName($value) : mixed;

    /**
     * Set input id
     *
     * @param string|integer $value
     */
    public function setInputId($value);

    /**
     * Rendering html content
     *
     * @return string
     */
    public function _toHtml() : string;
}
