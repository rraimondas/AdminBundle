<?php

declare(strict_types=1);

namespace Platform\Bundle\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

class DashboardController
{
    private EngineInterface $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function indexAction(): Response
    {
        $template = $this->templating->render('@PlatformAdmin/Dashboard/index.html.twig');

        return new Response($template);
    }
}
