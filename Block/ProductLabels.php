<?php

namespace TPB\ProductLabels\Block;

use Magento\Framework\Stdlib\DateTime\DateTime;

class ProductLabels extends AbstractBlock
{
    protected $blockCode = 'enableproductlabels';

    private $productChildren = null;

    /**
     * Check if limited edition label is enabled
     *
     * @return bool
     */
    public function isLimitedEnabled() :bool
    {
        if (empty($this->helperData->getGeneralConfig('displaylimited')) === true) {
            return false;
        }

        return true;
    }

    /**
     * Check if online exclusive is enabled
     *
     * @return bool
     */
    public function isOnlineEnabled() :bool
    {
        if (empty($this->helperData->getGeneralConfig('displayonline')) === true) {
            return false;
        }

        return true;
    }

    /**
     * Check if sale label is enabled
     *
     * @return bool
     */
    public function isSaleEnabled() :bool
    {
        if (empty($this->helperData->getGeneralConfig('displaysale')) === true) {
            return false;
        }

        return true;
    }

    /**
     * Check if new label is enabled
     *
     * @return bool
     */
    public function isNewEnabled() :bool
    {
        if (empty($this->helperData->getGeneralConfig('displaynew')) === true) {
            return false;
        }

        return true;
    }

    /**
     * Check if product is new
     *
     * @return bool
     */
    public function isProductNew() :bool
    {
        $newFromDate = $this->product->getNewsFromDate();
        $newToDate   = $this->product->getNewsToDate();

        if (!$newFromDate && !$newToDate) {
            return false;
        }

        $date = new DateTime($this->_localeDate);

        $currentTime = $date->gmtDate();
        $fromDate    = $date->gmtDate(null, $newFromDate);
        $toDate      = $date->gmtDate(null, $newToDate);

        if ($fromDate < $currentTime && $toDate > $currentTime) {
            return true;
        }

        return false;
    }

    /**
     * populate class variable with children products
     */
    public function getProductChildren()
    {
        $this->productChildren = $this->product->getTypeInstance()->getUsedProducts($this->product);
    }

    /**
     * check if product is on sale
     *
     * @return bool
     */
    public function isProductOnSale() :bool
    {
        foreach ($this->productChildren as $child) {
            $specialFromDate = $child->getSpecialFromDate();
            $specialToDate   = $child->getSpecialToDate();

            if (empty($specialFromDate) === false && empty($specialToDate) === false) {
                $date = new DateTime($this->_localeDate);

                $currentTime = $date->gmtDate();
                $fromDate    = $date->gmtDate(null, $specialFromDate);
                $toDate      = $date->gmtDate(null, $specialToDate);

                if ($fromDate < $currentTime && $toDate > $currentTime) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * get product discount amount (percentage)
     *
     * @return int
     */
    public function getProductDiscountAmount() :int
    {
        $largestPercentageOff = 0;
        foreach ($this->productChildren as $child) {
            $price = $child->getPrice();
            $specialPrice = $child->getFinalPrice();

            $percentage = 0;
            if ($price > $specialPrice) {
                $diff = $price - $specialPrice;
                $percentage = round(($diff / $price) * 100, 0);
            }

            if ($percentage > $largestPercentageOff) {
                $largestPercentageOff = $percentage;
            }
        }

        return (int) $largestPercentageOff;
    }

    /**
     * check if the product is limited edition
     *
     * @return bool
     */
    public function isProductLimitedEdition() :bool
    {
        return $this->checkYesNoAttr($this->getAttributeData('limited'));
    }

    /**
     * check if the product is an online exclusive
     *
     * @return bool
     */
    public function isProductOnlineExclusive() :bool
    {
        return $this->checkYesNoAttr($this->getAttributeData('online'));
    }

    /**
     *
     *
     * @return string
     */
    public function getNewColor()
    {
        $color = $this->helperData->getGeneralConfig('newcolor');

        if (empty($color) === false) {
            return $color;
        }

        return 'ffcc00';
    }

    public function getSaleColor()
    {
        $color = $this->helperData->getGeneralConfig('salecolor');

        if (empty($color) === false) {
            return $color;
        }

        return 'ff3385';
    }

    public function getLimitedColor()
    {
        $color = $this->helperData->getGeneralConfig('limitedcolor');

        if (empty($color) === false) {
            return $color;
        }

        return '66b3ff';
    }

    public function getOnlineColor()
    {
        $color = $this->helperData->getGeneralConfig('onlinecolor');

        if (empty($color) === false) {
            return $color;
        }

        return '00e6ac';
    }

    /**
     * check a yes/no attribute to see if its true
     * why wouldnt this return a bool already, its mad!
     *
     * @param $attr
     *
     * @return bool
     */
    private function checkYesNoAttr($attr) :bool
    {
        if (empty($attr) === true) {
            return false;
        }

        if ((string)$attr === 'No') {
            return false;
        }

        return true;
    }
}