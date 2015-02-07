<?php

namespace KS\DealBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use KS\MediaBundle\Entity\Media;

class MediaTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @param ObjectManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return string
     */
    public function transform($media)
    {
        if (null === $media) {
            return "";
        }

        return $media;
    }

    public function reverseTransform($file)
    {

    	if (!$file instanceOf UploadedFile) {
            return null;
        }

        $media = new Media();
        $media->setFile($file);


        return $media;
    }

    public function getName()
    {
        return 'media_selector';
    }
}