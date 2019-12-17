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

namespace Mamis\StructuredData\Helper;

use Magento\Store\Model\ScopeInterface;

class Store extends \Mamis\StructuredData\Helper\Data
{
    const XML_PATH_SETTINGS = 'mamis_structured_data/general/';

    public function getFacebookUrl()
    {
        return $this->getConfig('facebook');
    }

    public function getYoutubeUrl()
    {
        return $this->getConfig('youtube');
    }

    public function getPinterestUrl()
    {
        return $this->getConfig('pinterest');
    }
}
