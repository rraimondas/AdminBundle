<?php

declare(strict_types=1);

namespace Platform\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

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

    public function indexAction(): Response
    {
        return $this->templating->renderResponse('PlatformAdminBundle:Dashboard:index.html.twig');
    }
}
