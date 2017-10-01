<?php
/**
 * Created by PhpStorm.
 * User: anelos
 * Date: 29/09/17
 * Time: 12:25
 */

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use AppBundle\Entity\User;
use AppBundle\Service\FileUploader;

class AvatarUploadListener
{
    private $uploader;
    private $fileName;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    private function uploadFile($entity)
    {
        // upload only works for User entities
        if (!$entity instanceof User) {
            return;
        }

        $file = $entity->getAvatar();

        // only upload new files
        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file);
        }
        if (isset($fileName)) {
            $entity->setAvatar($this->fileName);
        }
    }
}