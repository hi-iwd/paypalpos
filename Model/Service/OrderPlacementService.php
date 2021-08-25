<?php

declare(strict_types=1);

namespace IWD\PaypalPos\Model\Service;

use IWD\PaypalPos\Api\OrderPlacementInterface;
use Magento\Sales\Model\Service\OrderService;
use Exception;
use Magento\Sales\Api\Data\OrderInterface;
use IWD\PaypalPos\Model\DefaultShippingInformationManagement;

/**
 * @inheritDoc
 */
class OrderPlacementService implements OrderPlacementInterface
{
    /**
     * @var OrderService
     */
    private $orderManagement;
    /**
     * @var DefaultShippingInformationManagement
     */
    private $defaultShippingInformationManagement;

    /**
     * OrderPlacementService constructor.
     *
     * @param OrderService $orderService
     * @param DefaultShippingInformationManagement $defaultShippingInformationManagement
     */
    public function __construct(
        OrderService $orderService,
        DefaultShippingInformationManagement $defaultShippingInformationManagement
    ) {
        $this->orderManagement = $orderService;
        $this->defaultShippingInformationManagement = $defaultShippingInformationManagement;
    }

    /**
     * fix magento bug. V1/order/create won't work correct
     * @param OrderInterface $entity
     * @return OrderInterface
     * @throws Exception
     */
    public function save(OrderInterface $entity): OrderInterface
    {
        $this->addAddress($entity);
        $this->addBundleOptions($entity->getItems());
        return $this->orderManagement->place($entity);
    }

    /**
     * @param $items
     */
    private function addBundleOptions($items)
    {
        $options = [];
        foreach ($items as $item) {
            $bundleOptions = $item->getExtensionAttributes()->getBundleOptions();
            if ($bundleOptions) {
                foreach ($bundleOptions as $bundleOption) {
                    if (!isset($options[$bundleOption->getOptionId()])) {
                        $options[$bundleOption->getOptionId()] = [
                            'option_id' => $bundleOption->getOptionId(),
                            'label' => $bundleOption->getLabel(),
                            'value' => [],
                        ];
                        foreach ($bundleOption->getValue() as $value) {
                            $options[$bundleOption->getOptionId()]['value'][] = [
                                'title' => $value->getTitle(),
                                'qty' => $value->getQty(),
                                'price' => $value->getPrice(),
                            ];
                        }
                    }
                }
                $optionArr['bundle_options'] = $options;
                $productCalculations = ($item->getExtensionAttributes()->getBundleProductCalculations())
                    ? $item->getExtensionAttributes()->getBundleProductCalculations()
                    : 0;
                $optionArr['product_calculations'] = $productCalculations;
                $shipmentType = ($item->getExtensionAttributes()->getBundleShipmentType())
                    ? $item->getExtensionAttributes()->getBundleShipmentType()
                    : 0;
                $optionArr['shipment_type'] = $shipmentType;
                $item->setProductOptions($optionArr);
            }
        }
    }

    /**
     * @param OrderInterface $entity
     */
    private function addAddress(OrderInterface $entity)
    {
        if (!$entity->getBillingAddress()->getPostcode() && !$entity->getBillingAddress()->getRegionId()) {
            $this->defaultShippingInformationManagement->assignBillingAddressToOrder($entity);
        }
        $shippingAssignments = $entity->getExtensionAttributes()->getShippingAssignments();
        if (!empty($shippingAssignments)) {
            $address = $shippingAssignments[0]->getShipping()->getAddress();
            if (!$address->getPostcode() && !$address->getRegionId()) {
                $this->defaultShippingInformationManagement->assignShippingAddressToOrder($entity);
            }
        }
    }
}
