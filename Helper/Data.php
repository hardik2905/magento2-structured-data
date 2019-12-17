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

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_SETTINGS = 'general/store_information/';

    protected $storeManager;
    protected $scopeConfig;
    protected $logo;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Theme\Block\Html\Header\Logo $logo
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->logo = $logo;
    }

    public function getStore()
    {
        return $this->storeManager->getStore();
    }

    public function getStoreUrl()
    {
        return $this->getStore()->getBaseUrl();
    }

    public function getStoreLogoUrl()
    {
        return $this->logo->getLogoSrc();
    }

    public function getConfig($key, $scope = ScopeInterface::SCOPE_STORES)
    {
        $path = static::XML_PATH_SETTINGS . $key;

        return $this->scopeConfig->getValue($path, $scope);
    }
}
