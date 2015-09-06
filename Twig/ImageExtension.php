<?php

/*
 * This file is part of the OpwocoBootstrapBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daemon\FilestorageBundle\Twig;

use Daemon\FilestorageBundle\Entity\DaemonFile;
use Doctrine\ORM\EntityManager;
use opwoco\Bundle\BootstrapBundle\Constant\IconSet;
use Symfony\Component\HttpFoundation\Response;

/**
 * OpwocoBootstrap Icon Extension.
 *
 */
class ImageExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    protected $environment;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var \Twig_Template
     */
    protected $imageTemplate;

    /**
     * Constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->entityManager = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        $functions = array(
            new \Twig_SimpleFunction('daemon_file', array($this, 'renderImage'), array('is_safe' => array('html'))),
        );

        return $functions;
    }

    /**
     * Renders the image.
     *
     * @param DaemonFile $daemonFile
     *
     * @return Response
     */
    public function renderImage(DaemonFile $daemonFile)
    {
        $template = $this->getTemplate();

        $context = array(
            'hashcode' => $daemonFile->getHashcode(),
            'title'    => $daemonFile->getOrigname(),
        );
        return $template->renderBlock($daemonFile->getMediaType(), $context);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'daemon_file_image';
    }

    /**
     * @return \Twig_Template
     */
    protected function getTemplate()
    {
        if ($this->imageTemplate === null) {
            $this->imageTemplate = $this->environment->loadTemplate('DaemonFilestorageBundle:Twig:render_file.html.twig');
        }

        return $this->imageTemplate;
    }
}
