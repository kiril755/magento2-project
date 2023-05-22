<?php
namespace Base\UkrPoshta\Plugin;
class AttributeMerger
{
    public function afterMerge(\Magento\Checkout\Block\Checkout\AttributeMerger $subject, $result)
    {
        if(array_key_exists('telephone', $result)){
            $result['telephone']['placeholder'] = __('+38 (999) 999 99 99');
        }
        return $result;
    }
}
