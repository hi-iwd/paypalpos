<?php

namespace IWD\PaypalPos\Api\Data;

interface BundleOrderItemOptionValueInterface
{
    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title);
    /**
     * @return int
     */
    public function getQty();

    /**
     * @param int $qty
     * @return $this
     */
    public function setQty($qty);

    /**
     * @param float $price
     * @return $this
     */
    public function setPrice($price);
    /**
     * @return float
     */
    public function getPrice();
}
