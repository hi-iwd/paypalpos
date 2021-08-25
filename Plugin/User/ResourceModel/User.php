<?php

namespace IWD\PaypalPos\Plugin\User\ResourceModel;

use IWD\PaypalPos\Api\UserLoggerRepositoryInterface;
use IWD\PaypalPos\Api\Data\UserLoggerInterface;
use IWD\PaypalPos\Api\Data\UserLoggerInterfaceFactory;
use IWD\PaypalPos\Model\ResourceModel\AdminUserRepository;

class User
{

    /**
     * @var UserLoggerRepositoryInterface
     */
    private $userLoggerRepository;
    /**
     * @var UserLoggerInterface
     */
    private $userLogger;
    /**
     * @var AdminUserRepository
     */
    private $adminUserRepository;

    public function __construct(
        UserLoggerRepositoryInterface $userLoggerRepository,
        UserLoggerInterfaceFactory $userLogger,
        AdminUserRepository $adminUserRepository
    ) {
        $this->userLoggerRepository = $userLoggerRepository;
        $this->userLogger = $userLogger;
        $this->adminUserRepository = $adminUserRepository;
    }

    public function aroundDelete($subject, callable $proceed, $user)
    {
        $entity = null;
        try {
            $entity = $this->adminUserRepository->getById($user->getId());
            // phpcs:ignore Magento2.CodeAnalysis.EmptyBlock
        } catch (\Exception $e) {

        }
        $result = $proceed($user);
        if ($result && $entity) {
            $userLogger = $this->userLogger->create();
            $userLogger->setEmail($entity->getEmail());
            $userLogger->setUserId($entity->getId());
            $userLogger->setUsername($entity->getUsername());
            $this->userLoggerRepository->save($userLogger);
        }
        return $result;
    }
}
