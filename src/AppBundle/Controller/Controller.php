<?php

namespace AppBundle\Controller;

use AppBundle\Presenter\MasterPresenter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Controller extends BaseController implements ControllerInterface
{

    /** @var MasterPresenter */
    public $masterViewPresenter;

    /** @var Request */
    public $request;

    protected $cacheTime = 300;

    /** Setup common tasks for a controller */
    public function initialize(Request $request)
    {
        $this->request = $request;
        $this->masterViewPresenter = new MasterPresenter(
            $this->get('kernel')->getEnvironment()
        );
    }

    public function toView(
        string $key,
        $value,
        $inFeed = null
    ): Controller {
        $this->masterViewPresenter->set($key, $value, $inFeed);
        return $this;
    }

    public function fromView(string $key)
    {
        return $this->masterViewPresenter->get($key);
    }

    public function setTitle(string $title): Controller
    {
        $this->masterViewPresenter->setTitle($title);
        return $this;
    }

    protected function setCacheHeaders(Response $response): void
    {
        if ($this->cacheTime) {
            $response->setPublic();

            $response->setMaxAge($this->cacheTime);
            $response->setSharedMaxAge($this->cacheTime);
        } else {
            $response->setPrivate();
            $response->setMaxAge(0);
        }
    }

    protected function renderJSON()
    {
        return $this->renderTemplate('json');
    }

    protected function renderTemplate($template, $title = null): Response
    {
        if ($title) {
            $this->setTitle($title);
        }

        $format = $this->request->get('format', null);
        if ($format == 'json' || $template == 'json') {
            $response = new JsonResponse($this->masterViewPresenter->getFeedData());
            $this->setCacheHeaders($response);
            return $response;
        }

        $ext = 'html';
        if (in_array($format, ['inc'])) {
            $ext = $format;
        } elseif ($format) {
            throw new HttpException(404, 'Invalid Format');
        }

        $response = new Response();
        $this->setCacheHeaders($response);

        $path = 'AppBundle:' . $template . '.' . $ext . '.twig';
        return $this->render($path, $this->masterViewPresenter->getData(), $response);
    }
}
