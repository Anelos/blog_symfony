<?php
/**
 * Created by PhpStorm.
 * User: anelos
 * Date: 29/09/17
 * Time: 12:28
 */

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->getTargetDir(), $fileName);
        return 'this'.$fileName;
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }
}