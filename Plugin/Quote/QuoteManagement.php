<?php

namespace IWD\PaypalPos\Plugin\Quote;

use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\QuoteFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Store\Model\StoreManagerInterface;

class QuoteManagement
{
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;
    /**
     * @var AddressRepositoryInterface|null
     */
    private $addressRepository;
    /**
     * @var QuoteFactory
     */
    private $quoteFactory;
    /**
     * @var CartRepositoryInterface
     */
    private $quoteRepository;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * QuoteManagement constructor.
     * @param CustomerRepositoryInterface $customerRepository
     * @param AddressRepositoryInterface|null $addressRepository
     * @param QuoteFactory $quoteFactory
     * @param CartRepositoryInterface $quoteRepository
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        AddressRepositoryInterface $addressRepository,
        QuoteFactory $quoteFactory,
        CartRepositoryInterface $quoteRepository,
        StoreManagerInterface $storeManager
    ) {
        $this->customerRepository = $customerRepository;
        $this->addressRepository = $addressRepository;
        $this->quoteFactory = $quoteFactory;
        $this->quoteRepository = $quoteRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * fix Magento < 2.3.3 bug
     * @param \Magento\Quote\Model\QuoteManagement $subject
     * @param \Closure $proceed
     * @param $customerId
     * @return int
     * @throws CouldNotSaveException
     */
    public function aroundCreateEmptyCartForCustomer(
        \Magento\Quote\Model\QuoteManagement $subject,
        \Closure $proceed,
        $customerId
    ) {
        $storeId = $this->storeManager->getStore()->getStoreId();
        $quote = $this->createCustomerCart($customerId, $storeId);

        $this->_prepareCustomerQuote($quote);

        try {
            $this->quoteRepository->save($quote);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__("The quote can't be created."));
        }
        return (int)$quote->getId();
    }

    private function createCustomerCart($customerId, $storeId)
    {
        try {
            $quote = $this->quoteRepository->getActiveForCustomer($customerId);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $customer = $this->customerRepository->getById($customerId);
            /** @var \Magento\Quote\Model\Quote $quote */
            $quote = $this->quoteFactory->create();
            $quote->setStoreId($storeId);
            $quote->setCustomer($customer);
            $quote->setCustomerIsGuest(0);
        }
        return $quote;
    }

    private function _prepareCustomerQuote($quote)
    {
        /** @var Quote $quote */
        $billing = $quote->getBillingAddress();
        $shipping = $quote->isVirtual() ? null : $quote->getShippingAddress();

        $customer = $this->customerRepository->getById($quote->getCustomerId());
        $hasDefaultBilling = (bool)$customer->getDefaultBilling();
        $hasDefaultShipping = (bool)$customer->getDefaultShipping();

        if ($shipping && !$shipping->getSameAsBilling()
            && (!$shipping->getCustomerId() || $shipping->getSaveInAddressBook())
        ) {
            if ($shipping->getQuoteId()) {
                $shippingAddress = $shipping->exportCustomerAddress();
            } else {
                $defaultShipping = $this->customerRepository->getById($customer->getId())->getDefaultShipping();
                if ($defaultShipping) {
                    try {
                        $shippingAddress = $this->addressRepository->getById($defaultShipping);
                        // phpcs:ignore Magento2.CodeAnalysis.EmptyBlock
                    } catch (LocalizedException $e) {
                        // no address
                    }
                }
            }
            if (isset($shippingAddress)) {
                if (!$hasDefaultShipping) {
                    //Make provided address as default shipping address
                    $shippingAddress->setIsDefaultShipping(true);
                    $hasDefaultShipping = true;
                    if (!$hasDefaultBilling && !$billing->getSaveInAddressBook()) {
                        $shippingAddress->setIsDefaultBilling(true);
                        $hasDefaultBilling = true;
                    }
                }
                //save here new customer address
                $shippingAddress->setCustomerId($quote->getCustomerId());
                $this->addressRepository->save($shippingAddress);
                $quote->addCustomerAddress($shippingAddress);
                $shipping->setCustomerAddressData($shippingAddress);
                $shipping->setCustomerAddressId($shippingAddress->getId());
            }
        }

        if (!$billing->getCustomerId() || $billing->getSaveInAddressBook()) {
            if ($billing->getQuoteId()) {
                $billingAddress = $billing->exportCustomerAddress();
            } else {
                $defaultBilling = $this->customerRepository->getById($customer->getId())->getDefaultBilling();
                if ($defaultBilling) {
                    try {
                        $billingAddress = $this->addressRepository->getById($defaultBilling);
                        // phpcs:ignore Magento2.CodeAnalysis.EmptyBlock
                    } catch (LocalizedException $e) {
                        // no address
                    }
                }
            }
            if (isset($billingAddress)) {
                if (!$hasDefaultBilling) {
                    //Make provided address as default shipping address
                    if (!$hasDefaultShipping) {
                        //Make provided address as default shipping address
                        $billingAddress->setIsDefaultShipping(true);
                    }
                    $billingAddress->setIsDefaultBilling(true);
                }
                $billingAddress->setCustomerId($quote->getCustomerId());
                $this->addressRepository->save($billingAddress);
                $quote->addCustomerAddress($billingAddress);
                $billing->setCustomerAddressData($billingAddress);
                $billing->setCustomerAddressId($billingAddress->getId());
            }
        }
        if ($shipping && !$shipping->getCustomerId() && !$hasDefaultBilling) {
            $shipping->setIsDefaultBilling(true);
        }
    }
}
