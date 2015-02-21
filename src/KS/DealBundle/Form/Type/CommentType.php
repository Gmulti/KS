<?php

namespace KS\DealBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class CommentType extends DynamicForm
{

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'KS\DealBundle\Entity\Comment',
            'csrf_protection' => false
        ));



    }
    
}