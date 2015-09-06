<?php

namespace Daemon\FilestorageBundle\Controller;

use Daemon\FilestorageBundle\Component\Data\Enum\FileType;
use Daemon\FilestorageBundle\Entity\DaemonFile;
//use Daemon\SimplifyBundle\Component\Helper\FileSystemHelper;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\KernelInterface;

class FileStorageController extends Controller {

    /**
     * @var EntityManager
     */
    private $entityManager;


    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->entityManager = $this->getDoctrine()->getManager();
    }


    /**
     * Gets the actual file which is belonging to the DaemonFile
     *
     * @param string $hashcode
     * @return UploadedFile
     */
    public function getFileAction($hashcode) {

        $daemonFile = $this->entityManager->getRepository('DaemonFilestorageBundle:DaemonFile')->findOneBy(array('hashcode' => $hashcode));
        $file = new UploadedFile($this->get('kernel')->getRootDir() . "/" . $daemonFile->getPath(), $daemonFile->getOrigname(), $daemonFile->getMimeType());
        $response = new BinaryFileResponse($file);
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $daemonFile->getOrigname(),
            iconv('UTF-8', 'ASCII//TRANSLIT', $daemonFile->getOrigname())
        );
        return $response;
    }


    /**
     * Saves an uploaded file + file info (files are being saved inside the storage folder
     *
     * @param UploadedFile $file
     * @return DaemonFile
     */
    public function save(UploadedFile $file) {
        $hashCode = sha1_file($file);
        $daemonFile = $this->entityManager->getRepository("DaemonFilestorageBundle:DaemonFile")
            ->findOneBy(array("hashcode" => $hashCode));

        if (isset($daemonFile)) {
            if ($daemonFile->getOrigname() != $file->getClientOriginalName()) {
                $daemonFile->setOrigname($file->getClientOriginalName());
            }
        }
        else {
            $daemonFile = new DaemonFile();
            $daemonFile->setOrigname($file->getClientOriginalName());
            $daemonFile->setExtension(substr($daemonFile->getOrigname(), strrpos($file->getClientOriginalName(),".") + 1));
            $daemonFile->setFileType($this->guessFileType($file));
            $daemonFile->setMimeType($file->getMimeType());
            $md5 = md5(uniqid(rand(), true));
            $daemonFile->setFilename($md5);

            $path = "../storage/" . date("Y") . "/" . date("m") . "/" . date("d");
  //          FileSystemHelper::createPathIfNotExists($path);

            $hashCode = sha1_file($file);
            $daemonFile->setHashcode($hashCode);

            $file->move($path, $hashCode . "." . $daemonFile->getExtension());
            $daemonFile->setPath($path . "/" . $hashCode . "." . $daemonFile->getExtension());

            $this->entityManager->persist($daemonFile);
        }
        return $daemonFile;
    }



    public function fileExists(DaemonFile $daemonFile) {
        $fileSystem = new Filesystem();
        return $fileSystem->exists($daemonFile->getPath());
    }


    /**
     * Deletes a DaemonFile and the linked file inside the storage folder on the hard drive
     *
     * @param DaemonFile $daemonFile
     */
    public function delete (DaemonFile $daemonFile) {
        $fileSystem = new Filesystem();
        $fileSystem->remove($daemonFile->getPath());
        $this->entityManager->remove($daemonFile);
        $this->entityManager->flush();

    }

    /**
     * Out of the FileType-enums the actual FileType is guessed by use of the MimeType
     *
     * @param UploadedFile $file
     * @return string
     */
    private function guessFileType(UploadedFile $file) {
        $fileTypes = FileType::toArray();
        unset($fileTypes[FileType::UNKNOWN]);
        foreach ($fileTypes as $fileType) {
            if (strpos($file->getMimeType(), $fileType) !== false) {
                return $fileType;
            }
        }
        return FileType::UNKNOWN;
    }
}