<?php
/**
 * Created by PhpStorm.
 * User: anelos
 * Date: 29/09/17
 * Time: 12:28
 */

namespace AppBundle\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    const DEFAULT_AVATAR = "uploads/avatars/default-avatar.png";

    private $targetDir;


    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();

        $file->move($this->getTargetDir(), $fileName);
        return $fileName;
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }

    public function removeOldFile($file)
    {
        if (isset($file) && $file != $this->getTargetDir() . self::DEFAULT_AVATAR) {
            $fs = new Filesystem();
            $fs->remove($file);
        }
        return;
    }

}