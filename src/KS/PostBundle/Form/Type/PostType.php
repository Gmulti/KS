<?php

namespace KS\PostBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\DataCollector\EventListener\DataCollectorListener;

use KS\PostBundle\Document\Post;
use KS\PostBundle\Form\DataTransformer\UserTransformer;

class PostType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {	

    	$builder
    	    ->add('content')
    	    ->add('user', 'user_selector')
            ->add('media','media_selector')
            ->add('rating')
    	;
        
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'KS\PostBundle\Document\Post',
            'csrf_protection' => false
        ));



    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return '';
    }
}