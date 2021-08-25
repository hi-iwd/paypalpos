<?php

namespace IWD\PaypalPos\Api;

use Magento\Framework\Exception\CouldNotSaveException;

interface ProductRepositoryInterface
{
    /**
     * @param string $sku
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    public function getBySkuWithSalableQty($sku);
}
