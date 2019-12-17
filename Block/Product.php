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

use Magento\Catalog\Block\Product\View;
use Magento\Review\Model\Review\SummaryFactory;
use Magento\Review\Model\Review\Summary;
use Magento\Review\Model\ResourceModel\Review\CollectionFactory as ReviewCollectionFactory;
use Magento\Catalog\Block\Product\Context;
use Magento\Framework\Url\EncoderInterface as UrlEncoderInterface;
use Magento\Framework\Json\EncoderInterface as JsonEncoderInterface;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Catalog\Helper\Product as ProductHelper;
use Magento\Catalog\Model\ProductTypes\ConfigInterface;
use Magento\Framework\Locale\FormatInterface;
use Magento\Customer\Model\Session;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Catalog\Model\ProductRepository;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Mamis\StructuredData\Helper\Data as DataHelper;

class Product extends View
{
    const INSTOCK = 'https://schema.org/InStock';
    const OUTOFSTOCK = 'https://schema.org/OutOfStock';
    const ITEMCONDITION = 'https://schema.org/NewCondition';

    /**
     * @var Product Loader
     */
    protected $productRepository;

    /**
     * @var SummaryFactory
     */
    protected $reviewSummaryFactory;

    /**
     * @var ReviewCollectionFactory
     */
    protected $reviewCollectionFactory;

    /**
     * @var string
     */
    protected $brand = null;

    /**
     * @var Summary
     */
    protected $reviewData = null;

    /**
     * @var int
     */
    protected $reviewsCount = null;

    protected $stockRegistry;

    protected $helper;

    /**
     * @param Context $context
     * @param UrlEncoderInterface $urlEncoder
     * @param JsonEncoderInterface $jsonEncoder
     * @param StringUtils $string
     * @param ProductHelper $productHelper
     * @param ConfigInterface $productTypeConfig
     * @param FormatInterface $localeFormat
     * @param Session $customerSession
     * @param ProductRepositoryInterface $productRepository
     * @param PriceCurrencyInterface $priceCurrency
     * @param SummaryFactory $reviewSummaryFactory
     * @param ReviewCollectionFactory $reviewCollectionFactory
     * @param array $data
     * @codingStandardsIgnoreStart
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        UrlEncoderInterface $urlEncoder,
        JsonEncoderInterface $jsonEncoder,
        StringUtils $string,
        ProductHelper $productHelper,
        ConfigInterface $productTypeConfig,
        FormatInterface $localeFormat,
        Session $customerSession,
        ProductRepositoryInterface $productRepositoryInterface,
        PriceCurrencyInterface $priceCurrency,
        SummaryFactory $reviewSummaryFactory,
        ReviewCollectionFactory $reviewCollectionFactory,
        ProductRepository $productRepository,
        StockRegistryInterface $stockRegistry,
        DataHelper $helper,
        array $data = []
    ) {
        $this->reviewSummaryFactory = $reviewSummaryFactory;
        $this->reviewCollectionFactory = $reviewCollectionFactory;
        $this->productRepository = $productRepository;
        $this->stockRegistry = $stockRegistry;
        $this->helper = $helper;
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepositoryInterface,
            $priceCurrency,
            $data
        );
    }

    public function getOffers()
    {
        $offers = [];

        if ($this->getProduct()->getTypeId() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
            $productIds = $this->getProduct()
                ->getTypeInstance()
                ->getChildrenIds($this->getProduct()->getId(), true);
        }
        else {
            $productIds[] = [$this->getProduct()->getId()];
        }

        try {
            foreach (reset($productIds) as $productId)
            {
                $product = $this->loadProduct($productId);
                $productStock = $this->getStockItem($productId);

                $offers[] = [
                    'url' => $product->getProductUrl(),
                    'price' => round($product->getPrice(), 2),
                    'currency' => $this->helper->getStore()->getCurrentCurrency()->getCode(),
                    'itemcondition' => self::ITEMCONDITION,
                    'availability' => ($productStock->getIsInStock()) ? self::INSTOCK : self::OUTOFSTOCK,
                ];
            }
        }
        catch (\Exception $e) {
            // Do nothing
        }

        return $offers;
    }

    public function loadProduct($id)
    {
        $storeId = $this->helper->getStore()->getId();

        return $this->productRepository->getById($id, false, $storeId);
    }

    public function getStockItem($productId)
    {
        return $this->stockRegistry->getStockItem($productId);
    }

    public function getReviewData()
    {
        if ($this->reviewData === null) {
            $this->reviewData = $this->reviewSummaryFactory
                ->create()
                ->load($this->getProduct()->getId());
        }

        return $this->reviewData;
    }

    public function getReviewsRating()
    {
        $ratingSummary = !empty($this->getReviewData()) ? $this->getReviewData()->getRatingSummary() : 20;

        return $ratingSummary / 20;
    }

    public function getReviewsCount()
    {
        if ($this->reviewsCount === null) {
            $this->reviewsCount = !empty($this->getReviewData()) ? $this->getReviewData()->getReviewsCount() : 0;
        }

        return $this->reviewsCount;
    }
}
