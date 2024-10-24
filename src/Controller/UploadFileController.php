<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\FilesCustom;
use App\Service\UploaderHelper;


class UploadFileController extends AbstractController {

    #[Route("/upload/file", name: 'upload_file', methods: ["POST"])]
    public function upload( Request $request, EntityManagerInterface $entityManagerInterface, UploaderHelper $uploaderHelper): JsonResponse
    {
        $uploadedFile = $request->files->get('file');

        $upldHelp = $uploaderHelper->uploadFilesImage($uploadedFile);

        $file = new FilesCustom();
        $file->filename= $uploaderHelper->getFileName();
        $file->mimeType= $uploadedFile->getClientMimeType();
        $file->size= $upldHelp->getSize();

        $entityManagerInterface->persist($file);
        $entityManagerInterface->flush();

        return $this->json([
            'message' => 'Saved file '.$file->id,
        ]);
    }
}