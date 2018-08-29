<?php

namespace TPB\ProductLabels\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use TPB\ProductLabels\Helper\Data;

class AbstractBlock extends Template
{
    /** @var Data */
    protected $helperData;
    /** @var Registry */
    protected $registry;

    protected $product;

    /** @var string */
    protected $blockCode = '';

    /**
     * ProductTopBarIcons constructor.
     *
     * @param Context  $context
     * @param Registry $registry
     * @param Data     $helperData
     */
    public function __construct(
        Context  $context,
        Registry $registry,
        Data     $helperData
    ) {
        $this->registry   = $registry;
        $this->helperData = $helperData;
        $this->product    = $this->getCurrentProduct();

        parent::__construct($context);
    }

    /**
     * @return bool
     */
    public function isBlockEnabled() :bool
    {
        if (empty($this->helperData->getGeneralConfig($this->blockCode)) === true) {
            return false;
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }

    /**
     * @param string $code
     *
     * @return mixed
     */
    public function getAttributeData(string $code)
    {
        return $this->helperData->getAttributeData($code, $this->product);
    }
}