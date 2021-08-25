<?php

namespace IWD\PaypalPos\Observer\Category;

use Magento\Framework\Event\ObserverInterface;
use IWD\PaypalPos\Api\CategoryLoggerRepositoryInterface;
use IWD\PaypalPos\Api\Data\CategoryLoggerInterface;
use IWD\PaypalPos\Api\Data\CategoryLoggerInterfaceFactory;

class Remove implements ObserverInterface
{
    /**
     * @var CategoryLoggerRepositoryInterface
     */
    private $categoryLoggerRepository;
    /**
     * @var CategoryLoggerInterfaceFactory
     */
    private $categoryLogger;

    public function __construct(
        CategoryLoggerRepositoryInterface $categoryLoggerRepository,
        CategoryLoggerInterfaceFactory $categoryLogger
    ) {
        $this->categoryLoggerRepository = $categoryLoggerRepository;
        $this->categoryLogger = $categoryLogger;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var $category \Magento\Catalog\Model\Category */
        $category = $observer->getEvent()->getCategory();
        $categoryLogger = $this->categoryLogger->create();
        $categoryLogger->setCategoryId($category->getId());
        $categoryLogger->setStoreId($category->getStoreId());
        try {
            $this->categoryLoggerRepository->save($categoryLogger);
            // phpcs:ignore Magento2.CodeAnalysis.EmptyBlock
        } catch (\Exception $e) {

        }
        return $this;
    }
}
