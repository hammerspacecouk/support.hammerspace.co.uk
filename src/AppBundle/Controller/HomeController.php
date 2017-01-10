<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function indexAction()
    {
        $this->toView('name', 'David');

        return $this->renderTemplate('home:index');
    }

    public function robotsAction()
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'text/plain');

        // default state should always be prod
        $path = 'AppBundle:home:robots.txt.twig';
        $enabled = $this->getParameter('enable_robots_txt');
        if ($enabled === false) {
            $path = 'AppBundle:home:robots-off.txt.twig';
        }

        return $this->render($path, [], $response);
    }
}
