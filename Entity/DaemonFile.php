<?php

namespace Daemon\FilestorageBundle\Entity;

use Daemon\SimplifyBundle\Entity\SimplifyEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * DaemonFile
 */
class DaemonFile extends SimplifyEntity
{

    /**
     * The name with which the file was uploaded
     *
     * @var string
     */
    private $origname;

    /**
     * The md5 hash to which the file was renamed
     *
     * @var string
     */
    private $filename;

    /**
     * The file extension
     *
     * @var string
     */
    private $extension;

    /**
     * The mime type of the file
     *
     * @var string
     */
    private $mimeType;

    /**
     * The media type of the file
     *
     * @var string
     */
    private $fileType;

    /**
     * The md5 hash of the file ... also the name to which the file was renamed( + extension);
     *
     * @var string
     */
    private $hashcode;

    /**
     * @var string
     */
    private $path;


    /**
     * Set origname
     *
     * @param string $origname
     * @return DaemonFile
     */
    public function setOrigname($origname)
    {
        $this->origname = $origname;

        return $this;
    }

    /**
     * Get origname
     *
     * @return string 
     */
    public function getOrigname()
    {
        return $this->origname;
    }

    /**
     * Set filename
     *
     * @param $filename
     * @return $this
     */
    public function setFilename($filename) {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename() {
        return $this->filename;
    }

    /**
     * Set extension
     *
     * @param $extension
     * @return DaemonFile
     */
    public function setExtension($extension) {
        $this->extension = $extension;

        return $this;
    }


    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension() {
        return $this->extension;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     * @return DaemonFile
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string 
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set fileType
     *
     * @param string $fileType
     * @return DaemonFile
     */
    public function setFileType($fileType)
    {
        $this->fileType = $fileType;

        return $this;
    }

    /**
     * Get fileType
     *
     * @return string 
     */
    public function getFileType()
    {
        return $this->fileType;
    }

    /**
     * Set hashcode
     *
     * @param string $hashcode
     * @return DaemonFile
     */
    public function setHashcode($hashcode)
    {
        $this->hashcode = $hashcode;

        return $this;
    }

    /**
     * Get hashcode
     *
     * @return string 
     */
    public function getHashcode()
    {
        return $this->hashcode;
    }

    /**
     * Set path
     *
     * @param $path
     * @return DaemonFile
     */
    public function setPath($path) {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath() {
        return $this->path;
    }
}
