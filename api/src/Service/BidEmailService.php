<?php

namespace App\Service;

use App\Entity\Bid;
use App\Entity\TokenResetPassword;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class BidEmailService
{
    public function __construct(private VerifyEmailHelperInterface $helper, private MailerInterface $mailer, private UserRepository $repository)
    {
    }

    //Fonction pour le mail envoyé quand un autre utilisateur a surenchéri sur celle de l'utilisateur
    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmailBidOldOwner(User $user, Bid $bid): void
    {


        $mail = (new TemplatedEmail())
            ->subject('Vous n\'êtes plus le meilleur enchérisseur')
            ->to(new Address($user->getEmail()))
            ->htmlTemplate('emails/oldOwnerBidEmail.html.twig')
            ->context([
                'bid' => $bid,
            ]);

        $this->mailer->send($mail);
    }

    //Fonction pour le mail envoyé quand un autre utilisateur a surenchéri sur celle de l'utilisateur
    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmailBidNewOwner(User $user, Bid $bid): void
    {

        $mail = (new TemplatedEmail())
            ->subject('Votre surenchère a été prise en compte')
            ->to(new Address($user->getEmail()))
            ->htmlTemplate('emails/newOwnerBidEmail.html.twig')
            ->context([
                'bid' => $bid,
            ]);

        $this->mailer->send($mail);
    }

    //Fonction pour le mail envoyé quand l'enchère est terminer
    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmailBidFinishedOwner(User $user, Bid $bid): void
    {

        $mail = (new TemplatedEmail())
            ->subject('Vous avez remporté l\'enchère !')
            ->to(new Address($user->getEmail()))
            ->htmlTemplate('emails/finishedOwnerBidEmail.html.twig')
            ->context([
                'bid' => $bid,
            ]);

        $this->mailer->send($mail);
    }
}
