<?php

namespace IWD\PaypalPos\Plugin\Sales;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use IWD\PaypalPos\Api\UserAttributeRepositoryInterface;
use IWD\PaypalPos\Api\Data\UserAttributeInterface;
use IWD\PaypalPos\Api\Data\UserAttributeInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class OrderRepositoryPlugin
{
    private $additionalInformationKey = [
        'paypal_invoice_id',
        'transaction',
        'order_reference_id',
        'offline_invoice_id',
        'creator'
    ];
    /**
     * @var UserAttributeRepositoryInterface
     */
    private $userAttributeRepository;
    /**
     * @var UserAttributeInterfaceFactory
     */
    private $userAttributeFactory;

    /**
     * OrderRepositoryPlugin constructor.
     * @param UserAttributeRepositoryInterface $userAttributeRepository
     * @param UserAttributeInterfaceFactory $userAttributeFactory
     */
    public function __construct(
        UserAttributeRepositoryInterface $userAttributeRepository,
        UserAttributeInterfaceFactory $userAttributeFactory
    ) {
        $this->userAttributeRepository = $userAttributeRepository;
        $this->userAttributeFactory = $userAttributeFactory;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $entity
     */
    public function beforeSave(OrderRepositoryInterface $subject, OrderInterface $entity)
    {
        $extensionAttributes = $entity->getExtensionAttributes();
        $paymentAdditionalInfo = $extensionAttributes->getPaymentAdditionalInfo();
        if ($extensionAttributes && $paymentAdditionalInfo) {
            $infoInstance = $entity->getPayment()->getMethodInstance()->getInfoInstance();
            foreach ($paymentAdditionalInfo as $additionalData) {
                if (in_array($additionalData->getKey(), $this->additionalInformationKey)) {
                    $infoInstance->setAdditionalInformation($additionalData->getKey(), $additionalData->getValue());
                }
            }
        }
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $result
     * @return OrderInterface
     * @throws CouldNotSaveException
     */
    public function afterSave(
        OrderRepositoryInterface $subject,
        OrderInterface $result
    ) {
        $extensionAttributes = $result->getExtensionAttributes();
        if (null !== $extensionAttributes &&
            null !== $extensionAttributes->getPosUser()
        ) {
            $posUserId = $extensionAttributes->getPosUser();
            if ($this->checkPosUser($result, $posUserId)) {
                return $result;
            }

            try {
                $userAttribute = $this->userAttributeFactory->create();
                $userAttribute->setUserId($posUserId);
                $userAttribute->setOrderId($result->getEntityId());
                $this->userAttributeRepository->save($userAttribute);
            } catch (\Exception $e) {
                throw new CouldNotSaveException(
                    __('Could not add attribute to order: "%1"', $e->getMessage()),
                    $e
                );
            }
        }
        return $result;
    }

    /**
     * @param $order
     * @param $posUserId
     * @return bool
     */
    private function checkPosUser($order, $posUserId)
    {
        try {
            $userAttribute = $this->userAttributeRepository->getByOrderId($order->getEntityId());
        } catch (NoSuchEntityException $e) {
            return false;
        }

        return ($posUserId == $userAttribute->getUserId()) ? true : false;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $result
     * @return OrderInterface
     */
    public function afterGet(
        OrderRepositoryInterface $subject,
        OrderInterface $result
    ) {
        try {
            $userAttribute = $this->userAttributeRepository->getByOrderId($result->getEntityId());
        } catch (NoSuchEntityException $e) {
            return $result;
        }

        $extensionAttributes = $result->getExtensionAttributes();
        $orderExtension = $extensionAttributes ? $extensionAttributes : $this->orderExtensionFactory->create();
        $orderExtension->setPosUser($userAttribute->getUserId());
        $result->setExtensionAttributes($orderExtension);

        return $result;
    }
}
