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
     * The file extension
     *
     * @var string
     */
    private $extension;

    /**
     * The mediaType of the file
     *
     * @var string
     */
    private $mediaType;

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
     * Get filename
     *
     * @return string
     */
    public function getFilename() {
        return substr($this->path, strrpos($this->path, '/'));
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
     * Set mediaType
     *
     * @param string $mediaType
     * @return DaemonFile
     */
    public function setMediaType($mediaType)
    {
        $this->mediaType = $mediaType;

        return $this;
    }

    /**
     * Get mediaType
     *
     * @return string
     */
    public function getMediaType()
    {
        return $this->mediaType;
    }

    /**
     * Get mimemediaType
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mediaType . '/' . $this->extension;
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
