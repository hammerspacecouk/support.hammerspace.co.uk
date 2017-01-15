<?php

declare(strict_types = 1);
namespace AppBundle\Controller;

use Stripe\Charge;
use Stripe\Error\Base;
use Stripe\Stripe;
use SupportService\Domain\Entity\Payment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class HomeController extends Controller
{
    private const PAGE_LIMIT = 5;

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
        $nextPageLink = null;
        if ($hasNextPage) {
            $nextPageLink = $this->generateUrl('home') . '?page=' . ($currentPage + 1);
        }

        $hasPrevPage = $currentPage > 1;
        $prevPageLink = null;
        if ($hasPrevPage) {
            $prevPageLink = $this->generateUrl('home');
            if ($currentPage > 2) {
                $prevPageLink .= '?page=' . ($currentPage - 1);
            }
        }

        $this->toView('stripeKey', $this->getParameter('stripe_key'));
        $this->toView('formAction', $this->generateUrl('process'));
        $this->toView('payments', $payments);
        $this->toView('resultStartNumber', (self::PAGE_LIMIT * ($currentPage-1)) + 1);
        $this->toView('hasResults', !empty($payments));
        $this->toView('hasPages', $hasPrevPage || $hasNextPage);
        $this->toView('nextPage', $nextPageLink);
        $this->toView('prevPage', $prevPageLink);

        $this->toView('paymentError', $this->request->get('error', null));
        $this->toView('paymentSuccess', $this->request->get('payment', null));

        return $this->renderTemplate('home:index', 'Support us');
    }

    public function processAction()
    {
        if (!$this->request->isMethod('POST')) {
            throw new MethodNotAllowedHttpException(['POST'], 'Must be POST');
        }
        $token = $this->request->get('paymentToken');
        $name = $this->request->get('name');
        $amount = (float) $this->request->get('amount');
        $message = $this->request->get('message');
        if (empty($message)) {
            $message = null;
        }

        if (!$token) {
            throw new BadRequestHttpException('Invalid request. No token found');
        }

        Stripe::setApiKey($this->getParameter('stripe_secret'));
        try {
            $charge = Charge::create([
                'amount' => $amount * 100,
                'currency' => 'gbp',
                'description' => 'hammerspace Donation - ' . $name,
                'source' => $token,
            ]);
            $this->get('logger')->notice('Successful payment of ' . $amount . ' from ' . $name);
        } catch (Base $e) {
            $this->get('logger')->error(
                'Failure to process payment of ' . $amount . ' from ' . $name . '. Message: ' . $e->getMessage()
            );
            return $this->redirectToRoute('home', ['error' => 'payment']);
        }
        $chargeId = $charge->id;
        $date = $this->get('app.time_provider');

        $this->get('app.services.payments')->createPayment($name, $message, $amount, $date, $chargeId);

        return $this->redirectToRoute('home', ['payment' => time()]);
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
