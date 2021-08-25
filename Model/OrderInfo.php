<?php

namespace IWD\PaypalPos\Model;

use IWD\PaypalPos\Model\Payment\PaypalPos;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactoryInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use IWD\PaypalPos\Model\Payment\VenmoQR;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class OrderInfo
{
    private $collectionFactory;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        CollectionFactoryInterface $collectionFactory,
        StoreManagerInterface $storeManager
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
    }

    public function getData()
    {
        $this->storeManager->setCurrentStore(Store::DEFAULT_STORE_ID);
        $url = $this->storeManager->getStore()->getBaseUrl();

        $date = new \DateTime();
        $startDate = $date->format("Y-m-d H:i:s");
        $date->modify("-1 day");
        $endDate = $date->format("Y-m-d H:i:s");

        $orders = $this->collectionFactory->create()->addFieldToSelect(
            '*'
        );

        $orders->getSelect()
            ->join(
                ["sop" => "sales_order_payment"],
                'main_table.entity_id = sop.parent_id',
                ['method']
            )
            ->where('sop.method = `'.PaypalPos::PAYMENT_METHOD_CODE .'` OR sop.method =`'.VenmoQR::PAYMENT_METHOD_CODE.'`');
        $orders
            ->addFieldToFilter(
                'created_at',
                ['lteq' => $startDate]
            )
            ->addFieldToFilter(
                'created_at',
                ['gteq' => $endDate]
            );
        $orders->setOrder(
            'created_at',
            'desc'
        );

        $ordersData = [];
        foreach ($orders as $k => $order) {
            $data = $order->getData();
            $ordersData[$k]['created_at'] = $data['created_at'];
            $ordersData[$k]['amount'] = $data['grand_total'];
            $ordersData[$k]['url'] = $url;
        }
        return $ordersData;
    }


}

