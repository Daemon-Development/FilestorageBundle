<?php

namespace Daemon\FilestorageBundle\Component;

use Daemon\FilestorageBundle\Component\Data\Enum\FileType;
use Daemon\FilestorageBundle\Component\Helper\FileSystemHelper;
use Daemon\FilestorageBundle\Entity\DaemonFile;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;

class FileStorage  {

    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var EntityManager
     */
    private $entityManager;


    /**
     * Receives the Kernel and the EntityManager as service parameters
     *
     * @param KernelInterface $kernel
     * @param EntityManager $entityManager
     */
    public function __construct(KernelInterface $kernel, EntityManager $entityManager) {
        $this->kernel = $kernel;
        $this->entityManager = $entityManager;
    }


    /**
     * Gets the actual file which is belonging to the DaemonFile
     *
     * @param DaemonFile $daemonFile
     * @return UploadedFile
     */
    public function getFile(DaemonFile $daemonFile) {

        return new UploadedFile($this->kernel->getRootDir() . "/" . $daemonFile->getPath(), $daemonFile->getOrigname(), $daemonFile->getMimeType());

    }


    /**
     * Saves an uploaded file + file info (files are being saved inside the storage folder
     *
     * @param UploadedFile $file
     * @return DaemonFile
     */
    public function save(UploadedFile $file) {
        $hashCode =  md5(uniqid(rand(), true)) . md5(uniqid(rand(), true));
        $daemonFile = new DaemonFile();
        $daemonFile->setOrigname($file->getClientOriginalName());
        $daemonFile->setExtension(substr($daemonFile->getOrigname(), strrpos($file->getClientOriginalName(),".") + 1));
        $daemonFile->setFileType($this->guessFileType($file));
        $daemonFile->setMimeType($file->getMimeType());
        $path = "../storage/" . date("Y") . "/" . date("m") . "/" . date("d");
        FileSystemHelper::createPathIfNotExists($path);

        $daemonFile->setHashcode($hashCode);

        $file->move($path, $hashCode . "." . $daemonFile->getExtension());
        $daemonFile->setPath($path . "/" . $hashCode . "." . $daemonFile->getExtension());

        $this->entityManager->persist($daemonFile);
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