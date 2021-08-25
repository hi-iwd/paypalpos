<?php

declare(strict_types=1);

namespace IWD\PaypalPos\Api;

use Magento\Sales\Api\Data\OrderInterface;

interface OrderPlacementInterface
{
    /**
     * @param \Magento\Sales\Api\Data\OrderInterface $entity
     * @return \Magento\Sales\Api\Data\OrderInterface
     */
    public function save(OrderInterface $entity): OrderInterface;
}
