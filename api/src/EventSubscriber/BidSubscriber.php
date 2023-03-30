<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Repository\BidRepository;
use App\Service\UserEmailService;
use DateTime;
use EasyRdf\Literal\Date;
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
        $bids = $this->bidRepository->findBy(['finished' => false]);
        // Après on parcours chaque bid
        foreach ($bids as $bid) {
            // Si la date de la bid est inférieur ou égale à la date du jour
            if ($bid->getEndDate() <= new DateTime()) {
                // Alors on set le bid à finished
                $bid->setFinished(true);
                // Puis on  sauvegarde la bid en base de données
                $this->bidRepository->save($bid, true);
            }
        }
    }
}
