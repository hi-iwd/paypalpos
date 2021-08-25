<?php

namespace IWD\PaypalPos\Api\Data;

interface BundleOrderItemOptionInterface
{
    /**
     * @return int
     */
    public function getOptionId();
    /**
     * @param int $optionId
     * @return $this
     */
    public function setOptionId($optionId);
    /**
     * @return string
     */
    public function getLabel();
    /**
     * @param string $label
     * @return $this
     */
    public function setLabel($label);
    /**
     * @param \IWD\PaypalPos\Api\Data\BundleOrderItemOptionValueInterface[] $value
     * @return $this
     */
    public function setValue($value);

    /**
     * @return \IWD\PaypalPos\Api\Data\BundleOrderItemOptionValueInterface[]
     */
    public function getValue();
}
