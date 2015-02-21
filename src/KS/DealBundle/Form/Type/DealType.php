<?php

namespace KS\DealBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class DealType extends DynamicForm
{

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'KS\DealBundle\Entity\Deal',
            'csrf_protection' => false
        ));



    }
    
}