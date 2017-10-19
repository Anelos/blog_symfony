<?php
/**
 * Created by PhpStorm.
 * User: anelos
 * Date: 19/10/17
 * Time: 10:18
 */

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use AppBundle\Entity\Article;
use AppBundle\Service\TagConverter;

class TagConverterListener
{

    private $converter;

    public function __construct(TagConverter $converter)
    {
        $this->converter = $converter;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->convertTag($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->convertTag($entity);
    }

    private function convertTag($entity)
    {
        // upload only works for Article entities
        if (!$entity instanceof Article) {
            return;
        }
        $entity = $this->converter->tagFindOrCreate($entity);

    }
}
