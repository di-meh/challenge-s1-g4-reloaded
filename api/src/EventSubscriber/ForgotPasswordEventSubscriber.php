<?php

namespace App\EventSubscriber;

use App\Repository\UserRepository;
use CoopTilleuls\ForgotPasswordBundle\Event\CreateTokenEvent;
use CoopTilleuls\ForgotPasswordBundle\Event\UpdatePasswordEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class ForgotPasswordEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly TokenStorageInterface $tokenStorage, private readonly MailerInterface $mailer, private UserRepository $userRepository)
    {
    }
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
            CreateTokenEvent::class => 'onCreateToken',
            UpdatePasswordEvent::class => 'onUpdatePassword',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest() || !str_starts_with($event->getRequest()->get('_route'), 'coop_tilleuls_forgot_password')) {
            return;
        }

        // User should not be authenticated on forgot password
        $token = $this->tokenStorage->getToken();
        if (null !== $token && $token->getUser() instanceof UserInterface) {
            throw new AccessDeniedHttpException;
        }

        $user = $this->userRepository->findOneBy(['email' => $event->getRequest()->get('value')]);
        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }
    }

    /**
     * @throws TransportExceptionInterface
     * @throws NotFoundHttpException
     */
    public function onCreateToken(CreateTokenEvent $event): void
    {
        $passwordToken = $event->getPasswordToken();
        $user = $passwordToken->getUser();
        $entityUser = $this->userRepository->findOneBy(['email' => $user->getEmail()]);
        if (!$entityUser) {
            throw new NotFoundHttpException('User not found');
        }

        $message = (new Email())
            ->to(new Address($user->getEmail()))
            ->subject('Réinitialisation de votre mot de passe')
            ->text('Réinitialisez votre mot de passe en cliquant ici: ' . $_ENV["FRONT_URL"] . '/reset-password/' . $passwordToken->getToken());
        $this->mailer->send($message);
    }

    public function onUpdatePassword(UpdatePasswordEvent $event): void
    {
        $passwordToken = $event->getPasswordToken();
        $user = $passwordToken->getUser();
        $user->setPassword($event->getPassword());
        $this->userRepository->save($user, true);
    }
}
