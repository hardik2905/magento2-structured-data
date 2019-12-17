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

class Organization extends Template
{
    protected $helper;
    protected $helperStore;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Mamis\StructuredData\Helper\Data $helper,
        \Mamis\StructuredData\Helper\Store $helperStore,
        array $data = []
    )
    {
        $this->helper = $helper;
        $this->helperStore = $helperStore;

        parent::__construct($context, $data);
    }

    public function getStoreUrl()
    {
        return $this->helper->getStoreUrl();
    }

    public function getSearchUrl()
    {
        return $this->helper->getStore()->getBaseUrl();
    }

    public function getName()
    {
        return $this->helper->getConfig('name');
    }

    public function getAddress()
    {
        return implode(', ', array_map('trim', [
            $this->helper->getConfig('street_line1'),
            $this->helper->getConfig('street_line2')
        ]));
    }

    public function getLocality()
    {
        return $this->helper->getConfig('city');
    }

    public function getRegion()
    {
        return $this->helper->getConfig('region_id');
    }

    public function getPostcode()
    {
        return $this->helper->getConfig('postcode');
    }

    public function getCountry()
    {
        return $this->helper->getConfig('country_id');
    }

    public function getSocialLinks()
    {
        $socialLinks = [];

        if (!empty($this->helperStore->getFacebookUrl())) {
            $socialLinks[] = $this->helperStore->getFacebookUrl();
        }

        if (!empty($this->helperStore->getYoutubeUrl())) {
            $socialLinks[] = $this->helperStore->getYoutubeUrl();
        }

        if (!empty($this->helperStore->getPinterestUrl())) {
            $socialLinks[] = $this->helperStore->getPinterestUrl();
        }

        return $socialLinks;
    }
}
