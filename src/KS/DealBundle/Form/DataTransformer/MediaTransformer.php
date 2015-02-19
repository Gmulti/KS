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

        return "";
    }

    public function reverseTransform($files)
    {
        if(empty($files)){
            return null;
        }

        $medias = new \Doctrine\Common\Collections\ArrayCollection();

        foreach ($files as $key => $file) {
            if (!$file instanceOf UploadedFile) {
                return null;
            }
            else{
                $media = new Media();
                $media->setFile($file);
                $medias->add($media);
            }
        }
       
        return $medias;
    }

    public function getName()
    {
        return 'media_selector';
    }
}