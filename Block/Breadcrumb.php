<?php
/**
 * Mamis.IT
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is available through the world-wide-web at this URL:
 * http://www.mamis.com.au/licencing
 *
 * @category   Mamis
 * @copyright  Copyright (c) by Mamis.IT Pty Ltd (http://www.mamis.com.au)
 * @author     Matthew Muscat <matthew@mamis.com.au>
 * @license    http://www.mamis.com.au/licencing
 */

namespace Mamis\StructuredData\Block;

use Magento\Catalog\Helper\Data;
use Magento\Framework\View\Element\Template;

class Breadcrumb extends Template
{
    protected $catalogData;

    public function __construct(
        \Magento\Catalog\Helper\Data $catalogData,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    )
    {
        $this->catalogData = $catalogData;

        parent::__construct($context, $data);
    }

    public function getBreadcrumbs()
    {
        $breadCrumbs = $this->catalogData->getBreadcrumbPath();

        if (empty($breadCrumbs)) {
            return;
        }

        $currenCategory = $this->catalogData->getCategory();
        $currenCategoryId = null;

        if ($currenCategory) {
            $currenCategoryId = $currenCategory->getId();
        }

        // Add category link to last element
        if ($currenCategory && empty($breadCrumbs['category' . $currenCategoryId]['link'])) {
            $breadCrumbs['category' . $currenCategoryId]['link'] = $currenCategory->getUrl();
        }

        // Add product link to last element
        if ($this->catalogData->getProduct()) {
            $breadCrumbs['product']['link'] = $this->catalogData->getProduct()->getProductUrl();
        }

        return $breadCrumbs;
    }
}
