<?php

namespace IWD\PaypalPos\Plugin\Product\Option;

class SelectPlugin
{
    /**
     * @param \Magento\Catalog\Model\Product\Option\Type\Select $subject
     * @param $values
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeValidateUserValue(\Magento\Catalog\Model\Product\Option\Type\Select $subject, $values)
    {
        //fix Magento <= 2.3.3 bug https://github.com/magento/magento2/issues/23863
        if (!$this->isSingleSelection($subject)) {
            if (isset($values[$subject->getOption()->getId()]) && is_string($values[$subject->getOption()->getId()])) {
                $values[$subject->getOption()->getId()] = explode(',', $values[$subject->getOption()->getId()]);
                return [$values];
            }
        }
    }

    /**
     * @param $subject
     * @return bool
     */
    private function isSingleSelection($subject)
    {
        return in_array($subject->getOption()->getType(), [
            'drop_down' => \Magento\Catalog\Api\Data\ProductCustomOptionInterface::OPTION_TYPE_DROP_DOWN,
            'radio' => \Magento\Catalog\Api\Data\ProductCustomOptionInterface::OPTION_TYPE_RADIO,
        ], true);
    }
}
