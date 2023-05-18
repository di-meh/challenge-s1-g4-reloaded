<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Annonces;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class SetAnnonceOwnerEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly TokenStorageInterface $tokenStorage)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['annonceOwner', EventPriorities::PRE_WRITE],
        ];
    }

    public function annonceOwner(ViewEvent $event): void
    {
        $annonces = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$annonces instanceof Annonces || Request::METHOD_POST !== $method) {
            return;
        }

        $user = $this->tokenStorage->getToken()->getUser();
        $annonces->setAnnonceOwner($user);
        $this->entityManager->persist($annonces);
        $this->entityManager->flush();
    }
}