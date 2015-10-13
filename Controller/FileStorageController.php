<?php

namespace Daemon\FilestorageBundle\Controller;

use Daemon\FilestorageBundle\Entity\DaemonFile;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

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
        $daemonFile = $this->get('daemon_filestorage')->getFile($hashcode);
        /** @var DaemonFile $daemonFile */
        //print_r($daemonFile->getOrigname());die;
    //    $daemonFile = $this->entityManager->getRepository('DaemonFilestorageBundle:DaemonFile')->findOneBy(array('hashcode' => $hashcode));
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
}