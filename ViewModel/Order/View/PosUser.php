<?php

declare(strict_types=1);

namespace IWD\PaypalPos\ViewModel\Order\View;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use IWD\PaypalPos\Api\UserAttributeRepositoryInterface;
use IWD\PaypalPos\Api\AdminUserRepositoryInterface;
use Magento\Framework\App\RequestInterface;

class PosUser implements ArgumentInterface
{
    /**
     * @var UserAttributeRepositoryInterface
     */
    private $userAttributeRepository;
    /**
     * @var AdminUserRepositoryInterface
     */
    private $adminUserRepository;
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * PosUser constructor.
     * @param UserAttributeRepositoryInterface $userAttributeRepository
     * @param AdminUserRepositoryInterface $adminUserRepository
     * @param RequestInterface $request
     */
    public function __construct(
        UserAttributeRepositoryInterface $userAttributeRepository,
        AdminUserRepositoryInterface $adminUserRepository,
        RequestInterface $request
    ) {
        $this->userAttributeRepository = $userAttributeRepository;
        $this->adminUserRepository = $adminUserRepository;
        $this->request = $request;
    }

    /**
     * @param $orderId
     * @return bool|string
     */
    public function getUsername()
    {
        $orderId = $this->getOrderId();
        try {
            $userForOrder = $this->userAttributeRepository->getByOrderId($orderId);
            $adminUser = $this->adminUserRepository->getById($userForOrder->getUserId());
        } catch (\Exception $e) {
            return false;
        }

        return $adminUser->getUsername();
    }

    /**
     * @return int
     */
    private function getOrderId()
    {
        return (int)$this->request->getParam('order_id');
    }
}
