<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Annonces;
use App\Entity\Items;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\StripeClient;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Request;

final class ItemStripeCreationEventSubscriber implements EventSubscriberInterface
{

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['createStripeProduct', EventPriorities::POST_WRITE],
        ];
    }

    public function createStripeProduct(ViewEvent $event): void
    {
        $item = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$item instanceof Annonces || Request::METHOD_POST !== $method) {
            return;
        }

        $stripe = new StripeClient($_SERVER['STRIPE_SECRET_KEY']);
        $stripeProduct = $stripe->products->create([
            'name' => $item->getTitle(),
            'description' => $item->getDescription(),
            'default_price_data' => [
                'currency' => 'eur',
                'unit_amount' => $item->getPrice() * 100,
            ]
        ]);

        $item->setStripePriceId($stripeProduct->default_price);
        $item->setStripeProductId($stripeProduct->id);
        
        $this->entityManager->flush();

    }
}