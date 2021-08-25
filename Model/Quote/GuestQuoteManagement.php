<?php
declare(strict_types=1);

namespace IWD\PaypalPos\Model\Quote;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\QuoteIdMask;
use Magento\Quote\Model\QuoteIdMaskFactory;

class GuestQuoteManagement implements \IWD\PaypalPos\Api\GuestCartManagementInterface
{
    /**
     * @var CartRepositoryInterface
     */
    private $quoteRepository;
    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteIdMaskFactory;

    /**
     * QuoteManagement constructor.
     * @param CartRepositoryInterface $quoteRepository
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     */
    public function __construct(
        CartRepositoryInterface $quoteRepository,
        QuoteIdMaskFactory $quoteIdMaskFactory
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
    }

    /**
     * @param sting $cartId
     * @return mixed|string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function reserveOrderId($cartId)
    {
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        $quote = $this->quoteRepository->get($quoteIdMask->getQuoteId());
        $quote->reserveOrderId();
        $this->quoteRepository->save($quote);

        return $quote->getReservedOrderId();
    }
}
