<?php

namespace KS\PostBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use KS\MediaBundle\Document\Media;

class MediaTransformer implements DataTransformerInterface
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