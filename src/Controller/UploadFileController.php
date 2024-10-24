<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use App\ApiResource\ArticleReferenceUploadApiModel;
use Symfony\Component\HttpFoundation\File\File as FileObject;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\FilesCustom;


class UploadFileController extends AbstractController {

    #[Route("/upload/file", name: 'upload_file', methods: ["POST"])]
    public function upload( Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $uploadedFile = $request->files->get('file');

        $destination = $this->getParameter('kernel.project_dir').'/public/uploads';

        $originalFileName= pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);

        $newFileName = $originalFileName.'-'.uniqid().'.'.$uploadedFile->guessExtension();

        $uploadFile =$uploadedFile->move($destination,$newFileName);

        $file = new FilesCustom();
        $file->filename= $newFileName;
        $file->mimeType= $uploadedFile->getClientMimeType();
        $file->size= $uploadFile->getSize();

        $entityManagerInterface->persist($file);
        $entityManagerInterface->flush();


        return $this->json([
            'message' => 'Saved file '.$file->id,
        ]);
    }
}