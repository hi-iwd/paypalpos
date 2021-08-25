<?php
declare(strict_types=1);

namespace IWD\PaypalPos\Model\Quote;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\CartManagementInterface;

class QuoteManagement implements \IWD\PaypalPos\Api\CartManagementInterface
{
    /**
     * @var CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @var CartManagement
     */
    private $cartManagement;

    /**
     * QuoteManagement constructor.
     * @param CartRepositoryInterface $quoteRepository
     * @param CartManagementInterface $cartManagement
     */
    public function __construct(
        CartRepositoryInterface $quoteRepository,
        CartManagementInterface $cartManagement
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->cartManagement = $cartManagement;
    }

    /**
     * @param int $customerId
     * @return int
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function createNewCartForCustomer($customerId) : int
    {
        try {
            $quote = $this->quoteRepository->getActiveForCustomer($customerId);
            $this->quoteRepository->delete($quote);
            // phpcs:ignore Magento2.CodeAnalysis.EmptyBlock
        } catch (\Exception $e) {
            //no quote
        }

        return $this->cartManagement->createEmptyCartForCustomer($customerId);
    }

    /**
     * @param int $cartId
     * @return mixed|string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function reserveOrderId($cartId)
    {
        $quote = $this->quoteRepository->get($cartId);
        $quote->reserveOrderId();
        $this->quoteRepository->save($quote);

        return $quote->getReservedOrderId();
    }
}
