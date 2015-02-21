<?php

namespace KS\DealBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\DataCollector\EventListener\DataCollectorListener;

class DynamicForm extends AbstractType
{

    private $formConfig = array();

    public function __construct($formConfig = array()){
        $this->formConfig = $formConfig;
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {	

        foreach ($this->formConfig as $key => $field) {
            $category = isset($field['category']) ? $field['category'] : null;
            $options = isset($field['options'])  ? $field['options']  : array();
            $builder->add($key, $category, $options);
        }

    }   

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return '';
    }
}