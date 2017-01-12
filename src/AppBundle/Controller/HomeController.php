<?php

declare(strict_types = 1);
namespace AppBundle\Controller;

use SupportService\Domain\Entity\Payment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HomeController extends Controller
{
    private const PAGE_LIMIT = 30;

    public function indexAction()
    {
        $currentPage = $this->getCurrentPage();
        $paymentsService = $this->get('app.services.payments');

        $payments = [];
        $totalCount = $paymentsService->countAll();

        $totalPages = ceil($totalCount / self::PAGE_LIMIT);
        if ($totalCount && $currentPage > $totalPages) {
            throw new HttpException(404, 'No such page');
        }

        if ($totalCount > 0) {
            $paymentData = $paymentsService->findAll(self::PAGE_LIMIT, $currentPage);
            foreach ($paymentData as $payment) {
                $payments[] = $this->makePaymentView($payment);
            }
        }

        $hasNextPage = $currentPage < $totalPages;
        $hasPrevPage = $currentPage > 1;

        $this->toView('formAction', $this->generateUrl('process'));
        $this->toView('payments', $payments);
        $this->toView('resultStartNumber', (self::PAGE_LIMIT * ($currentPage-1)) + 1);
        $this->toView('hasResults', !empty($payments));
        $this->toView('hasPages', $hasPrevPage || $hasNextPage);
        $this->toView('hasNextPage', $hasNextPage);
        $this->toView('hasPrevPage', $hasPrevPage);

        return $this->renderTemplate('home:index');
    }

    public function processAction()
    {
        if (!$this->request->isMethod('POST')) {
            throw new HttpException(405, 'Must be POST'); // todo - ensure the code is sent to the user
        }
        $name = $this->request->get('name');
        $amount = (float) $this->request->get('amount');
        $message = $this->request->get('message');
        if (empty($message)) {
            $message = null;
        }
        $date = $this->get('app.time_provider');

        $payment = $this->get('app.services.payments')->createPayment($name, $message, $amount, $date);

        $this->toView('payment', $this->makePaymentView($payment));
        $this->toView('returnLink', $this->generateUrl('home') . '?done=' . time());

        return $this->renderTemplate('home:process');
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

    private function makePaymentView(Payment $payment): array
    {
        return [
            'name' => $payment->getName(),
            'amount' => '£' . number_format($payment->getAmount(), 2),
            'message' => $payment->getMessage(),
            'date' => $payment->getDate()->format('d M Y'),
        ];
    }

    private function getCurrentPage(): int
    {
        $page = $this->request->get('page', 1);

        // must be an integer string
        if (strval(intval($page)) !== strval($page) ||
            $page < 1
        ) {
            throw new HttpException(404, 'No such page value');
        }
        return (int) $page;
    }
}
