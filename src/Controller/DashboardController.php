<?php

declare(strict_types=1);

namespace Platform\Bundle\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class DashboardController
{
    private Environment $templating;

    public function __construct(Environment $templating)
    {
        $this->templating = $templating;
    }

    public function indexAction(): Response
    {
        $template = $this->templating->render('@PlatformAdmin/Dashboard/index.html.twig');

        return new Response($template);
    }
}
