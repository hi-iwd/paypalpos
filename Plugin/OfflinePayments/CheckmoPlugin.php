<?php

namespace IWD\PaypalPos\Plugin\OfflinePayments;

use Magento\Framework\Exception\LocalizedException;

class CheckmoPlugin
{
    /**
     * @param \Magento\OfflinePayments\Model\Checkmo $subject
     * @param $result
     * @param $data
     * @return $this
     * @throws LocalizedException
     */
    public function afterAssignData(\Magento\OfflinePayments\Model\Checkmo $subject, $result, $data)
    {
        $additionalData = $data->getAdditionalData();

        if (isset($additionalData) && isset($additionalData['pos_user'])) {
            $subject->getInfoInstance()->setAdditionalInformation('pos_user', $additionalData['pos_user']);
        }
        return $result;
    }
}
