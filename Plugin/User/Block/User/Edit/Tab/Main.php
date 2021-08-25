<?php

namespace IWD\PaypalPos\Plugin\User\Block\User\Edit\Tab;

class Main
{
    private $registry;

    public function __construct(\Magento\Framework\Registry $registry)
    {
        $this->registry = $registry;
    }

    public function aroundGetFormHtml(\Magento\User\Block\User\Edit\Tab\Main $subject, \Closure $proceed)
    {
        $form = $subject->getForm();
        $fieldset = $form->getElement('base_fieldset');

        $model = $this->registry->registry('permissions_user');
        if (is_object($fieldset)) {
            $fieldset->addField(
                'paypal_password',
                'text',
                [
                    'name' => 'paypal_password',
                    'label' => __('PIN'),
                    'title' => __('PIN'),
                    'maxlength' => 4,
                    'class' => 'validate-number maximum-length-4 minimum-length-4 validate-length'
                ]
            );

            $data = $model->getData();
            unset($data['password']);
            unset($data['current_password']);

            $form->setValues($data);
            $subject->setForm($form);
        }

        return $proceed();
    }
}
