<?php

namespace IWD\PaypalPos\Observer\Product;

use Magento\Framework\Event\ObserverInterface;
use IWD\PaypalPos\Api\ProductLoggerRepositoryInterface;
use IWD\PaypalPos\Api\Data\ProductLoggerInterfaceFactory;

class Remove implements ObserverInterface
{
    /**
     * @var ProductLoggerRepositoryInterface
     */
    private $productLoggerRepository;
    /**
     * @var ProductLoggerInterfaceFactory
     */
    private $productLogger;

    public function __construct(
        ProductLoggerRepositoryInterface $productLoggerRepository,
        ProductLoggerInterfaceFactory $productLogger
    ) {
        $this->productLoggerRepository = $productLoggerRepository;
        $this->productLogger = $productLogger;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var $product \Magento\Catalog\Model\Product */
        $product = $observer->getEvent()->getProduct();
        $productLogger = $this->productLogger->create();
        $productLogger->setProductId($product->getId());
        try {
            $this->productLoggerRepository->save($productLogger);
            // phpcs:ignore Magento2.CodeAnalysis.EmptyBlock
        } catch (\Exception $e) {

        }

        return $this;
    }
}
