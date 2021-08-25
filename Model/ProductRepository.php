<?php

namespace IWD\PaypalPos\Model;

use IWD\PaypalPos\Api\ProductRepositoryInterface;
use Magento\InventorySalesApi\Api\GetProductSalableQtyInterface;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class ProductRepository implements ProductRepositoryInterface
{
    /**
     * @var Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var GetProductSalableQtyInterface
     */
    private $getProductSalableQty;

    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        GetProductSalableQtyInterface $getProductSalableQty
    ) {
        $this->productRepository = $productRepository;
        $this->getProductSalableQty = $getProductSalableQty;
    }

    /**
     * @param string $sku
     * @return mixed
     */
    public function getBySkuWithSalableQty($sku)
    {
        $product = $this->productRepository->get($sku);
        if ($product->getExtensionAttributes()) {
            try {
                $stockId = $product->getExtensionAttributes()->getStockItem()->getStockId();
                $salableQty = $this->getProductSalableQty->execute($sku, $stockId);
                $product->getExtensionAttributes()->setSalableQty($salableQty);
            } catch (\Exception $e) {
                //Can't check requested quantity for products without Source Items support
                $product->getExtensionAttributes()->setSalableQty(null);
            }

        }
        return $product;
    }
}
