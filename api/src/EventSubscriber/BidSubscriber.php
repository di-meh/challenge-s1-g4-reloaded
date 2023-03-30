<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Repository\BidRepository;
use App\Service\UserEmailService;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

final class BidSubscriber implements EventSubscriberInterface
{

    public function __construct(private readonly BidRepository $bidRepository)
    {
    }


    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'checkBidDate',
        ];
    }



    public function checkBidDate(RequestEvent $event): void
    {
        // Ici on récupères tous tes bids qui sont pas finished
        //$bids = $this->bidRepository->findBy(['finished' => false]);
        //dd($bids);
        // Après tu fais un foreach sur chaque bid
         //foreach ($bid of $bids) bref
        // if ($bid->getDate() <= new Date())
        // $bid->setFinished(true)
        // endforeach puis tu persist et flush
    }
}
