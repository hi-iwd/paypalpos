<?php

namespace IWD\PaypalPos\Plugin\User\Model;

use Magento\User\Model\ResourceModel\User\CollectionFactory as AdminUserCollectionFactory;
use Magento\User\Model\User as AdminUser;

class User
{

    /**
     * @var AdminUserCollectionFactory
     */
    private $adminUserCollection;
    /**
     * @var AdminUser
     */
    private $adminUser;

    public function __construct(
        AdminUserCollectionFactory $adminUserCollection,
        AdminUser $adminUser
    ) {
        $this->adminUserCollection = $adminUserCollection;
        $this->adminUser = $adminUser;
    }

    /**
     * @param \Magento\User\Model\User $subject
     * @param array|bool $result
     * @return array|bool
     */
    public function afterValidate(
        \Magento\User\Model\User $subject,
        $result
    ) {
        $isValidPaypalPassword = $this->validatePaypalPassword($subject);

        if ($isValidPaypalPassword !== null && $isValidPaypalPassword !== true) {
            $result = is_array($result) ? $result : [];
            $result[] = $isValidPaypalPassword;
        }

        return $result;
    }

    /**
     * @return string|bool
     */
    private function validatePaypalPassword($subject)
    {
        $paypalPassword = $subject->getPaypalPassword();
        if ($paypalPassword) {
            $errorMessage = __('A user with the same paypal password already exists.');
            $currentUserId = $subject->getUserId();

            $userPaypalPasswords = $this->getUsersPaypalPassword($subject);
            $userIds = $this->getUserIds($subject);

            if (in_array($currentUserId, $userIds)) {
                $key = array_search($paypalPassword, $userPaypalPasswords);
                if ($key === false || $key == $currentUserId) {
                    return true;
                }
            }

            if (in_array($paypalPassword, $userPaypalPasswords)) {
                return $errorMessage;
            }
        }
    }

    /**
     * @param $subject
     * @return array
     */
    private function getUsersPaypalPassword($subject)
    {
        $collection = $this->adminUserCollection->create();
        $adminUserData = $collection->getItems();

        $userPaypalPasswords = [];
        foreach ($adminUserData as $key => $adminUser) {
            $userPaypalPasswords[$adminUser->getData('user_id')] = $adminUser->getData('paypal_password');
        }
        return $userPaypalPasswords;
    }

    /**
     * @param $subject
     * @return array
     */
    private function getUserIds($subject)
    {
        $collection = $this->adminUserCollection->create();
        $adminUserData = $collection->getItems();

        $userIds = [];
        foreach ($adminUserData as $key => $adminUser) {
            $userIds[] = $adminUser->getData('user_id');
        }
        return $userIds;
    }
}
