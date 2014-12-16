<?php

namespace KS\PostBundle\Form\DataTransformer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use KS\PostBundle\Form\DataTransformer\MediaTransformer;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

class MediaTransformerType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ManagerRegistry $om)
    {
        $this->om = $om;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new MediaTransformer($this->om);
        $builder->addModelTransformer($transformer);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'invalid_message' => 'The selected media does not exist',
        ));
    }

    public function getParent()
    {
        return 'file';
    }

    public function getName()
    {
        return 'media_selector';
    }
}