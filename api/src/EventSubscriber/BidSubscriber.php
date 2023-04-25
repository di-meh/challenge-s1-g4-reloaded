<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Bid;
use App\Repository\BidRepository;
use App\Service\BidEmailService;
use DateTime;
use EasyRdf\Literal\Date;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class BidSubscriber implements EventSubscriberInterface
{

    public function __construct(private readonly TokenStorageInterface $tokenStorage, private readonly BidRepository $bidRepository, private readonly BidEmailService $bidEmailService)
    {
    }
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'checkBidDate',
            KernelEvents::VIEW => [
                ['sendEmailBid', EventPriorities::POST_WRITE],
            ],
        ];
    }


    public function checkBidDate(RequestEvent $event): void
    {
        // Ici on récupères tous tes bids qui sont pas finished
        $bids = $this->bidRepository->findBy(['finished' => false]);

        // Après on parcours chaque bid
        foreach ($bids as $bid) {
            // Si la date de la bid est inférieur ou égale à la date du jour
            $now = new DateTime('Europe/Paris');
            $now = $now->format('Y-m-d H:i:s');
            if ($bid->getEndDate()->format('Y-m-d H:i:s') <= $now) {
                // Alors on set le bid à finished
                $bid->setFinished(true);
                // Puis on  sauvegarde la bid en base de données
                $this->bidRepository->save($bid, true);
                $this->bidEmailService->sendEmailBidFinishedOwner($bid->getOwner());
            }
        }
    }
    //Fonction qui permet d'envoyer un mail de confirmation et un mail pour prévenir l'ancien propriètaire quand
    // il y a une nouvelle enchère

     /**
      * @throws TransportExceptionInterface
      */
     public function sendEmailBid(ViewEvent $event): void
     {
        //Je dois check si un owner existe, s'il existe je dois lui envoyer un mail pour lui dire qu'il a été remplacé
        //Je dois envoyer un mail de confirmation à l'utilisateur qui vient d'enchérir
        //Je dois récuperer la bid qui veut être modifier
        $bid = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if($bid instanceof Bid && $bid->getOwner() !== null && Request::METHOD_PUT === $method ){
            //Envoie un mail à l'ancien propriétaire pour lui dire qu'il a été remplacé
            $this->bidEmailService->sendEmailBidOldOwner($bid->getOwner());
            //Récupérer l'utilisateur qui est connecté
            $user = $this->tokenStorage->getToken()->getUser();
            //Assigne l'utilisateur à la bid
            $bid->setOwner($user);
            //Sauvegarde la bid en base de données
            $this->bidRepository->save($bid, true);
            //Envoie un mail de confirmation à l'utilisateur qui vient d'enchérir
            $this->bidEmailService->sendEmailBidNewOwner($user);
        }else if($bid instanceof Bid && $bid->getOwner() === null){
            //Récupérer l'utilisateur qui est connecté
            $user = $this->tokenStorage->getToken()->getUser();
            //Assigne l'utilisateur à la bid
            $bid->setOwner($user);
            //Sauvegarde la bid en base de données
            $this->bidRepository->save($bid, true);
            $this->bidEmailService->sendEmailBidNewOwner($bid->getOwner());
        }
     }
}
