<?php

namespace App\Controller;

use App\Service\StripeService;
use Doctrine\ORM\Exception\EntityManagerClosed;
use Psr\Log\LoggerInterface;
use Stripe\Exception\SignatureVerificationException;
use Stripe\StripeClient;
use Stripe\Webhook;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeWebhookController extends AbstractController
{

    public function __construct(private StripeService $stripeService)
    {
    }

    #[Route('/api/stripe/webhook', name: 'app_stripe_webhook', methods: ['POST'])]
    public function index(Request $request): Response
    {
        $event = null;
        $stripe = new StripeClient($_SERVER['STRIPE_SECRET_KEY']);
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $payload = @file_get_contents('php://input');
        $endpoint_secret = $_SERVER['STRIPE_WEBHOOK_SECRET'];

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            return new Response('Invalid payload', 400);
        } catch(SignatureVerificationException $e) {
            // Invalid signature
            return new Response('Invalid signature', 400);
        }

        switch ($event->type) {
            case "checkout.session.completed":
                $this->stripeService->handlePaymentSucceeded($event->data->object);
                return new Response('Webhook successful', 200);
            default:
                return new Response('Unhandled webhook event type', 400);
        }



    }
}
