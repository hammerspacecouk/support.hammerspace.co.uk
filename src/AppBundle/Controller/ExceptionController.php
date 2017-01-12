<?php

declare(strict_types = 1);
namespace AppBundle\Controller;

use Symfony\Bundle\TwigBundle\Controller\ExceptionController as TwigExceptionController;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class ExceptionController extends TwigExceptionController
{
    public function showAction(
        Request $request,
        FlattenException $exception,
        DebugLoggerInterface $logger = null
    ): Response {

        $code = $exception->getStatusCode();
        $template = 'error';
        switch ($code) {
            case 404:
                $template = 'error404';
                break;
            case 403:
                $template = 'error403';
                break;
        }

        return new Response($this->twig->render(
            'AppBundle:exception:' . $template . '.html.twig',
            [
                'status_code' => $code,
                'status_text' => isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '',
                'exception' => $exception,
                'logger' => $logger,
            ]
        ));
    }
}
