<?php

namespace Platform\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class DashboardController
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * DashboardController constructor.
     * 
     * @param EngineInterface $templating
     */
    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function indexAction()
    {
        return $this->templating->renderResponse('PlatformAdminBundle:Dashboard:index.html.twig');
    }
}
