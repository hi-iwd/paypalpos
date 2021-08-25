<?php

namespace IWD\PaypalPos\Observer\Customer;

use Magento\Framework\Event\ObserverInterface;
use IWD\PaypalPos\Api\CustomerLoggerRepositoryInterface;
use IWD\PaypalPos\Api\Data\CustomerLoggerInterfaceFactory;

class Remove implements ObserverInterface
{
    /**
     * @var CustomerLoggerRepositoryInterface
     */
    private $customerLoggerRepository;
    /**
     * @var CustomerLoggerInterfaceFactory
     */
    private $customerLogger;

    public function __construct(
        CustomerLoggerRepositoryInterface $customerLoggerRepository,
        CustomerLoggerInterfaceFactory $customerLogger
    ) {
        $this->customerLoggerRepository = $customerLoggerRepository;
        $this->customerLogger = $customerLogger;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        $customerLogger = $this->customerLogger->create();
        $customerLogger->setCustomerId($customer->getId());
        $customerLogger->setEmail($customer->getEmail());
        try {
            $this->customerLoggerRepository->save($customerLogger);
            // phpcs:ignore Magento2.CodeAnalysis.EmptyBlock
        } catch (\Exception $e) {

        }

        return $this;
    }
}
