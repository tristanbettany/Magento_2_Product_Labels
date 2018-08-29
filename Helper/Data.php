<?php

namespace TPB\ProductLabels\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /** @var string */
    const XML_PATH = 'productlabels/';

    /**
     * @param string   $field
     * @param int|null $storeId
     *
     * @return mixed
     */
    public function getConfigValue(
        string $field,
        int    $storeId = null
    ) {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param string   $code
     * @param int|null $storeId
     *
     * @return mixed
     */
    public function getGeneralConfig(
        string $code,
        int    $storeId = null
    ) {
        return $this->getConfigValue(
            self::XML_PATH . 'general/' . $code,
            $storeId
        );
    }

    /**
     * @param string $code
     * @param        $product
     *
     * @return mixed
     */
    public function getAttributeData(
        string $code,
               $product
    ) {
        return $product->getResource()->getAttribute($code)->getFrontend()->getValue($product);
    }
}