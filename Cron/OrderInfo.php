<?php
declare(strict_types=1);

namespace IWD\PaypalPos\Cron;

use IWD\PaypalPos\Model\OrderInfo as OrderInfoModel;
use Psr\Log\LoggerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\HTTP\Client\Curl;

class OrderInfo
{
    const URL = 'https://pos.iwdagency.com';
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var OrderInfoModel
     */
    private $orderInfoModel;
    /**
     * @var Curl
     */
    private $curl;

    /**
     * Constructor
     *
     * @param LoggerInterface $logger
     * @param OrderInfoModel $orderInfoModel
     * @param Curl $curl
     */
    public function __construct(
        LoggerInterface $logger,
        OrderInfoModel $orderInfoModel,
        Curl $curl
    ) {
        $this->logger = $logger;
        $this->orderInfoModel = $orderInfoModel;
        $this->curl = $curl;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        $data = $this->orderInfoModel->getData();
        if ($data) {
            $this->curl->addHeader("Content-Type", "application/json");
            $this->curl->post(self::URL, json_encode($data));
        }
    }
}



