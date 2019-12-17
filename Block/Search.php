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

use Magento\Framework\View\Element\Template;

class Search extends Template
{
    protected $searchHelper;
    protected $helper;

    public function __construct(
        \Magento\Search\Helper\Data $searchHelper,
        \Magento\Framework\View\Element\Template\Context $context,
        \Mamis\StructuredData\Helper\Data $helper,
        array $data = []
    )
    {
        $this->helper = $helper;
        $this->searchHelper = $searchHelper;

        parent::__construct($context, $data);
    }

    public function getSearchUrl()
    {
        return $this->searchHelper->getResultUrl();
    }

    public function getSiteUrl()
    {
        return $this->helper->getStore()->getBaseUrl();
    }
}
