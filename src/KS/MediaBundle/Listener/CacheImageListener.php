<?php
namespace KS\MediaBundle\Listener;

use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use KS\MediaBundle\Entity\Media;
use KS\DealBundle\Entity\Deal;
use KS\DealBundle\Entity\Comment;
use KS\UserBundle\Entity\User;

use Imagine\Exception\RuntimeException;



class CacheImageListener
{
	protected $cacheManager;
	
	protected $dataManager;

	protected $filterManager;

	protected $entities;

	protected $thumbnails = array(
		'image_deal',
		'image_deal_large',
		'user_profile_tile',
		'user_profile_tile_large',
        'user_profile_tile_desktop'
	);

	public function __construct($cacheManager, DataManager $dataManager, FilterManager $filterManager){
		$this->cacheManager = $cacheManager;
		$this->dataManager = $dataManager;
		$this->filterManager = $filterManager;
	}

	public function onFlush(OnFlushEventArgs $event){
        $this->entities = [];

        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {

            if ($entity instanceOf Deal || 
            	$entity instanceOf Comment || 
            	$entity instanceOf User || 
            	$entity instanceOf Media) {

                $this->entities[] = $entity;
            }
        }

    }

	public function postFlush(PostFlushEventArgs $args){
		$thumbnailsUrl = array();

		$em  = $args->getEntityManager();
		$uow = $em->getUnitOfWork();

		if (!empty($this->entities)) {
			foreach ($this->entities as $entity) {
    			if(!$entity instanceOf Media){
    				$medias = $entity->getMedias();
					if(count($medias) > 0){
						foreach ($medias as $key => $media) {
							$path = $media->getPathImagine();
							foreach ($this->thumbnails as $key => $thumb) {
								$url = $this->resolveMedia($path,$thumb);
								if(!empty($url)){
									$thumbnailsUrl[$thumb] = $url;
								}
							}
							$media->setThumbnailsUrl($thumbnailsUrl);
							$em->persist($media);
						}
					}
    			}
    			else{
    				$path = $entity->getPathImagine();
					foreach ($this->thumbnails as $key => $thumb) {
						$url = $this->resolveMedia($path,$thumb);
						if(!empty($url)){
							$thumbnailsUrl[$thumb] = $url;
						}
					}
					$entity->setThumbnailsUrl($thumbnailsUrl);
					$em->persist($entity);
    			}
        	}
        	$em->flush();
		}		
	}

	private function resolveMedia($path,$filter){
		try {
			$url = null;
            if (!$this->cacheManager->isStored($path, $filter)) {

                $binary = $this->dataManager->find($filter, $path);

                $this->cacheManager->store(
                    $this->filterManager->applyFilter($binary, $filter),
                    $path,
                    $filter
                );
            }

            return $this->cacheManager->resolve($path, $filter);

        } catch (RuntimeException $e) {
            throw new \RuntimeException(sprintf('Unable to create image for path "%s" and filter "%s". Message was "%s"', $path, $filter, $e->getMessage()), 0, $e);
        }
	}

	public function preUpdate(LifecycleEventArgs $args){
		$entity = $args->getEntity();

		if ($entity instanceOf Deal || $entity instanceOf Comment || $entity instanceOf Media) {
			$entity->getMedia()->setPath($this->cacheManager->getBrowserPath($entity->getPathImagine(),'image_deal'));
		}
	}

	public function postUpdate(LifecycleEventArgs $args){
		$entity = $args->getEntity();

		if ($entity instanceof Media) {
			$this->cacheManager->remove($entity->getWebPath());
		}
	}

	public function preRemove(LifecycleEventArgs $args){
		$entity = $args->getEntity();
		if ($entity instanceof Media) {
			$this->cacheManager->remove($entity->getWebPath());
		}
	}
}