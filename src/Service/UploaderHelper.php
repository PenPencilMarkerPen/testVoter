<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

class UploaderHelper{

    private string $uploadsPath;
    private string $filename;

    public function __construct(string $uploadsPath)
    {
        $this->uploadsPath = $uploadsPath;
    }

    public function uploadFilesImage(UploadedFile $uploadedFile):File
    {
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $this->filename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
        $resultUploadFile = $uploadedFile->move(
            $this->uploadsPath,
            $this->filename,
        );
        return $resultUploadFile;
    }

    public function getFileName()
    {
        return $this->filename;
    }

}