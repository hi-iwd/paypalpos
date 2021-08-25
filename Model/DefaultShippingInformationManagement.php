<?php

namespace IWD\PaypalPos\Model;

use IWD\PaypalPos\Api\DefaultShippingInformationManagementInterface;
use IWD\PaypalPos\Model\Customer\Address\DefaultAddress;
use Magento\Checkout\Api\ShippingInformationManagementInterface;
use Magento\Checkout\Api\Data\ShippingInformationInterfaceFactory;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\AddressInterfaceFactory;
use Magento\Quote\Api\BillingAddressManagementInterface;
use Magento\Sales\Api\Data\OrderInterface;

class DefaultShippingInformationManagement implements DefaultShippingInformationManagementInterface
{
    /**
     * @var DefaultAddress
     */
    private $defaultAddress;
    /**
     * @var ShippingInformationManagementInterface
     */
    private $shippingInformationManagement;

    /**
     * @var AddressInterfaceFactory
     */
    private $addressFactory;
    /**
     * @var ShippingInformationInterfaceFactory
     */
    private $shippingInformationFactory;
    /**
     * @var BillingAddressManagementInterface
     */
    private $billingAddressManagement;

    public function __construct(
        DefaultAddress $defaultAddress,
        ShippingInformationManagementInterface $shippingInformationManagement,
        ShippingInformationInterfaceFactory $shippingInformationFactory,
        AddressInterfaceFactory $addressFactory,
        BillingAddressManagementInterface $billingAddressManagement
    ) {
        $this->defaultAddress = $defaultAddress;
        $this->shippingInformationManagement = $shippingInformationManagement;
        $this->shippingInformationFactory = $shippingInformationFactory;
        $this->addressFactory = $addressFactory;
        $this->billingAddressManagement = $billingAddressManagement;
    }

    /**
     * @param int $cartId
     * @return \Magento\Checkout\Api\Data\PaymentDetailsInterface
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function saveDefaultAddressInformation($cartId)
    {
        $this->billingAddressManagement->assign($cartId, $this->createAddress());
        return $this->shippingInformationManagement->saveAddressInformation($cartId, $this->getDefaultAddress());
    }

    /**
     * @return AddressInterface
     */
    private function createAddress()
    {
        $address = $this->addressFactory->create();
        $address->setData($this->defaultAddress->get());
        return $address;
    }

    /**
     * @return ShippingInformationInterface
     */
    private function getDefaultAddress()
    {
        $address = $this->createAddress();

        return $this->shippingInformationFactory->create(['data' => [
            ShippingInformationInterface::SHIPPING_ADDRESS => $address,
            ShippingInformationInterface::SHIPPING_CARRIER_CODE => 'paypalposshipping',
            ShippingInformationInterface::SHIPPING_METHOD_CODE => 'paypalposshipping',
        ]
        ]);
    }

    public function assignShippingAddressToOrder(OrderInterface $entity)
    {
        $shippingAssignments = $entity->getExtensionAttributes()->getShippingAssignments();
        $address = $this->defaultAddress->get();
        $address['address_type'] = 'shipping';
        $shippingAssignments[0]->getShipping()->getAddress()->setData($address);

        return $entity;
    }

    /**
     * @param OrderInterface $entity
     * @return OrderInterface
     */
    public function assignBillingAddressToOrder(OrderInterface $entity)
    {
        $address = $this->defaultAddress->get();
        $address['address_type'] = 'billing';
        $entity->getBillingAddress()->setData($address);
        return $entity;
    }
}
