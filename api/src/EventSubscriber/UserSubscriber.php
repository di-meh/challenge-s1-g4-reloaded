<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\User;
use App\Service\UserEmailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

final class UserSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private UserEmailService $userEmailService,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => [
                ['sendEmailConfirmation', EventPriorities::POST_WRITE],
            ],
        ];
    }

    
    public function sendEmailConfirmation(ViewEvent $event): void
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$user instanceof User || Request::METHOD_POST !== $method) {
            return;
        }

        try {
            $this->userEmailService->sendEmailVerification($user);
        } catch (TransportExceptionInterface $e) {
            return;
        }
    }

}
